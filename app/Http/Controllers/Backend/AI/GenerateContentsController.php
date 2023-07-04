<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\CustomTemplate;
use App\Models\Project;
use App\Models\SubscriptionHistory;
use App\Models\SubscriptionPackageTemplate;
use App\Models\Template;
use App\Models\TemplateUsage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;
use Str;

class GenerateContentsController extends Controller
{
    # generate contents
    public function generate(Request $request)
    {
        $user = auth()->user();

        # 1. init openAi
        $open_ai = new OpenAi(config('services.open-ai.key'));

        $template = Template::where('code', $request->template_code)->first();
        if (empty($template)) {
            abort(404);
        }

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

            // subscription package template based on template
            $subscriptionTemplate = SubscriptionPackageTemplate::where('template_id', $template->id)->where('subscription_package_id', $package->id)->first();
            if (empty($subscriptionTemplate)) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('You do not have access to this template'),
                ];
                return $data;
            }

            # 3. validity of the package & verify if the user has word limit
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

        # 4. generate prompt in selected language 
        $parsePromptsController = new ParsePromptsController;
        $prompt                 = $parsePromptsController->index($request->all());

        if (preg_match("/bad_words_found/i", $prompt) == 1) {
            $badWords =  explode('_#themeTags', rtrim($prompt, ","));
            $data = [
                'status'  => 400,
                'success' => false,
                'message' => localize('Please remove these words from your inputs') . '-' . $badWords[1],
            ];
            return $data;
        }

        # 5. apply openAi model based on admin configuration  
        $model = 'gpt-3.5-turbo'; // default model
        if ($user->user_type == "customer") {
            $model = $user->subscriptionPackage->openai_model->key;
        }

        # 6. generate contents
        $temperature    = (float)$request->creativity;
        $max_tokens     = getSetting('default_max_result_length', -1);

        if ($request->max_tokens != null) {
            $max_tokens     = (int)$request->max_tokens;
        }

        $n              = (int)$request->num_of_results;
        $num_of_results = 1;

        $latestModels = [
            'gpt-4',
            'gpt-4-32k',
            'gpt-3.5-turbo'
        ];

        // ai params
        $aiParams = [
            'model' => $model,
            'temperature' => $temperature,
            'n' => $n,
        ];

        if ($max_tokens != -1) {
            $aiParams['max_tokens'] = $max_tokens;
        }

        # make api call to openAi
        if (in_array($model, $latestModels)) {
            $aiParams['messages'] = [[
                "role" => "user",
                "content" => $prompt
            ]];
            $result = $open_ai->chat($aiParams);
        } else {
            $aiParams['prompt'] = $prompt;
            $result = $open_ai->completion($aiParams);
        }

        # parse response
        $result = json_decode($result, true);

        $outputContents = '';
        if (isset($result['choices'])) {
            if (in_array($model, $latestModels)) {
                if (count($result['choices']) > 1) {
                    foreach ($result['choices'] as $value) {
                        $outputContents .= '<b>[Output-' . $num_of_results . ']</b>' . "\r\n" . trim($value['message']['content']) . "\r\n\r\n\r\n";
                        $num_of_results++;
                    }
                } else {
                    $outputContents = ($result['choices'][0]['message']['content']);
                }
            } else {
                if (count($result['choices']) > 1) {
                    foreach ($result['choices'] as $value) {
                        $outputContents .= '<b>[Output-' . $num_of_results . ']</b>' . "\r\n" . ltrim($value['text']) . "\r\n\r\n\r\n";
                        $num_of_results++;
                    }
                } else {
                    $outputContents = ($result['choices'][0]['text']);
                }
            }

            // $outputContents = nl2br($outputContents);
            $outputContents = str_replace(["\r\n", "\r", "\n"], "<br/>", $outputContents);
            $promptsToken = $result['usage']['prompt_tokens'];
            $completionToken = $result['usage']['completion_tokens'];
            $tokens = $result['usage']['total_tokens'];

            # 7. Save it as a project 
            $projectTitle = "Untitled Project - " . date("Y-m-d");
            if ($request->project_id == null) {
                $project = new Project;
                $project->user_id       = $user->id;
                $project->template_id   = $template->id;
                $project->model_name    = $model;
                $project->title         = $projectTitle;
                $project->slug          = Str::slug($projectTitle) . '-' . strtolower(Str::random(5));
                $project->prompts       = $promptsToken;
                $project->completion    = $completionToken;
                $project->words         = $tokens;
                $project->content_type  = 'content';
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

            # 8. update word limit for user or admin/staff
            $this->updateUserWords($tokens, $user);

            # 9. update template usage
            $this->updateTemplateUsages($tokens, $template, $user);

            $data = [
                'status'            => 200,
                'success'           => true,
                'output'            => trim($outputContents),
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

    # generate contents
    public function generateCustom(Request $request)
    {
        $user = auth()->user();

        # 1. init openAi
        $open_ai = new OpenAi(config('services.open-ai.key'));

        $template = CustomTemplate::where('code', $request->template_code)->first();
        if (empty($template)) {
            abort(404);
        }

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

            // check if allow custom template content is enabled
            if ((int) $package->allow_custom_templates == 0) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('Custom template is not available in this package, please upgrade you plan'),
                ];
                return $data;
            }

            # 3. validity of the package & verify if the user has word limit
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

        # 4. generate prompt
        $prompt  = $template->prompt;

        // dd($request->all());
        foreach ($request->all() as $name => $inpVal) {
            if ($name != '_token' && $name != 'project_id' && $name != 'max_tokens') {
                $name = '{_' . $name . '_}';
                if (!is_null($inpVal) && !is_null($name)) {
                    $prompt = str_replace($name, $inpVal, $prompt);
                } else {
                    $data = [
                        'status'  => 400,
                        'success' => false,
                        'message' => localize('Your input does not match with the custom prompt'),
                    ];
                    return $data;
                }
            }
        }

        $prompt .= 'In ' . $request->lang . ' language.'  . ' The tone of voice should be ' . $request->tone . '. Do not write translations.';


        # 5. apply openAi model based on admin configuration  
        $model = 'gpt-3.5-turbo'; // default model
        if ($user->user_type == "customer") {
            $model = $user->subscriptionPackage->openai_model->key;
        }

        # 6. generate contents
        $temperature    = (float)$request->creativity;
        $max_tokens     =  getSetting('default_max_result_length', -1);

        if ($request->max_tokens != null) {
            $max_tokens     = (int)$request->max_tokens;
        }

        $n              = (int)$request->num_of_results;
        $num_of_results = 1;

        $latestModels = [
            'gpt-4',
            'gpt-4-32k',
            'gpt-3.5-turbo'
        ];

        // ai params
        $aiParams = [
            'model' => $model,
            'temperature' => $temperature,
            'n' => $n,
        ];

        if ($max_tokens != -1 && $max_tokens != 0) {
            $aiParams['max_tokens'] = (int) $max_tokens;
        }

        # make api call to openAi
        if (in_array($model, $latestModels)) {
            $aiParams['messages'] = [[
                "role" => "user",
                "content" => $prompt
            ]];
            $result = $open_ai->chat($aiParams);
        } else {
            $aiParams['prompt'] = $prompt;
            $result = $open_ai->completion($aiParams);
        }

        # parse response
        $result = json_decode($result, true);

        $outputContents = '';
        if (isset($result['choices'])) {
            if (in_array($model, $latestModels)) {
                if (count($result['choices']) > 1) {
                    foreach ($result['choices'] as $value) {
                        $outputContents .= '<b>[Output-' . $num_of_results . ']</b>' . "\r\n" . trim($value['message']['content']) . "\r\n\r\n\r\n";
                        $num_of_results++;
                    }
                } else {
                    $outputContents = ($result['choices'][0]['message']['content']);
                }
            } else {
                if (count($result['choices']) > 1) {
                    foreach ($result['choices'] as $value) {
                        $outputContents .= '<b>[Output-' . $num_of_results . ']</b>' . "\r\n" . ltrim($value['text']) . "\r\n\r\n\r\n";
                        $num_of_results++;
                    }
                } else {
                    $outputContents = ($result['choices'][0]['text']);
                }
            }

            // $outputContents = nl2br($outputContents);  
            $outputContents = str_replace(["\r\n", "\r", "\n"], "<br/>", $outputContents);

            $promptsToken = $result['usage']['prompt_tokens'];
            $completionToken = $result['usage']['completion_tokens'];
            $tokens = $result['usage']['total_tokens'];

            # 7. Save it as a project 
            $projectTitle = "Untitled Project - " . date("Y-m-d");
            if ($request->project_id == null) {
                $project = new Project;
                $project->user_id       = $user->id;
                $project->custom_template_id   = $template->id;
                $project->model_name    = $model;
                $project->title         = $projectTitle;
                $project->slug          = Str::slug($projectTitle) . '-' . strtolower(Str::random(5));
                $project->prompts       = $promptsToken;
                $project->completion    = $completionToken;
                $project->words         = $tokens;
                $project->content_type  = 'content';
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

            # 8. update word limit for user or admin/staff
            $this->updateUserWords($tokens, $user);

            # 9. update template usage
            $this->updateTemplateUsages($tokens, $template, $user, true);

            $data = [
                'status'            => 200,
                'success'           => true,
                'output'            => trim($outputContents),
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

    # updateTemplateUsages - take token as word
    public function updateTemplateUsages($tokens, $template, $user, $customTemplate = false)
    {
        // user wise template usage
        $template->total_words_generated += (int) $tokens;
        $template->save();

        // user wise template usage
        $templateUsage                      = new TemplateUsage;
        $templateUsage->user_id             = $user->id;
        if ($customTemplate) {
            $templateUsage->custom_template_id         = $template->id;
        } else {
            $templateUsage->template_id         = $template->id;
        }
        $templateUsage->total_used_words    = $template->total_words_generated;
        $templateUsage->save();
    }
}
