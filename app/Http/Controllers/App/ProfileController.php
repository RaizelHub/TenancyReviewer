<?php

namespace App\Http\Controllers\App;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = Auth::guard('student')->check() ? Auth::guard('student')->user() : $request->user();
        return view('app.profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::guard('student')->check() ? Auth::guard('student')->user() : $request->user();

        // Only validate and update profile photo if it's present in the request
        if ($request->hasFile('profile_photo')) {
            $request->validate([
                'profile_photo' => 'image|max:2048', // 2MB max
            ]);

            // Handle profile photo upload
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = '/storage/' . $path;
            $user->save();
        }

        return Redirect::back()->with('status', 'profile-updated');
    }
}
