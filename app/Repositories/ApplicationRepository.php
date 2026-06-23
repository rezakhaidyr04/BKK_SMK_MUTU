<?php

namespace App\Repositories;

use App\Interfaces\ApplicationRepositoryInterface;
use App\Models\Application;

class ApplicationRepository implements ApplicationRepositoryInterface 
{
    public function getApplicationById($applicationId) 
    {
        return Application::with(['job.company', 'user'])->findOrFail($applicationId);
    }

    public function deleteApplication($applicationId) 
    {
        return Application::destroy($applicationId);
    }

    public function createApplication(array $applicationDetails) 
    {
        $application = Application::create($applicationDetails);

        // Dispatch notification to the company owner if possible
        try {
            $application->load('job.company.user', 'user');
            $companyUser = $application->job->company->user ?? null;
            if ($companyUser) {
                $companyUser->notify(new \App\Notifications\ApplicationReceived($application));
            }
        } catch (\Throwable $e) {
            // fail silently; do not block creation
        }

        return $application;
    }

    public function updateApplicationStatus($applicationId, $newStatus) 
    {
        return Application::whereId($applicationId)->update(['status' => $newStatus]);
    }

    public function getApplicationsByUser($userId)
    {
        return Application::where('user_id', $userId)->with('job.company')->latest()->paginate(10);
    }

    public function getApplicationsByJob($jobId)
    {
        return Application::where('job_id', $jobId)->with('user')->latest()->paginate(10);
    }
}
