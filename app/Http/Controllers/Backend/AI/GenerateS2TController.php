<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SubscriptionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;
use Str;
use Illuminate\Support\Facades\Validator;

class GenerateS2TController extends Controller
{
    # code
    public function index()
    {
        $user = auth()->user();

        if ($user->user_type == "customer") {
            $package = $user->SubscriptionPackage;
            if ($package->allow_speech_to_text == 0) {
                abort(403);
            }
        } else {
            if (!auth()->user()->can('speech_to_text')) {
                abort(403);
            }
        }

        return view('backend.pages.templates.generate-s2t');
    }

    # generate code
    public function generate(Request $request)
    {

        if (env('DEMO_MODE') == "On") {
            $data = [
                'status'  => 400,
                'success' => false,
                'message' => localize('Text to speech is turned off in demo'),
            ];
            return $data;
        }

        $user = auth()->user();
        $sizeLimit = 0; // unlimited

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
            if ((int) $package->allow_speech_to_text == 0) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('Speech to text is not available in this package, please upgrade you plan'),
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

            // check s2t limit  
            if ($user->this_month_available_s2t < 1) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('Your balance is low, please upgrade you plan'),
                ];
                return $data;
            }

            // check file size
            if ($package->speech_to_text_filesize_limit > 0) {
                $sizeLimit = $package->speech_to_text_filesize_limit;
            }
        } else {
            $sizeLimit = 1000;
        }
        $fileLimit = (int)($sizeLimit) * 1024;
        if ($request->file('audio') != null) {

            $rules = [
                'audio' => 'required|mimes:mp3,mp4,mpeg,mpga,m4a,wav,webm|max:' . $fileLimit . ''
            ];

            $messages = ['audio.max' => localize('Max file size is: ') . $sizeLimit . 'MB'];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as  $msg) {
                    $message .= $msg . ' ';
                }
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => $message,
                ];
                return $data;
            }
        }

        # 4. generate code
        $model = 'whisper-1';

        $audio = $request->file('audio');
        $audioUrl = $audio->store('s2t');

        $audioPath = public_path() . DIRECTORY_SEPARATOR . $audioUrl;

        $file = curl_file_create($audioPath);

        $result = $open_ai->transcribe([
            'model' => $model,
            'file' => $file,
        ]);

        fileDelete($audioUrl);

        # parse response
        $result = json_decode($result, true);
        $outputContents = '';
        if (isset($result['text'])) {
            $outputContents = $result['text'];
            // $outputContents = nl2br($outputContents);
            $outputContents = str_replace(["\r\n", "\r", "\n"], "<br/>", $outputContents);
            $promptsToken = 0;
            $completionToken = count(explode(' ', $result['text']));
            $tokens =  count(explode(' ', $result['text']));

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
                $project->content_type  = 'speech_to_text';
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
            $this->updateUserS2T($user);

            $data = [
                'status'            => 200,
                'success'           => true,
                'output'            => trim($outputContents),
                'title'             => $projectTitle,
                'project_id'        => $project->id ?? '',
                'usedPercentage'    => view('backend.pages.templates.inc.used-s2t-percentage')->render(),
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

    # updateUserS2T - take token as word
    public function updateUserS2T($user)
    {
        if ($user->user_type == "customer") {
            $user->this_month_used_s2t        += 1;
            $user->this_month_available_s2t   -= 1;
            $user->total_used_s2t             += 1;
            $user->save();
        } else {
            $user->total_used_s2t             += 1;
            $user->save();
        }
    }
}
