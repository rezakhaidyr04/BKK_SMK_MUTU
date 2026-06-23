<?php

namespace App\Repositories;

use App\Interfaces\JobRepositoryInterface;
use App\Models\Job;

class JobRepository implements JobRepositoryInterface 
{
    public function getAllActiveJobs($search = null) 
    {
        $query = Job::where('status', 'active')->with('company');
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('position', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
        }

        return $query->latest()->paginate(9);
    }

    public function getJobById($jobId) 
    {
        return Job::with(['company', 'applications'])->findOrFail($jobId);
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
        return Job::where('company_id', $companyId)->latest()->paginate(10);
    }
}
