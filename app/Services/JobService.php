<?php

namespace App\Services;

use App\Interfaces\JobRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobService
{
    protected $jobRepository;

    public function __construct(JobRepositoryInterface $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function getAllActiveJobs($search = null)
    {
        return $this->jobRepository->getAllActiveJobs($search);
    }

    public function getJobById($jobId)
    {
        return $this->jobRepository->getJobById($jobId);
    }

    public function createJob(array $jobData)
    {
        DB::beginTransaction();

        try {
            $job = $this->jobRepository->createJob($jobData);
            DB::commit();

            return $job;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating job: ' . $e->getMessage());
            throw new \Exception('Failed to create job');
        }
    }

    public function updateJob($jobId, array $jobData)
    {
        DB::beginTransaction();

        try {
            $this->jobRepository->updateJob($jobId, $jobData);
            $job = $this->jobRepository->getJobById($jobId);
            DB::commit();

            return $job;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating job: ' . $e->getMessage());
            throw new \Exception('Failed to update job');
        }
    }

    public function deleteJob($jobId)
    {
        DB::beginTransaction();

        try {
            $this->jobRepository->deleteJob($jobId);
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting job: ' . $e->getMessage());
            throw new \Exception('Failed to delete job');
        }
    }
}
