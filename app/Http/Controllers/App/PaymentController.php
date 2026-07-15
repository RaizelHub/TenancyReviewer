<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $subscriptionLimitService;

    public function __construct(SubscriptionLimitService $subscriptionLimitService)
    {
        $this->subscriptionLimitService = $subscriptionLimitService;
    }

    /**
     * Show the mock Stripe-like checkout page.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|string|in:Basic,Premium,Pro',
        ]);

        $plan = $request->plan;
        $currentPlan = $this->subscriptionLimitService->getCurrentSubscriptionPlan();

        // Already on this plan
        if ($currentPlan === $plan) {
            return redirect()->route('subscription.plan')
                ->with('info', 'You are already on the ' . $plan . ' plan.');
        }

        // Plan details — kept in sync with SubscriptionPlanController
        $plans = [
            'Basic' => [
                'price'        => '₱999',
                'period'       => 'month',
                'max_subjects' => 3,
                'max_students' => 30,
                'features'     => [
                    'Basic classroom management',
                    'Limited file uploads',
                    'Basic activity types',
                    'Email support',
                ],
                'color' => 'blue',
                'icon'  => 'fa-user',
            ],
            'Premium' => [
                'price'        => '₱2,499',
                'period'       => 'month',
                'max_subjects' => 5,
                'max_students' => 50,
                'features'     => [
                    'Advanced classroom management',
                    'Unlimited file uploads',
                    'All activity types',
                    'Quiz functionality',
                    'Priority email support',
                ],
                'color' => 'indigo',
                'icon'  => 'fa-star',
            ],
            'Pro' => [
                'price'        => '₱4,999',
                'period'       => 'month',
                'max_subjects' => PHP_INT_MAX, // Unlimited
                'max_students' => PHP_INT_MAX, // Unlimited
                'features'     => [
                    'Complete classroom management',
                    'Unlimited file uploads',
                    'All activity types',
                    'Advanced quiz functionality',
                    'Priority 24/7 support',
                    'Custom branding',
                    'API access',
                ],
                'color' => 'purple',
                'icon'  => 'fa-crown',
            ],
        ];

        return view('app.subscription.checkout', compact('plan', 'plans', 'currentPlan'));
    }
}
