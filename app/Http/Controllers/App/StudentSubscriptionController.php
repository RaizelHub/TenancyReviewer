<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentSubscriptionController extends Controller
{
    /**
     * Display a listing of the student subscriptions.
     */
    public function index()
    {
        $subscriptions = StudentSubscription::with('student')->get();
        return view('app.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new subscription.
     */
    public function create()
    {
        $students = Student::orderBy('name')->get();
        return view('app.subscriptions.create', compact('students'));
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'plan' => 'required|string|in:basic,premium',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string|in:active,inactive,pending',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        StudentSubscription::create($request->all());

        return redirect()->route('subscriptions.index')
            ->with('success', 'Student subscription created successfully.');
    }

    /**
     * Display the specified subscription.
     */
    public function show(string $id)
    {
        $subscription = StudentSubscription::with('student')->findOrFail($id);
        return view('app.subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified subscription.
     */
    public function edit(string $id)
    {
        $subscription = StudentSubscription::findOrFail($id);
        $students = Student::orderBy('name')->get();
        return view('app.subscriptions.edit', compact('subscription', 'students'));
    }

    /**
     * Update the specified subscription in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'plan' => 'required|string|in:basic,premium',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string|in:active,inactive,pending',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $subscription = StudentSubscription::findOrFail($id);
        $subscription->update($request->all());

        return redirect()->route('subscriptions.index')
            ->with('success', 'Student subscription updated successfully.');
    }

    /**
     * Remove the specified subscription from storage.
     */
    public function destroy(string $id)
    {
        $subscription = StudentSubscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('subscriptions.index')
            ->with('success', 'Student subscription deleted successfully.');
    }

    /**
     * Activate a subscription.
     */
    public function activate(string $id)
    {
        $subscription = StudentSubscription::findOrFail($id);
        $subscription->status = 'active';
        $subscription->save();

        return redirect()->route('subscriptions.show', $subscription->id)
            ->with('success', 'Subscription activated successfully.');
    }

    /**
     * Deactivate a subscription.
     */
    public function deactivate(string $id)
    {
        $subscription = StudentSubscription::findOrFail($id);
        $subscription->status = 'inactive';
        $subscription->save();

        return redirect()->route('subscriptions.show', $subscription->id)
            ->with('success', 'Subscription deactivated successfully.');
    }
}
