<x-app-layout :full-bleed="true">
    <x-ui.company-dashboard
        :company="$company"
        :stats="$stats"
        :recent-jobs="$recentJobs"
        :recent-applications="$recentApplications"
    />
</x-app-layout>
