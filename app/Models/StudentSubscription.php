<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'plan',
        'start_date',
        'end_date',
        'status',
        'payment_method',
        'payment_reference',
        'amount',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the student that owns the subscription.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if the subscription is active.
     */
    public function isActive()
    {
        return $this->status === 'active' && 
               $this->start_date->isPast() && 
               $this->end_date->isFuture();
    }

    /**
     * Check if the subscription is expired.
     */
    public function isExpired()
    {
        return $this->end_date->isPast();
    }

    /**
     * Check if the subscription is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Get the remaining days of the subscription.
     */
    public function remainingDays()
    {
        if ($this->isExpired()) {
            return 0;
        }

        return now()->diffInDays($this->end_date);
    }
}
