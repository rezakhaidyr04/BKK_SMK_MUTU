<?php

namespace App\Repositories;

use App\Interfaces\ApplicationRepositoryInterface;
use App\Models\Application;

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
        return Application::create($applicationDetails);
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
