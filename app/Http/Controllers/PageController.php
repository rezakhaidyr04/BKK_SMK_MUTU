<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    /**
     * Slug halaman statis yang diizinkan. Kunci = URL, nilai = view + meta.
     * Kontak (/kontak) punya controller sendiri karena ada form.
     */
    public const PAGES = [
        'tentang' => [
            'view' => 'pages.tentang',
            'title' => 'Tentang BKKMu',
            'description' => 'Mengenal Bursa Kerja Khusus SMK TI Muhammadiyah Cikampek dan misi platform BKKMu.',
        ],
        'faq' => [
            'view' => 'pages.faq',
            'title' => 'Pertanyaan Umum (FAQ)',
            'description' => 'Jawaban atas pertanyaan umum seputar akun, lamaran, CV, dan verifikasi perusahaan.',
        ],
        'privasi' => [
            'view' => 'pages.privasi',
            'title' => 'Kebijakan Privasi',
            'description' => 'Bagaimana BKKMu mengumpulkan, menggunakan, dan melindungi data pribadi Anda.',
        ],
        'syarat-ketentuan' => [
            'view' => 'pages.syarat-ketentuan',
            'title' => 'Syarat & Ketentuan',
            'description' => 'Aturan penggunaan platform BKKMu untuk pencari kerja dan perusahaan.',
        ],
    ];

    public function show(string $slug)
    {
        $page = self::PAGES[$slug] ?? abort(404);

        return view($page['view'], [
            'seoTitle' => $page['title'].' — BKKMu',
            'seoDescription' => $page['description'],
        ]);
    }
}
