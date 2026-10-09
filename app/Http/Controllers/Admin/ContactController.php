<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::with('user')
            ->latest()
            ->paginate(15);

        $unreadCount = ContactMessage::baru()->count();

        return view('admin.contacts.index', compact('messages', 'unreadCount'));
    }

    public function show(ContactMessage $contact)
    {
        // Tandai dibaca saat pertama dibuka admin.
        if ($contact->isBaru()) {
            $contact->update(['status' => 'dibaca']);
        }

        return view('admin.contacts.show', ['message' => $contact->load('user')]);
    }

    public function markReplied(ContactMessage $contact)
    {
        $contact->update(['status' => 'dibalas', 'replied_at' => now()]);

        return back()->with('success', 'Pesan ditandai sudah dibalas.');
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Pesan kontak dihapus.');
    }
}
