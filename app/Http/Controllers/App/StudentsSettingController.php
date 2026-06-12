<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class StudentSettingsController extends Controller
{
    public function edit()
    {
        $student = Auth::guard('student')->user();
        return view('app.settings.student', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,' . $student->id,
        ]);

        $student->name = $request->name;
        $student->email = $request->email;
        $student->save();

        return Redirect::back()->with('status', 'profile-updated');
    }
}