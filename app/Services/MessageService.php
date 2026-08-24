<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class MessageService
{
    public function sendMessage(Conversation $conversation, array $validatedData)
    {
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $validatedData['body'],
        ]);

        $message->load('sender');

        try {
            Log::info('Dispatching MessageSent event', ['message_id' => $message->id]);
            Event::dispatch(new \App\Events\MessageSent($message));
        } catch (\Exception $e) {
            Log::error('Failed to dispatch MessageSent event: ' . $e->getMessage());
        }

        return $message;
    }
}
