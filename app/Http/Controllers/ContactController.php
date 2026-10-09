<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    /**
     * Form kontak publik + info kontak BKK.
     */
    public function index()
    {
        return view('contact.index', [
            'seoTitle' => 'Kontak — BKKMu',
            'seoDescription' => 'Hubungi tim BKK SMK MUTU: pertanyaan akun, kemitraan perusahaan, atau laporan lowongan mencurigakan.',
        ]);
    }

    /**
     * Publik, di-throttle anti spam. user_id opsional dari auth.
     */
    public function store(StoreContactRequest $request)
    {
        ContactMessage::create([
            'user_id' => auth()->id(),
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'subject' => $request->validated('subject'),
            'message' => $request->validated('message'),
            'status' => 'baru',
        ]);

        return back()->with('success', 'Pesan terkirim! Tim BKK akan membalas via email maksimal 2 hari kerja.');
    }
}
