<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Join BKK SMK MUTU</h2>
        <p class="text-gray-600">Start your career journey today</p>
    </div>

    @php $selectedRole = old('role', 'student'); @endphp
    <form method="POST" action="{{ route('register') }}" class="space-y-6" x-data="{ role: '{{ $selectedRole }}' }">
        @csrf

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <div class="font-semibold mb-2">Terjadi kesalahan saat mendaftar:</div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Role Selection -->
        <div>
            <x-input-label for="role" :value="__('Account Type')" class="text-gray-700 font-medium mb-3" />
            <div class="grid grid-cols-2 gap-4">
                <label class="relative">
                    <input type="radio" name="role" value="student" x-model="role" class="sr-only" {{ $selectedRole === 'student' ? 'checked' : '' }}>
                    <div class="p-4 rounded-xl border-2 cursor-pointer transition-all" 
                         :class="role === 'student' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Student/Alumni</div>
                                <div class="text-xs text-gray-600">Find jobs & build career</div>
                            </div>
                        </div>
                    </div>
                </label>

                <label class="relative">
                    <input type="radio" name="role" value="company" x-model="role" class="sr-only" {{ $selectedRole === 'company' ? 'checked' : '' }}>
                    <div class="p-4 rounded-xl border-2 cursor-pointer transition-all" 
                         :class="role === 'company' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300'">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Company</div>
                                <div class="text-xs text-gray-600">Post jobs & hire talent</div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" x-text="role === 'company' ? 'Company Name' : 'Full Name'" class="text-gray-700 font-medium mb-2" />
            <div class="relative">
                <x-text-input id="name" class="form-input pl-12" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" x-bind:placeholder="role === 'company' ? 'Enter company name' : 'Enter your full name'" />
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="role === 'student'">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="role === 'company'">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium mb-2" />
            <div class="relative">
                <x-text-input id="email" class="form-input pl-12" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter email address" />
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Student Specific Fields -->
        <div x-show="role === 'student'" x-transition class="space-y-6">
            <!-- NIS -->
            <div>
                <x-input-label for="nis" :value="__('NIS (Student ID)')" class="text-gray-700 font-medium mb-2" />
                <div class="relative">
                    <x-text-input id="nis" class="form-input pl-12" type="text" name="nis" :value="old('nis')" placeholder="Enter your NIS" />
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                        </svg>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('nis')" class="mt-2" />
            </div>

            <!-- Graduation Year -->
            <div>
                <x-input-label for="graduation_year" :value="__('Graduation Year')" class="text-gray-700 font-medium mb-2" />
                <div class="relative">
                    <select id="graduation_year" name="graduation_year" class="form-select pl-12">
                        <option value="">Select graduation year</option>
                        @for($year = date('Y') + 2; $year >= date('Y') - 10; $year--)
                            <option value="{{ $year }}" {{ old('graduation_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('graduation_year')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium mb-2" />
            <div class="relative">
                <x-text-input id="password" class="form-input pl-12" type="password" name="password" required autocomplete="new-password" placeholder="Create a strong password" />
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-medium mb-2" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="form-input pl-12" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms -->
        <div>
            <label class="flex items-start gap-3">
                <input type="checkbox" class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-0" name="terms" required>
                <span class="text-sm text-gray-600">
                    I agree to the <a href="{{ route('home') }}#terms" class="text-blue-600 hover:text-blue-800 font-medium">Terms of Service</a> 
                    and <a href="{{ route('home') }}#privacy" class="text-blue-600 hover:text-blue-800 font-medium">Privacy Policy</a>
                </span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="space-y-4">
            <button type="submit" class="w-full btn-primary py-4 text-base">
                <span class="flex items-center justify-center gap-2">
                    Create Account
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </span>
            </button>
        </div>
    </form>

    <!-- Login Link -->
    <div class="mt-8 text-center">
        <p class="text-gray-600">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-800 transition-colors">
                Sign in here
            </a>
        </p>
    </div>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-guest-layout>