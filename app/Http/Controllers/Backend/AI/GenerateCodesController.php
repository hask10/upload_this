<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SubscriptionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;
use Str;

class GenerateCodesController extends Controller
{
    # code
    public function index()
    {
        $user = auth()->user();
        if ($user->user_type == "customer") {
            $package = $user->SubscriptionPackage;
            if ($package->allow_ai_code == 0) {
                abort(403);
            }
        } else {
            if (!auth()->user()->can('generate_code')) {
                abort(403);
            }
        }

        return view('backend.pages.templates.generate-codes');
    }

    # generate code
    public function generate(Request $request)
    {
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
            if ((int) $package->allow_ai_code == 0) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('AI Code is not available in this package, please upgrade you plan'),
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

            // check word limit  
            if ($user->this_month_available_words < 50) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('Your word balance is low, please upgrade you plan'),
                ];
                return $data;
            }
        }

        # 4. generate code
        $model = 'gpt-3.5-turbo';
        $result = $open_ai->chat([
            'model' => $model,
            'messages' => [
                [
                    "role" => "system",
                    "content" => "You are a creative assistant that writes code."
                ],
                [
                    "role" => "user",
                    "content" => $request->description,
                ],
            ],
            'temperature' => 1,
            'max_tokens' => 4000,
        ]);


        # parse response
        $result = json_decode($result, true);

        $outputContents = '';
        if (isset($result['choices'])) {
            $outputContents = ($result['choices'][0]['message']['content']);
            $promptsToken = $result['usage']['prompt_tokens'];
            $completionToken = $result['usage']['completion_tokens'];
            $tokens = $result['usage']['total_tokens'];

            # 5. Save it as a project 
            $projectTitle = $request->title;
            if ($request->project_id == null) {
                $project = new Project;
                $project->user_id       = $user->id;
                $project->model_name    = $model;
                $project->title         = $projectTitle;
                $project->slug          = Str::slug($projectTitle) . '-' . strtolower(Str::random(5));
                $project->prompts       = $promptsToken;
                $project->completion    = $completionToken;
                $project->words         = $tokens;
                $project->content_type  = 'code';
                $project->content       = trim($outputContents);
                $project->save();
            } else {
                $project = Project::where('id', $request->project_id)->first();
                if (!is_null($project)) {
                    $project->words         = $tokens;
                    $project->content       = trim($outputContents);
                    $project->save();
                }
            }

            # 6. update word limit for user or admin/staff
            $this->updateUserWords($tokens, $user);

            $data = [
                'status'            => 200,
                'success'           => true,
                'output'            => view('backend.pages.templates.inc.contentCode', compact('project'))->render(),
                'title'             => $projectTitle,
                'project_id'        => $project->id ?? '',
                'usedPercentage'    => view('backend.pages.templates.inc.used-words-percentage')->render(),
            ];
            return $data;
        } else {
            if (isset($result['error']['message'])) {
                $message = $result['error']['message'];
            } else {
                $message = localize('There is an issue with the openai account');
            }
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

    # updateUserWords - take token as word
    public function updateUserWords($tokens, $user)
    {
        if ($user->user_type == "customer") {
            $user->this_month_used_words        += (int) $tokens;
            $user->this_month_available_words   -= (int) $tokens;
            $user->total_used_words             += (int) $tokens;
            $user->save();
        } else {
            $user->total_used_words             += (int) $tokens;
            $user->save();
        }
    }
}
