<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DebugController extends Controller
{
    public function statusPlayground(): View
    {
        $statuses = [
            'not_submitted', 'submitted', 'under_review', 'interviewed',
            'accepted', 'rejected', 'pending', 'verified', 'draft', 'closed',
            'unknown_status',
        ];

        return view('debug.status-playground', compact('statuses'));
    }
}
