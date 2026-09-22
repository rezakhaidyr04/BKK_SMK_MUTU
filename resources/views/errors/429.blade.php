@extends('errors.layout')
@section('code', '429')
@section('title', 'Terlalu Banyak Permintaan')
@section('message', 'Anda mengirim terlalu banyak permintaan. Silakan tunggu beberapa saat dan coba lagi.')
@section('icon')
<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
@endsection
