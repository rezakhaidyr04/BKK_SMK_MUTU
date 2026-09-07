<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets;

    public int $conversationId;
    public array $payload;

    public function __construct(Message $message)
    {
        $this->conversationId = $message->conversation_id;
        $this->payload = [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->sender->name ?? 'Pengguna',
            'body' => $message->body,
            'created_at_formatted' => $message->created_at->format('d M Y, H:i'),
        ];
    }

    public function broadcastOn()
    {
        return new Channel('conversation.' . $this->conversationId);
    }

    public function broadcastWith()
    {
        return $this->payload;
    }
}
