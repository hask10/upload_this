<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SubscriptionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;
use Str;

class GenerateImagesController extends Controller
{
    # images
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->user_type == "customer") {
            $package = $user->SubscriptionPackage;
            if ($package->allow_images == 0) {
                abort(403);
            }
        } else {
            if (!auth()->user()->can('generate_images')) {
                abort(403);
            }
        }

        $searchKey = null;
        $images = Project::where('content_type', 'image')->where('user_id', auth()->user()->id)->latest();

        if ($request->search != null) {
            $images = $images->where('title', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        $images = $images->paginate(paginationNumber(18));
        return view('backend.pages.templates.generate-images', ['images' => $images, 'searchKey' => $searchKey]);
    }

    # generate images
    public function generate(Request $request)
    {

        if (env('DEMO_MODE') == "On") {
            $data = [
                'status'  => 400,
                'success' => false,
                'message' => localize('Image generation is turned off in demo')
            ];
            return $data;
        }

        $user = auth()->user();

        # 1. init openAi
        $open_ai = new OpenAi(config('services.open-ai.key'));

        # 2. verify if user has access to the template [template available in subscription package]
        if ($user->user_type == "customer") {
            if ($user->subscription_package_id == null) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('Please upgrade your subscription plan'),
                ];
                return $data;
            }

            // package
            $package = $user->subscriptionPackage;
            # 3. validity of the package & verify if the user has word limit

            //  check if allow images is enabled
            if ((int) $package->allow_images == 0) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('AI Images is not available in this package, please upgrade you plan'),
                ];
                return $data;
            }

            $subscriptionHistory = SubscriptionHistory::where('subscription_package_id', $user->subscription_package_id)->latest()->first();
            if (empty($subscriptionHistory)) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('Please upgrade your subscription plan'),
                ];
                return $data;
            }
            // check validity
            $days = 30;
            if ($package->package_type == "yearly") {
                $days = 365; // 1 year
            }

            if ($package->package_type == "lifetime") {
                $days = 365 * 100; // 100 years
            }
            if (Carbon::now() > $subscriptionHistory->created_at->addDays($days)) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('Your subscription is expired, please upgrade you plan'),
                ];
                return $data;
            }

            // check images limit  
            if ($user->this_month_available_images < (int)$request->num_of_results) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('Your limit is low, please upgrade you plan'),
                ];
                return $data;
            }
        }

        # 4. generate prompt in selected language 
        $parsePromptsController = new ParsePromptsController;
        $prompt                 = $parsePromptsController->images($request->all());


        # 6. generate image
        $n              = 1;
        $resolution     = '256x256';

        if (env('DEMO_MODE') == 'Off') {
            $n              = (int)$request->num_of_results;
            $resolution     = $request->resolution;
        }

        $result = $open_ai->image([
            'prompt' => $prompt,
            'size' => $resolution,
            'n' => $n,
            "response_format" => "url",
        ]);

        # parse response
        $result = json_decode($result, true);

        if (isset($result['data'])) {
            if (count($result['data']) > 1) {
                foreach ($result['data'] as $key => $value) {
                    $url = $value['url'];

                    $name = Str::random(10) . '.png';
                    $image = file_get_contents($url);
                    file_put_contents(public_path('images/' . $name), $image);

                    $project = new Project;
                    $project->user_id       = $user->id;
                    $project->title         = $request->title . '-' . ($key + 1);
                    $project->slug          = Str::slug($project->title) . '-' . strtolower(Str::random(5));
                    $project->content_type  = 'image';
                    $project->resolution    = $resolution;
                    $project->content       = 'images/' . $name;
                    $project->save();
                }
            } else {
                $url = $result['data'][0]['url'];
                $name = Str::random(10) . '.png';
                $image = file_get_contents($url);
                file_put_contents(public_path('images/' . $name), $image);

                $project = new Project;
                $project->user_id       = $user->id;
                $project->title         = $request->title;
                $project->slug          = Str::slug($project->title) . '-' . strtolower(Str::random(5));
                $project->content_type  = 'image';
                $project->resolution    = $resolution;
                $project->content       = 'images/' . $name;
                $project->save();
            }

            # Update credit balance
            $this->updateUserImages($user, $n);


            $images = Project::where('content_type', 'image')->where('user_id', auth()->user()->id)->latest();
            $images = $images->paginate(paginationNumber());

            $data = [
                'status'            => 200,
                'success'           => true,
                'images'            => view('backend.pages.templates.inc.images-list', compact('images'))->render(),
                'usedPercentage'    => view('backend.pages.templates.inc.used-images-percentage')->render(),
            ];
            return $data;
        } else {
            $message = $result['error']['message'];
            $data = [
                'status'  => 400,
                'success' => false,
                'message' => $message
            ];
            return $data;
        }

        $data = [
            'status'  => 500,
            'success' => false,
        ];
        return $data;
    }

    # updateUserImages - take n
    public function updateUserImages($user, $n)
    {
        if ($user->user_type == "customer") {
            $user->this_month_used_images       += (int) $n;
            $user->this_month_available_images  -= (int) $n;
            $user->total_used_images            += (int) $n;
            $user->save();
        } else {
            $user->total_used_images            += (int) $n;
            $user->save();
        }
    }

    # delete image
    public function delete($id)
    {
        $image = Project::where('user_id', auth()->user()->id)->where('id', $id)->first();
        if (!is_null($image)) {
            try {
                fileDelete($image->content);
            } catch (\Throwable $th) {
                //throw $th;
            }
            $image->delete();
        }
        flash(localize('Image has been deleted successfully'))->success();
        return back();
    }
}
