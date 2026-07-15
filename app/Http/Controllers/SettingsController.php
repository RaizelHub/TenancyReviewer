<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $settings = [
            'app_name' => Setting::get('app_name', config('app.name')),
        ];

        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        // Log setting changes
        ActivityLog::log('Settings Updated', 'Super Admin updated platform name.');

        return redirect()->route('settings.index')->with('success', 'System settings updated successfully!');
    }
}
