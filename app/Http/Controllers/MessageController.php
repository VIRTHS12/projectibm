<?php

namespace App\Http\Controllers;

use App\Enums\EnumsUserMessages;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\ReplicateServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MessageController extends Controller
{
    public function index(Conversation $conversation)
    {
        if (auth()->id() !== $conversation->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()->latest()->paginate(20);
        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function store(Request $request, ReplicateServices $replicateService)
    {
        $validate = $request->validate([
            'conversation_id' => [
                'required',
                Rule::exists('conversations', 'id')->where('user_id', auth()->id())
            ],
            'content' => 'required|string|max:2000',
        ]);

        $userMessage = Message::create([
            'conversation_id' => $validate['conversation_id'],
            'content' => $validate['content'],
            'sender' => EnumsUserMessages::USER
        ]);

        $botmessage = null;

        try {

            $botreply = $replicateService->getReply($userMessage->content);
            $botMessage = Message::create([
                'conversation_id' => $userMessage->conversation_id,
                'content' => $botreply,
                'sender' => EnumsUserMessages::AI
            ]);

            return response()->json([
                'success' => true,
                'message' => 'pesan dikirim',
                'data' => [
                    'user_message' => $userMessage,
                    'bot_message' => $botMessage
                ],
            ], 201);
        } catch (Exception $e) {
            $botMessage = Message::create([
                'conversation_id' => $userMessage->conversation_id,
                'content' => 'Bot sedang sibuk silahkan coba lagi nanti',
                'sender' => EnumsUserMessages::AI
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => ['bot_message' => $botMessage]
            ], 500);
        }
    }
}
