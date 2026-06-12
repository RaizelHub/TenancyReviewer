<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StudentUpgradeController extends Controller
{
    /**
     * Show the plan management page.
     */
    public function showPlanManagement()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();

        // Get active subscription if any
        $activeSubscription = StudentSubscription::where('student_id', $student->id)
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        return view('app.student.plan-management', compact('student', 'activeSubscription'));
    }

    /**
     * Show the upgrade plan page.
     */
    public function showUpgradePage()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();

        // If student is already on premium plan, redirect to plan management
        if ($student->plan === 'premium') {
            return redirect()->route('student.plan')
                ->with('info', 'You are already on the Premium plan.');
        }

        return view('app.student.upgrade', compact('student'));
    }

    /**
     * Process the plan upgrade.
     */
    public function processUpgrade(Request $request)
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();

        // If student is already on premium plan, redirect to plan management
        if ($student->plan === 'premium') {
            return redirect()->route('student.plan')
                ->with('info', 'You are already on the Premium plan.');
        }

        // Validate the request
        $request->validate([
            'payment_method' => 'required|string|in:credit_card,paypal,gcash,paymaya',
            'terms_accepted' => 'required|accepted',
        ]);

        try {
            // Update student plan to premium
            $student->update(['plan' => 'premium']);

            // Create a new subscription record
            StudentSubscription::create([
                'student_id' => $student->id,
                'plan' => 'premium',
                'start_date' => now(),
                'end_date' => now()->addYear(), // Default to 1 year subscription
                'status' => 'active',
                'payment_method' => $request->payment_method,
                'payment_reference' => 'UPGRADE-' . uniqid(),
                'amount' => 4999.00, // Default premium plan price in Philippine Peso
                'notes' => 'Upgraded from Basic plan',
            ]);

            // Log the upgrade
            Log::info('Student upgraded to premium plan', [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'payment_method' => $request->payment_method,
            ]);

            return redirect()->route('student.plan')
                ->with('success', 'Congratulations! You have successfully upgraded to the Premium plan.');
        } catch (\Exception $e) {
            Log::error('Error upgrading student plan', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['upgrade_error' => 'There was an error processing your upgrade. Please try again later.']);
        }
    }

    /**
     * Process the plan downgrade.
     */
    public function processDowngrade(Request $request)
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();

        // If student is already on basic plan, redirect to plan management
        if ($student->plan === 'basic') {
            return redirect()->route('student.plan')
                ->with('info', 'You are already on the Basic plan.');
        }

        try {
            // Get active subscription if any
            $activeSubscription = StudentSubscription::where('student_id', $student->id)
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->orderBy('created_at', 'desc')
                ->first();

            if ($activeSubscription) {
                // Mark the subscription as cancelled
                $activeSubscription->update([
                    'status' => 'cancelled',
                    'notes' => $activeSubscription->notes . "\nDowngraded to Basic plan on " . now()->format('Y-m-d H:i:s'),
                ]);
            }

            // Update student plan to basic
            $student->update(['plan' => 'basic']);

            // Log the downgrade
            Log::info('Student downgraded to basic plan', [
                'student_id' => $student->id,
                'student_name' => $student->name,
            ]);

            return redirect()->route('student.plan')
                ->with('success', 'You have successfully downgraded to the Basic plan.');
        } catch (\Exception $e) {
            Log::error('Error downgrading student plan', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['downgrade_error' => 'There was an error processing your downgrade. Please try again later.']);
        }
    }
}
