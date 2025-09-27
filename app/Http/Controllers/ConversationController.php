<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function store(Request $request) {

        $validated = $request->validate(['title' => 'required|string|max:100']);
        $conversation = auth()->user()->conversations()->create([
           'title' => $validated['title'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $conversation
        ], 201);
    }

    public function show(Conversation $conversation) {

        if (auth()->id() !== $conversation->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $conversation->load('messages');

        return response()->json([
            'success' => true,
            'data' => $conversation
        ]);
    }

    public function destroy (Conversation $conversation){
        if (auth()->id() !== $conversation->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $conversation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Percakapan Ini Sudah Dihapus'
        ], 200);
    }

    public function index () {
        $conversations = auth()->user()->conversations()->withCount('messages')->latest()->paginate(50);

        return response()->json([
            'success' => true,
            'data'=> $conversations
        ]);
    }

    public function clearAll()
    {
        auth()->user()->conversations()->delete();
        return response()->json(['message' => 'All conversations cleared.']);
    }
}
