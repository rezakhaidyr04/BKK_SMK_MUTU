<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = Conversation::whereHas('users', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['users', 'messages' => function($query) {
            $query->latest()->limit(1);
        }])
        ->get();

        return view('messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        // Check authorization
        if (!$conversation->users->contains(Auth::id())) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', compact('conversation', 'messages'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // Check authorization
        if (!$conversation->users->contains(Auth::id())) {
            abort(403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $request->body,
        ]);

        // Broadcast the message so realtime clients can receive it
        try {
            Log::info('Dispatching MessageSent event', ['message_id' => $message->id]);
            Event::dispatch(new \App\Events\MessageSent($message));
            Log::info('MessageSent event dispatched', ['message_id' => $message->id]);
        } catch (\Throwable $e) {
            Log::error('MessageSent dispatch failed', ['message_id' => $message->id, 'error' => $e->getMessage()]);
            // ignore broadcast failures; message still persisted
        }

        return back()->with('success', 'Pesan berhasil dikirim.');
    }
}
