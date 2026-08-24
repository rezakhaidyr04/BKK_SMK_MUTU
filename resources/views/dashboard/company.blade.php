<x-app-layout :full-bleed="true">
    <x-slot name="header">
        <x-ui.page-header title="Dasbor Perusahaan" subtitle="Pantau lowongan dan aktivitas pelamar perusahaan Anda." />
    </x-slot>
    <x-ui.company-dashboard
        :company="$company"
        :stats="$stats"
        :recent-jobs="$recentJobs"
        :recent-applications="$recentApplications"
    />
</x-app-layout>
