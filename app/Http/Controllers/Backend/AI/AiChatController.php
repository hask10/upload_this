<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\AiChat;
use App\Models\AiChatCategory;
use App\Models\AiChatMessage;
use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;

class AiChatController extends Controller
{
    # chat index
    public function index(Request $request)
    {
        $searchKey = null;
        $user = auth()->user();
        if ($user->user_type == "customer") {
            $package = $user->SubscriptionPackage;
            if ($package->allow_ai_chat == 0) {
                abort(403);
            }
        } else {
            if (!auth()->user()->can('ai_chat')) {
                abort(403);
            }
        }
        $chatExperts = AiChatCategory::oldest()->get();
        $chatListQuery = AiChat::orderBy('updated_at', 'DESC')->where('user_id', $user->id);

        if ($request->search != null) {
            $chatListQuery = $chatListQuery->where('title', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        if ($request->expert != null) {
            $chatList     = $chatListQuery->where('ai_chat_category_id', $request->expert)->get();
        } else {
            $chatList     = $chatListQuery->where('ai_chat_category_id', 1)->get();
        }

        $conversation = $chatListQuery->first();
        return view('backend.pages.aiChat.index', compact('chatExperts', 'chatList', 'conversation', 'searchKey'));
    }

    # Experts index
    public function indexExperts(Request $request)
    {
        $searchKey = null;
        $user = auth()->user();
        if ($user->user_type == "customer") {
            $package = $user->SubscriptionPackage;
            if ($package->allow_ai_chat == 0) {
                abort(403);
            }
        } else {
            if (!auth()->user()->can('ai_chat')) {
                abort(403);
            }
        }
        $chatExperts = AiChatCategory::oldest();

        if ($request->search != null) {
            $chatExperts = $chatExperts->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')->orWhere('description', 'like', '%' . $request->search . '%')->orWhere('role', 'like', '%' . $request->search . '%');
            });

            $searchKey = $request->search;
        }

        $chatExperts     = $chatExperts->get();
        return view('backend.pages.aiChat.experts', compact('chatExperts', 'searchKey'));
    }

