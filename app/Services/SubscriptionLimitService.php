<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class SubscriptionLimitService
{
    /**
     * Get the maximum number of subjects allowed for the current subscription plan
     *
     * @return int
     */
    public function getMaxSubjects(): int
    {
        $plan = $this->getCurrentSubscriptionPlan();
        
        return match ($plan) {
            'Pro' => PHP_INT_MAX, // Unlimited
            'Premium' => 5,
            'Basic' => 3,
            default => 1, // Fallback
        };
    }
    
    /**
     * Get the maximum number of students allowed for the current subscription plan
     *
     * @return int
     */
    public function getMaxStudents(): int
    {
        $plan = $this->getCurrentSubscriptionPlan();
        
        return match ($plan) {
            'Pro' => PHP_INT_MAX, // Unlimited
            'Premium' => 50,
            'Basic' => 30,
            default => 10, // Fallback
        };
    }
    
    /**
     * Check if the tenant can create more subjects
     *
     * @return bool
     */
    public function canCreateSubject(): bool
    {
        $currentCount = Subject::where('user_id', Auth::id())->count();
        $maxAllowed = $this->getMaxSubjects();
        
        return $currentCount < $maxAllowed;
    }
    
    /**
     * Check if the tenant can create more students
     *
     * @return bool
     */
    public function canCreateStudent(): bool
    {
        $currentCount = Student::count();
        $maxAllowed = $this->getMaxStudents();
        
        return $currentCount < $maxAllowed;
    }
    
    /**
     * Get the current subscription plan
     *
     * @return string
     */
    public function getCurrentSubscriptionPlan(): string
    {
        $user = Auth::user();
        
        if ($user && $user->subscription_plan) {
            return $user->subscription_plan;
        }
        
        return 'Basic'; // Default fallback
    }
    
    /**
     * Get the remaining number of subjects that can be created
     *
     * @return int
     */
    public function getRemainingSubjects(): int
    {
        $currentCount = Subject::where('user_id', Auth::id())->count();
        $maxAllowed = $this->getMaxSubjects();
        
        if ($maxAllowed === PHP_INT_MAX) {
            return PHP_INT_MAX; // Unlimited
        }
        
        return max(0, $maxAllowed - $currentCount);
    }
    
    /**
     * Get the remaining number of students that can be created
     *
     * @return int
     */
    public function getRemainingStudents(): int
    {
        $currentCount = Student::count();
        $maxAllowed = $this->getMaxStudents();
        
        if ($maxAllowed === PHP_INT_MAX) {
            return PHP_INT_MAX; // Unlimited
        }
        
        return max(0, $maxAllowed - $currentCount);
    }
}
