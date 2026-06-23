<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembuat CV</h1>
            <p class="text-gray-600 mb-8">Buat CV yang ramah ATS dalam hitungan menit</p>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-xl font-bold mb-6">Pilih Template</h2>
                    
                    <form action="{{ route('cv.generate') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <label class="cursor-pointer">
                                <input type="radio" name="template" value="modern" class="peer hidden" checked>
                                <div class="border-2 border-gray-200 peer-checked:border-blue-600 rounded-lg p-4 text-center hover:border-blue-300 transition">
                                    <div class="w-full h-32 bg-blue-50 rounded mb-2"></div>
                                    <p class="font-medium">Modern</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="template" value="classic" class="peer hidden">
                                <div class="border-2 border-gray-200 peer-checked:border-blue-600 rounded-lg p-4 text-center hover:border-blue-300 transition">
                                    <div class="w-full h-32 bg-gray-50 rounded mb-2"></div>
                                    <p class="font-medium">Klasik</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="template" value="professional" class="peer hidden">
                                <div class="border-2 border-gray-200 peer-checked:border-blue-600 rounded-lg p-4 text-center hover:border-blue-300 transition">
                                    <div class="w-full h-32 bg-indigo-50 rounded mb-2"></div>
                                    <p class="font-medium">Profesional</p>
                                </div>
                            </label>
                        </div>
                        
                        <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                            Buat CV
                        </button>
                    </form>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-4">CV Anda</h3>
                    @if($cvFiles->count() > 0)
                        @foreach($cvFiles as $cv)
                        <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium">CV {{ $cv->created_at->format('d M Y') }}</p>
                            <a href="{{ route('cv.download', $cv->id) }}" class="text-blue-600 text-sm">Unduh</a>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-600 text-sm">Belum ada CV</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