    # new conversation
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->user_type == "customer") {
            $package = $user->SubscriptionPackage;
            if ($package->allow_ai_chat == 0) {
                $data = [
                    'status'  => 400,
                    'success' => false,
                    'message' => localize('AI Chat is not available in this package, please upgrade you plan'),
                ];
                return $data;
            }
        }
        $expert = AiChatCategory::whereId((int)$request->ai_chat_category_id)->first();

        $conversation                      = new AiChat;
        $conversation->user_id             = $user->id;
        $conversation->ai_chat_category_id = $request->ai_chat_category_id;
        $conversation->title               = $expert->name . ' Chat';
        $conversation->save();

        $message = new AiChatMessage;
        $message->ai_chat_id = $conversation->id;
        $message->user_id    = $user->id;
        if ($expert->role == 'default') {
            $result =  "Hello! I am $expert->name, and I'm here to answer your all questions.";
        } else {
            $result =  "Hello! I am $expert->name, and I'm $expert->role. $expert->assists_with.";
        }
        $message->response   = $result;
        $message->result   = $result;
        $message->save();

        $chatList = AiChat::latest();
        $chatList = $chatList->where('ai_chat_category_id', $expert->id)->where('user_id', $user->id)->get();

        $data = [
            'status'                 => 200,
            'chatList'               => view('backend.pages.aiChat.inc.chat-list', compact('chatList'))->render(),
            'messagesContainer'      => view('backend.pages.aiChat.inc.messages-container', compact('conversation'))->render(),
        ];
        return $data;
    }

    # update conversation
    public function update(Request $request)
    {
        $conversation = AiChat::whereId((int) $request->chatId)->first();
        $conversation->title = $request->value;
        $conversation->save();
    }

    # delete conversation
    public function delete($id)
    {
        $conversation = AiChat::findOrFail((int)$id);
        AiChatMessage::where('ai_chat_id', $conversation->id)->delete();
        $conversation->delete();
        flash(localize('Chat has been deleted successfully'))->success();
        return back();
    }

    # new message
    public function newMessage(Request $request)
    {
        $chat = AiChat::where('id', $request->chat_id)->first();
        $category = AiChatCategory::where('id', $request->category_id)->first();

        $user = auth()->user();

        // check word limit  
        if ($user->user_type == 'customer' && $user->this_month_available_words <= 0) {
            $data = [
                'status'  => 400,
                'success' => false,
                'message' => localize('Your word balance is low, please upgrade you plan'),
            ];
            return $data;
        }


        $prompt = $request->prompt;
        $total_used_tokens = 0;

        $message                = new AiChatMessage;
        $message->ai_chat_id    = $chat->id;
        $message->user_id       = $user->id;
        $message->prompt        = $prompt;
        $message->result        = $prompt;
        $message->save();

        $message->aiChat->touch(); // updated at

        $chat_id = $chat->id;
        $message_id = $message->id;

        return response()->json(compact('chat_id', 'message_id'));
    }

    # ai response
    public function process(Request $request)
    {
        $chat_id    = $request->chat_id;
        $message_id = $request->message_id;

        $message    = AiChatMessage::whereId((int)$message_id)->first();
        $prompt     = $message->prompt;

        $chat                   = AiChat::whereId((int) $chat_id)->first();
        $lastSixMessageQuery    = $chat->messages()->latest()->take(6);
        $lastSixMessage         = $lastSixMessageQuery->get()->reverse();

        $history[] = ["role" => "system", "content" => "You are a helpful assistant."];
        if (count($lastSixMessage) > 1) {
            foreach ($lastSixMessage as $sixMessage) {
                if ($sixMessage->prompt != null) {
                    $history[] = ["role" => "user", "content" => $sixMessage->prompt];
                } else {
                    $history[] = ["role" => "assistant", "content" => $sixMessage->response];
                }
            }
        } else {
            $history[] = ["role" => "user", "content" => $prompt];
        }

        return response()->stream(function () use ($prompt, $chat_id,  $message_id, $history) {

            $user = auth()->user();
            # 1. init openAi
            $open_ai = new OpenAi(config('services.open-ai.key'));

            $opts = [
                'model' => 'gpt-3.5-turbo',
                'messages' => $history,
                'temperature' => 1.0,
                'presence_penalty' => 0.6,
                'frequency_penalty' => 0,
                'stream' => true
            ];
            $text = "";
            $output = "";

            $open_ai->chat($opts, function ($curl_info, $data) use (&$text, $output, $user, $chat_id) {
                if ($obj = json_decode($data) and $obj->error->message != "") {
                    error_log(json_encode($obj->error->message));
                } else {
                    echo $data;

                    $clean = str_replace("data: ", "", $data);
                    $first = str_replace("}\n\n{", ",", $clean);

                    if (str_contains($first, 'assistant')) {
                        $raw = str_replace('"choices":[{"delta":{"role":"assistant"', '"random":[{"alpha":{"role":"assistant"', $first);
                        $response = json_decode($raw, true);
                    } else {
                        $response = json_decode($clean, true);
                    }

                    if ($data != "data: [DONE]\n\n" and isset($response["choices"][0]["delta"]["content"])) {
                        $text .= $response["choices"][0]["delta"]["content"];
                    }
                }

                echo PHP_EOL;
                ob_flush();
                flush();
                return strlen($data);
            });


            # Update credit balance
            $words = count(explode(' ', ($text)));
            $this->updateUserWords($words, $user);

            $messageFix = str_replace(["\r\n", "\r", "\n"], "<br/>", $text);
            $output     .= $messageFix;

            $message                = new AiChatMessage;
            $message->ai_chat_id    = $chat_id;
            $message->user_id       = $user->id;
            $message->response      = $text;
            $message->result        = $output;
            $message->words         = $words;
            $message->save();
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
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

    # get messages
    public function getMessages(Request $request)
    {
        $conversation = AiChat::whereId((int) $request->chatId)->first();
        if (is_null($conversation)) {
            $data = [
                'status'                 => 400
            ];
            return $data;
        }

        $data = [
            'status'                 => 200,
            'messagesContainer'      => view('backend.pages.aiChat.inc.messages-container', compact('conversation'))->render(),
        ];
        return $data;
    }

    # get conversations
    public function getConversations(Request $request)
    {
        $conversationsQuery = AiChat::where('ai_chat_category_id', (int) $request->ai_chat_category_id)->where('user_id', auth()->user()->id)->latest('updated_at');

        $chatList = $conversationsQuery->get();
        $conversation = $conversationsQuery->first();

        $data = [
            'status'                 => 200,
            'chatRight'      => view('backend.pages.aiChat.inc.chat-right', compact('conversation', 'chatList', 'conversation',))->render(),
        ];
        return $data;
    }
}
