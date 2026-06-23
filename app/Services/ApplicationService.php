<?php

namespace App\Services;

use App\Interfaces\ApplicationRepositoryInterface;
use App\Interfaces\JobRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationService
{
    protected $applicationRepository;
    protected $jobRepository;

    public function __construct(
        ApplicationRepositoryInterface $applicationRepository,
        JobRepositoryInterface $jobRepository
    ) {
        $this->applicationRepository = $applicationRepository;
        $this->jobRepository = $jobRepository;
    }

    public function applyForJob($userId, array $applicationData)
    {
        // Check if job exists and is active
        $job = $this->jobRepository->getJobById($applicationData['job_id']);
        if ($job->status !== 'active') {
            throw new \Exception('Job is no longer active.');
        }

        DB::beginTransaction();

        try {
            $applicationData['user_id'] = $userId;
            $applicationData['status'] = 'submitted';
            
            $application = $this->applicationRepository->createApplication($applicationData);
            
            DB::commit();

            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating application: ' . $e->getMessage());
            throw new \Exception('Failed to submit application');
        }
    }

    public function updateApplicationStatus($applicationId, $newStatus)
    {
        DB::beginTransaction();

        try {
            $this->applicationRepository->updateApplicationStatus($applicationId, $newStatus);
            $application = $this->applicationRepository->getApplicationById($applicationId);
            DB::commit();

            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating application status: ' . $e->getMessage());
            throw new \Exception('Failed to update application status');
        }
    }

    public function getUserApplications($userId)
    {
        return $this->applicationRepository->getApplicationsByUser($userId);
    }
}
