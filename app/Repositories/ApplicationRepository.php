<?php

namespace App\Repositories;

use App\Interfaces\ApplicationRepositoryInterface;
use App\Models\Application;
use App\Notifications\ApplicationReceived;

class ApplicationRepository implements ApplicationRepositoryInterface 
{
    public function getApplicationById($applicationId) 
    {
        return Application::with(['job', 'user'])->findOrFail($applicationId);
    }

    public function deleteApplication($applicationId) 
    {
        return Application::destroy($applicationId);
    }

    public function createApplication(array $applicationDetails) 
    {
        $application = Application::create($applicationDetails);

        // Notify the company user that a new application was received
        $companyUser = optional($application->job->company ?? null)->user ?? null;
        if ($companyUser) {
            $companyUser->notify(new ApplicationReceived($application));
        }

        return $application;
    }

    public function updateApplicationStatus($applicationId, $newStatus) 
    {
        return Application::whereId($applicationId)->update(['status' => $newStatus]);
    }

    public function getApplicationsByUser($userId)
    {
        return Application::where('user_id', $userId)->with('job')->latest()->paginate(10);
    }

    public function getApplicationsByJob($jobId)
    {
        return Application::where('job_id', $jobId)->with('user')->latest()->paginate(10);
    }
}
