<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Banner -->
            <div class="bg-primary rounded-xl shadow-soft-modern p-6 text-white flex justify-between items-center transform transition duration-300 hover:scale-[1.01]">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-blue-100">Jelajahi berbagai peluang karir masa depan Anda di BKK SMK MUTU KARAWANG.</p>
                </div>
                <div class="hidden md:block">
                    <!-- Placeholder Illustration -->
                    <svg class="w-24 h-24 text-blue-200 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>

            <!-- Dashboard Analytics Cards (Placeholder Structure) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-white overflow-hidden shadow-soft-modern rounded-xl p-6 border-l-4 border-primary">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">Total Lowongan</div>
                    <div class="text-3xl font-bold text-gray-900">124</div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white overflow-hidden shadow-soft-modern rounded-xl p-6 border-l-4 border-secondary">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">Lamaran Aktif</div>
                    <div class="text-3xl font-bold text-gray-900">12</div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white overflow-hidden shadow-soft-modern rounded-xl p-6 border-l-4 border-warning">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">Menunggu Interview</div>
                    <div class="text-3xl font-bold text-gray-900">3</div>
                </div>
                <!-- Card 4 -->
                <div class="bg-white overflow-hidden shadow-soft-modern rounded-xl p-6 border-l-4 border-danger">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">Pesan Masuk</div>
                    <div class="text-3xl font-bold text-gray-900">5</div>
                </div>
            </div>

            <!-- Recent Activity & Recommendations -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white shadow-soft-modern rounded-xl p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Rekomendasi Lowongan (Job Match)</h4>
                    <div class="space-y-4">
                        <!-- Empty State Example -->
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            <p>Lengkapi profil dan keahlian (Skill) Anda untuk mendapatkan rekomendasi lowongan yang sesuai!</p>
                            <button class="mt-4 px-4 py-2 bg-primary text-white rounded-xl hover:bg-blue-700 transition">Lengkapi Profil</button>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow-soft-modern rounded-xl p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Aktivitas Terakhir</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3 text-sm">
                            <span class="flex-shrink-0 w-2 h-2 mt-1.5 rounded-full bg-secondary"></span>
                            <p class="text-gray-600">Melamar posisi <span class="font-semibold text-gray-800">Frontend Developer</span> di PT Teknologi Bangsa</p>
                        </li>
                        <li class="flex items-start space-x-3 text-sm">
                            <span class="flex-shrink-0 w-2 h-2 mt-1.5 rounded-full bg-warning"></span>
                            <p class="text-gray-600">Memperbarui CV ATS Friendly</p>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
