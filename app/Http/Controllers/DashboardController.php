<?php

namespace App\Http\Controllers;

use App\Queries\AdminDashboardQuery;
use App\Queries\CompanyDashboardQuery;
use App\Queries\UmumDashboardQuery;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case "admin":
                return $this->adminDashboard();
            case "umum":
                return $this->umumDashboard();
            case "company":
                return $this->companyDashboard();
            default:
                abort(403, 'Unauthorized access.');
        }
    }

    private function adminDashboard()
    {
        return view("dashboard.admin", app(AdminDashboardQuery::class)->get());
    }

    private function umumDashboard()
    {
        return view("dashboard.umum", app(UmumDashboardQuery::class)->get(Auth::user()));
    }

    private function companyDashboard()
    {
        return view("dashboard.company", app(CompanyDashboardQuery::class)->get(Auth::user()));
    }
}
