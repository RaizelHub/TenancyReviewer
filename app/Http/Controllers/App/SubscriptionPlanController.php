<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionPlanController extends Controller
{
    protected $subscriptionLimitService;

    public function __construct(SubscriptionLimitService $subscriptionLimitService)
    {
        $this->subscriptionLimitService = $subscriptionLimitService;
    }

    /**
     * Display the subscription plan page.
     */
    public function index()
    {
        $currentPlan = $this->subscriptionLimitService->getCurrentSubscriptionPlan();
        $maxSubjects = $this->subscriptionLimitService->getMaxSubjects();
        $maxStudents = $this->subscriptionLimitService->getMaxStudents();
        $remainingSubjects = $this->subscriptionLimitService->getRemainingSubjects();
        $remainingStudents = $this->subscriptionLimitService->getRemainingStudents();
        
        // Get current usage
        $subjectCount = \App\Models\Subject::where('user_id', Auth::id())->count();
        $studentCount = \App\Models\Student::count();
        
        // Plan details
        $plans = [
            'Basic' => [
                'price' => '₱999',
                'period' => 'month',
                'max_subjects' => 3,
                'max_students' => 30,
                'features' => [
                    'Basic classroom management',
                    'Limited file uploads',
                    'Basic activity types',
                    'Email support',
                ],
                'color' => 'blue',
                'icon' => 'fa-user',
            ],
            'Premium' => [
                'price' => '₱2,499',
                'period' => 'month',
                'max_subjects' => 5,
                'max_students' => 50,
                'features' => [
                    'Advanced classroom management',
                    'Unlimited file uploads',
                    'All activity types',
                    'Quiz functionality',
                    'Priority email support',
                ],
                'color' => 'indigo',
                'icon' => 'fa-star',
            ],
            'Pro' => [
                'price' => '₱4,999',
                'period' => 'month',
                'max_subjects' => 'Unlimited',
                'max_students' => 'Unlimited',
                'features' => [
                    'Complete classroom management',
                    'Unlimited file uploads',
                    'All activity types',
                    'Advanced quiz functionality',
                    'Priority 24/7 support',
                    'Custom branding',
                    'API access',
                ],
                'color' => 'purple',
                'icon' => 'fa-crown',
            ],
        ];
        
        return view('app.subscription.plan', compact(
            'currentPlan', 
            'maxSubjects', 
            'maxStudents', 
            'remainingSubjects', 
            'remainingStudents', 
            'subjectCount', 
            'studentCount', 
            'plans'
        ));
    }
    
    /**
     * Process a plan upgrade or downgrade.
     */
    public function changePlan(Request $request)
    {
        $request->validate([
            'plan' => 'required|string|in:Basic,Premium,Pro',
        ]);
        
        $newPlan = $request->plan;
        $currentPlan = $this->subscriptionLimitService->getCurrentSubscriptionPlan();
        
        // Check if trying to downgrade with too many subjects/students
        if ($newPlan === 'Basic') {
            $subjectCount = \App\Models\Subject::where('user_id', Auth::id())->count();
            $studentCount = \App\Models\Student::count();
            
            if ($subjectCount > 3) {
                return redirect()->route('subscription.plan')
                    ->with('error', 'Cannot downgrade to Basic plan. You have ' . $subjectCount . ' subjects, but Basic plan only allows 3. Please remove some subjects first.');
            }
            
            if ($studentCount > 30) {
                return redirect()->route('subscription.plan')
                    ->with('error', 'Cannot downgrade to Basic plan. You have ' . $studentCount . ' students, but Basic plan only allows 30. Please remove some students first.');
            }
        } elseif ($newPlan === 'Premium') {
            $subjectCount = \App\Models\Subject::where('user_id', Auth::id())->count();
            $studentCount = \App\Models\Student::count();
            
            if ($subjectCount > 5) {
                return redirect()->route('subscription.plan')
                    ->with('error', 'Cannot downgrade to Premium plan. You have ' . $subjectCount . ' subjects, but Premium plan only allows 5. Please remove some subjects first.');
            }
            
            if ($studentCount > 50) {
                return redirect()->route('subscription.plan')
                    ->with('error', 'Cannot downgrade to Premium plan. You have ' . $studentCount . ' students, but Premium plan only allows 50. Please remove some students first.');
            }
        }
        
        // Update the user's subscription plan
        $user = Auth::user();
        $user->subscription_plan = $newPlan;
        $user->save();
        
        // Update the tenant data
        $tenant = \App\Models\Tenant::find(tenant('id'));
        if ($tenant) {
            $data = $tenant->data;
            $data['subscription_plan'] = $newPlan;
            $tenant->data = $data;
            $tenant->save();
        }
        
        $action = $currentPlan === $newPlan ? 'changed to' : ($currentPlan < $newPlan ? 'upgraded to' : 'downgraded to');
        
        return redirect()->route('subscription.plan')
            ->with('success', 'Your subscription plan has been ' . $action . ' ' . $newPlan . ' successfully.');
    }
}
