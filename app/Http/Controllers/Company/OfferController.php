<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Mail\OfferLetter;
use Illuminate\Support\Facades\Mail;

class OfferController extends Controller
{
    public function send(Application $application)
    {
        // authorize: company owning the job
        if (!Auth::user()->company || Auth::user()->company->id !== $application->job->company_id) {
            abort(403);
        }

        // Send offer mail, but do not fail the action if mail transport is unavailable.
        try {
            Mail::to($application->user->email)->queue(new OfferLetter($application));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('company.applicants.index')->with('success', 'Offer letter dikirim ke pelamar.');
    }
}
