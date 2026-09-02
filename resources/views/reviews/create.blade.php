@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Bagikan Pengalaman Anda</h1>
            <p class="text-xl text-gray-600">Ulasan Anda membantu kami terus meningkatkan layanan untuk pengguna lain</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Review Form -->
        <form action="{{ route('reviews.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            @csrf

            <!-- Rating -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-3">Rating Anda</label>
                <div class="flex gap-2" id="rating-container">
                    @for($i = 1; $i <= 5; $i++)
                        <input type="radio" name="rating" value="{{ $i }}" id="rating_{{ $i }}" 
                            class="hidden" 
                            required
                            @if(old('rating') == $i) checked @endif>
                        <label for="rating_{{ $i }}" class="cursor-pointer transition-all duration-200 transform hover:scale-110 rating-star" data-rating="{{ $i }}" style="display: inline-block;">
                            <svg class="w-12 h-12 transition-all duration-200" style="fill: #d1d5db;" 
                                viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </label>
                    @endfor
                </div>
                @error('rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Comment -->
            <div class="mb-6">
                <label for="comment" class="block text-sm font-semibold text-gray-900 mb-2">Ulasan Anda</label>
                <textarea name="comment" id="comment" rows="5" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('comment') border-red-500 @enderror"
                    placeholder="Bagikan pengalaman Anda menggunakan BKK SMK MUTU..."
                    required>{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">Minimal 10 karakter, maksimal 1000 karakter</p>
            </div>

            <!-- Job Details (Optional) -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="job_title" class="block text-sm font-semibold text-gray-900 mb-2">Posisi Pekerjaan</label>
                    <input type="text" name="job_title" id="job_title" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Contoh: Web Developer"
                        value="{{ old('job_title') }}">
                    @error('job_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="company_name" class="block text-sm font-semibold text-gray-900 mb-2">Nama Perusahaan</label>
                    <input type="text" name="company_name" id="company_name" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Contoh: PT Teknologi Nusantara"
                        value="{{ old('company_name') }}">
                    @error('company_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact Details (Optional) -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Detail Kontak (Opsional)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                        <input type="text" name="name" id="name" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nama Anda"
                            value="{{ old('name') ?? auth()->user()->name ?? '' }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="email@example.com"
                            value="{{ old('email') ?? auth()->user()->email ?? '' }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                        <input type="tel" name="phone" id="phone" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="08xxxxxxxxxx"
                            value="{{ old('phone') ?? auth()->user()->phone ?? '' }}">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Privacy Notice -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <strong>Privasi Anda:</strong> Ulasan Anda akan ditinjau terlebih dahulu sebelum ditampilkan. Nama Anda akan ditampilkan bersama ulasan kecuali Anda ingin tetap anonim.
                </p>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    Kirim Ulasan
                </button>
                <a href="{{ route('home') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>

        <!-- Stats Preview -->
        <div class="mt-12 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Statistik Pengguna BKK SMK MUTU</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6">
                    <div class="text-4xl font-bold text-yellow-600 mb-2">{{ number_format(\App\Models\Review::getAverageRating(), 1) }}</div>
                    <p class="text-gray-700">Rating Rata-rata</p>
                    <div class="flex justify-center gap-1 mt-3">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= \App\Models\Review::getAverageRating() ? 'fill-yellow-400' : 'fill-gray-300' }}" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ \App\Models\Review::getTotalReviews() }}+</div>
                    <p class="text-gray-700">Ulasan Positif</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ \App\Models\Review::getSatisfactionPercentage() }}%</div>
                    <p class="text-gray-700">Pengguna Puas</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingStars = document.querySelectorAll('.rating-star');
    
    function updateStars(rating) {
        ratingStars.forEach((star, index) => {
            const starRating = index + 1;
            const svg = star.querySelector('svg');
            if (starRating <= rating) {
                svg.style.fill = '#fbbf24'; // yellow-400
            } else {
                svg.style.fill = '#d1d5db'; // gray-300
            }
        });
    }
    
    // Set initial state from checked input
    const checkedInput = document.querySelector('input[name="rating"]:checked');
    if (checkedInput) {
        updateStars(checkedInput.value);
    }
    
    // Add click handlers
    ratingStars.forEach((star) => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            const input = document.getElementById('rating_' + rating);
            input.checked = true;
            updateStars(rating);
        });
        
        star.addEventListener('mouseenter', function() {
            const rating = this.dataset.rating;
            updateStars(rating);
        });
    });
    
    // Reset on mouse leave
    const container = document.getElementById('rating-container');
    container.addEventListener('mouseleave', function() {
        const checkedInput = document.querySelector('input[name="rating"]:checked');
        if (checkedInput) {
            updateStars(checkedInput.value);
        } else {
            updateStars(0);
        }
    });
});
</script>
@endsection
