<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
    ];

    /**
     * Get the user associated with this activity log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to write an activity log.
     */
    public static function log(string $action, string $description): self
    {
        return self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
        ]);
    }
}
