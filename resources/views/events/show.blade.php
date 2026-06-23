<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="{{ route('events.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Kembali ke acara</a>

            <article class="mt-6 rounded-xl bg-white p-6 sm:p-8 shadow-lg border border-gray-100">
                <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700">
                    {{ \App\Support\Label::eventType($event->type) }}
                </span>
                <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $event->title }}</h1>

                @if($event->poster)
                    <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}" class="mt-6 w-full rounded-xl object-cover max-h-96">
                @endif

                <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Mulai</dt>
                        <dd class="mt-1 text-gray-900">{{ $event->start_time->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Selesai</dt>
                        <dd class="mt-1 text-gray-900">{{ $event->end_time ? $event->end_time->format('d M Y, H:i') : '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                        <dd class="mt-1 text-gray-900">{{ $event->location }}</dd>
                    </div>
                </dl>

                <div class="prose max-w-none mt-6 text-gray-700 whitespace-pre-line">{{ $event->description }}</div>
            </article>
        </div>
    </div>
</x-app-layout>
