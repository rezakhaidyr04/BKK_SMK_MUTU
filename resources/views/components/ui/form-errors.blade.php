@props(['errors'])

@if($errors->any())
<x-ui.alert type="error">
    <div>
        <p class="font-semibold mb-1">Terjadi kesalahan:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</x-ui.alert>
@endif
