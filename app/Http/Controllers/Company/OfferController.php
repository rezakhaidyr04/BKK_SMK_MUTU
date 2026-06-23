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

        // send offer mail (queued)
        Mail::to($application->user->email)->queue(new OfferLetter($application));

        return redirect()->route('company.applicants.index')->with('success', 'Offer letter dikirim ke pelamar.');
    }
}
