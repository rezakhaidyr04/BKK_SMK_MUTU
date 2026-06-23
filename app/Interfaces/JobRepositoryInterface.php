<?php

namespace App\Interfaces;

interface JobRepositoryInterface
{
    public function getAllActiveJobs($search = null);
    public function getJobById($jobId);
    public function deleteJob($jobId);
    public function createJob(array $jobDetails);
    public function updateJob($jobId, array $newDetails);
    public function getJobsByCompany($companyId);
}
