<?php

return [

    /*
    |--------------------------------------------------------------------------
    | A/B Testing Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk A/B testing copy CTA dan elemen UI lainnya.
    | Variasi dipilih berdasarkan session/user ID untuk konsistensi.
    |
    */

    'enabled' => env('AB_TESTING_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | CTA Copy Variations
    |--------------------------------------------------------------------------
    */
    'cta_copy' => [
        'hero_primary' => [
            'variations' => [
                'control' => [
                    'label' => 'Mulai Gratis',
                    'description' => 'Versi asli - fokus pada gratis',
                    'weight' => 50,
                ],
                'variant_a' => [
                    'label' => 'Cari Lowongan Sekarang',
                    'description' => 'Fokus pada action langsung',
                    'weight' => 25,
                ],
                'variant_b' => [
                    'label' => 'Daftar & Temukan Karir',
                    'description' => 'Kombinasi daftar + benefit',
                    'weight' => 25,
                ],
            ],
            'default' => 'control',
        ],
        'hero_secondary' => [
            'variations' => [
                'control' => [
                    'label' => 'Lihat Lowongan',
                    'description' => 'Versi asli',
                    'weight' => 50,
                ],
                'variant_a' => [
                    'label' => 'Jelajahi Pekerjaan',
                    'description' => 'Lebih inviting',
                    'weight' => 25,
                ],
                'variant_b' => [
                    'label' => 'Lowongan Terbaru',
                    'description' => 'Fokus kebaruannya',
                    'weight' => 25,
                ],
            ],
            'default' => 'control',
        ],
        'cta_banner' => [
            'variations' => [
                'control' => [
                    'guest' => 'Daftar Sekarang Gratis',
                    'auth' => 'Lihat Lowongan',
                    'description' => 'Versi asli',
                    'weight' => 50,
                ],
                'variant_a' => [
                    'guest' => 'Mulai Karir Impianmu',
                    'auth' => 'Temukan Pekerjaan Baru',
                    'description' => 'Benefit-driven',
                    'weight' => 25,
                ],
                'variant_b' => [
                    'guest' => 'Bergabung 2500+ Pencari Kerja',
                    'auth' => 'Lihat 320+ Lowongan Aktif',
                    'description' => 'Social proof',
                    'weight' => 25,
                ],
            ],
            'default' => 'control',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Hero Heading Variations
    |--------------------------------------------------------------------------
    */
    'hero_heading' => [
        'variations' => [
            'control' => [
                'text' => 'Temukan Karier Impian Anda<br>Bersama <span class="hero-heading-accent">BKK SMK MUTU</span>',
                'description' => 'Versi asli',
                'weight' => 50,
            ],
            'variant_a' => [
                'text' => 'Karier Impian Dimulai<br>Dari <span class="hero-heading-accent">Sini</span>',
                'description' => 'Lebih singkat, action-oriented',
                'weight' => 25,
            ],
            'variant_b' => [
                'text' => 'Ribuan Alumni Sukses<br>Melalui <span class="hero-heading-accent">BKK SMK MUTU</span>',
                'description' => 'Social proof di heading',
                'weight' => 25,
            ],
        ],
        'default' => 'control',
    ],

    /*
    |--------------------------------------------------------------------------
    | Assignment Strategy
    |--------------------------------------------------------------------------
    | 'session' - Tetap sama selama session (default)
    | 'user'    - Tetap sama per user (perlu login)
    | 'random'  - Random setiap request (tidak direkomendasikan)
    */
    'assignment_strategy' => env('AB_ASSIGNMENT_STRATEGY', 'session'),

    /*
    |--------------------------------------------------------------------------
    | Tracking Events
    |--------------------------------------------------------------------------
    | Event names untuk tracking konversi
    */
    'events' => [
        'hero_cta_click' => 'ab_test.hero_cta_click',
        'hero_secondary_click' => 'ab_test.hero_secondary_click',
        'cta_banner_click' => 'ab_test.cta_banner_click',
        'registration' => 'ab_test.registration',
        'job_application' => 'ab_test.job_application',
    ],

];