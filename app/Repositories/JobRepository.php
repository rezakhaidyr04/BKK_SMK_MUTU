<?php

namespace App\Repositories;

use App\Interfaces\JobRepositoryInterface;
use App\Models\Job;

class JobRepository implements JobRepositoryInterface 
{
    public function getAllActiveJobs($search = null) 
    {
        $query = Job::where('status', 'active');
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('position', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
        }

        return $query->latest()->paginate(9);
    }

    public function getJobById($jobId) 
    {
        return Job::with(['applications'])->findOrFail($jobId);
    }

    public function deleteJob($jobId) 
    {
        return Job::destroy($jobId);
    }

    public function createJob(array $jobDetails) 
    {
        return Job::create($jobDetails);
    }

    public function updateJob($jobId, array $newDetails) 
    {
        return Job::whereId($jobId)->update($newDetails);
    }

    public function getJobsByCompany($companyId)
    {
        // This method is no longer needed as companies are removed
        return collect();
    }
}
