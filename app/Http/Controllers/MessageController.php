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

    public function start(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => [
                'required',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ((int) $value === auth()->id()) {
                        $fail('Anda tidak dapat mengirim pesan ke diri sendiri.');
                    }
                },
            ],
            'job_id' => ['nullable', 'integer', 'exists:jobs,id'],
        ]);

        $sender = Auth::user();
        $recipient = \App\Models\User::findOrFail($validated['recipient_id']);

        // Hanya pasangan pencari kerja (umum) <-> perusahaan yang boleh chat.
        $allowedPair = ($sender->isUmum() && $recipient->isCompany())
            || ($sender->isCompany() && $recipient->isUmum());

        if (! $allowedPair) {
            return back()->with('error', 'Percakapan hanya tersedia antara pencari kerja dan perusahaan.');
        }

        $conversation = $this->findOrCreateConversation($sender, $recipient);

        return redirect()->route('messages.show', $conversation);
    }

    private function findOrCreateConversation($userA, $userB): Conversation
    {
        $existing = Conversation::whereHas('users', function ($query) use ($userA) {
                $query->where('user_id', $userA->id);
            })
            ->whereHas('users', function ($query) use ($userB) {
                $query->where('user_id', $userB->id);
            })
            ->first();

        if ($existing) {
            return $existing;
        }

        $conversation = Conversation::create();
        $conversation->users()->attach([$userA->id, $userB->id]);

        return $conversation;
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

    public function fetch(Conversation $conversation)
    {
        if (!$conversation->users->contains(Auth::id())) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name ?? 'Pengguna',
                    'created_at_formatted' => $message->created_at->format('d M Y, H:i'),
                ];
            }),
            'auth_id' => Auth::id()
        ]);
    }

    public function send(\App\Http\Requests\SendMessageRequest $request, Conversation $conversation, \App\Services\MessageService $messageService)
    {
        $message = $messageService->sendMessage($conversation, $request->validated());

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'body' => $message->body,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name ?? 'Pengguna',
                'created_at_formatted' => $message->created_at->format('d M Y, H:i'),
            ]
        ]);
    }
}
