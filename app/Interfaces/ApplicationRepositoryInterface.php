<?php

namespace App\Interfaces;

interface ApplicationRepositoryInterface
{
    public function getApplicationById($applicationId);
    public function deleteApplication($applicationId);
    public function createApplication(array $applicationDetails);
    public function updateApplicationStatus($applicationId, $newStatus);
    public function getApplicationsByUser($userId);
    public function getApplicationsByJob($jobId);
}
