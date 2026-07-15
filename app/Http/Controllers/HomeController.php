<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the central welcome application form.
     */
    public function welcome()
    {
        return app(SubscriptionController::class)->showApplicationForm();
    }

    /**
     * Show the central super admin dashboard.
     */
    public function dashboard()
    {
        return view('dashboard');
    }
}
