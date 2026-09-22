<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Error') — {{ config('app.name', 'BKK SMK MUTU') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-neutral-50 text-neutral-900">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-16">
        <div class="w-full max-w-lg text-center">
            <div class="mb-6 flex justify-center">
                <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" class="w-14 h-14 rounded-xl object-cover ring-1 ring-slate-200">
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-600">
                    @yield('icon')
                </div>
                <h1 class="text-2xl font-bold text-slate-900">@yield('code')</h1>
                <p class="mt-2 text-sm font-semibold text-slate-700">@yield('title')</p>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">@yield('message')</p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Kembali</a>
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Beranda</a>
                </div>
            </div>
            <p class="mt-6 text-xs text-slate-400">© {{ date('Y') }} BKK SMK MUTU</p>
        </div>
    </div>
</body>
</html>
