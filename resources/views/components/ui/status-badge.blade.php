@props(['status'])

@php
$config = match($status) {
    'submitted' => ['class' => 'ui-badge-blue', 'label' => 'Terkirim'],
    'under_review' => ['class' => 'ui-badge-yellow', 'label' => 'Ditinjau'],
    'interviewed' => ['class' => 'ui-badge-purple', 'label' => 'Wawancara'],
    'accepted' => ['class' => 'ui-badge-green', 'label' => 'Diterima'],
    'rejected' => ['class' => 'ui-badge-red', 'label' => 'Ditolak'],
    'active' => ['class' => 'ui-badge-green', 'label' => 'Aktif'],
    'inactive' => ['class' => 'ui-badge-gray', 'label' => 'Nonaktif'],
    'pending' => ['class' => 'ui-badge-yellow', 'label' => 'Menunggu'],
    'verified' => ['class' => 'ui-badge-green', 'label' => 'Terverifikasi'],
    'published' => ['class' => 'ui-badge-green', 'label' => 'Dipublish'],
    'draft' => ['class' => 'ui-badge-yellow', 'label' => 'Draft'],
    'closed' => ['class' => 'ui-badge-red', 'label' => 'Ditutup'],
    default => ['class' => 'ui-badge-gray', 'label' => ucfirst(str_replace('_', ' ', $status))],
};
@endphp

<span {{ $attributes->merge(['class' => 'ui-badge ' . $config['class']]) }}>
    {{ $slot->isEmpty() ? $config['label'] : $slot }}
</span>
