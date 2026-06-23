<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BKK SMK MUTU') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('css/tailwind-local.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-white/40 backdrop-blur-3xl"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(147, 51, 234, 0.1) 0%, transparent 50%);"></div>
        
        <div class="min-h-screen flex">
            <!-- Left Side - Branding -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-blue-900/20"></div>
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);"></div>
                
                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-center px-12">
                    <div class="mb-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 rounded-2xl overflow-hidden border border-white/20">
                                <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" class="w-16 h-16 object-cover">
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">BKK SMK MUTU</h1>
                                <p class="text-blue-100">Pusat Pengembangan Karir</p>
                            </div>
                        </div>
                        
                        <h2 class="text-4xl font-bold text-white mb-4 leading-tight">
                            Menghubungkan Talenta<br>
                            dengan Peluang
                        </h2>
                        <p class="text-xl text-blue-100 leading-relaxed">
                            Bergabunglah dengan ribuan siswa dan alumni yang menemukan karir impian melalui platform kami.
                        </p>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">500+</div>
                            <div class="text-blue-200 text-sm">Siswa Ditempatkan</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">150+</div>
                            <div class="text-blue-200 text-sm">Perusahaan Mitra</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">95%</div>
                            <div class="text-blue-200 text-sm">Tingkat Keberhasilan</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
                <div class="w-full max-w-md relative">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-8">
                            <div class="inline-flex items-center gap-3 mb-4">
                            <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" class="w-12 h-12 rounded-xl object-cover">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">BKK SMK MUTU</h1>
                            </div>
                        </div>
                    </div>

                    <!-- Form Card -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl p-8 border border-white/20">
                        {{ $slot }}
                    </div>

                    <!-- Back to Home -->
                    <div class="text-center mt-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>