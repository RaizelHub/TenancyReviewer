<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class TenantDashboardController extends Controller
{
    /**
     * Show the tenant welcome page.
     */
    public function welcome()
    {
        return view('app.welcome');
    }

    /**
     * Show the teacher/tenant dashboard.
     */
    public function dashboard()
    {
        return view('app.dashboard');
    }
}
