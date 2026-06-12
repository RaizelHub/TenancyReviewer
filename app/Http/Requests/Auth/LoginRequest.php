<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        Log::info('Login attempt', [
            'email' => $this->input('email'),
            'remember' => $this->boolean('remember'),
            'tenant' => tenant() ? tenant()->id : 'central',
            'database' => DB::connection()->getDatabaseName()
        ]);

        // Try student guard first
        try {
            if (Auth::guard('student')->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
                Log::info('Student authentication successful for email: ' . $this->input('email'));
                RateLimiter::clear($this->throttleKey());
                return;
            }
        } catch (\Exception $e) {
            Log::error('Student authentication error', [
                'error' => $e->getMessage(),
                'email' => $this->input('email'),
                'tenant' => tenant() ? tenant()->id : 'central',
                'database' => DB::connection()->getDatabaseName()
            ]);
        }

        // Try web guard if student authentication fails
        try {
            if (Auth::guard('web')->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
                Log::info('Tenant authentication successful for email: ' . $this->input('email'));
                RateLimiter::clear($this->throttleKey());
                return;
            }
        } catch (\Exception $e) {
            Log::error('Tenant authentication error', [
                'error' => $e->getMessage(),
                'email' => $this->input('email'),
                'tenant' => tenant() ? tenant()->id : 'central',
                'database' => DB::connection()->getDatabaseName()
            ]);
        }

        RateLimiter::hit($this->throttleKey());
        Log::warning('Authentication failed for email: ' . $this->input('email'), [
            'tenant' => tenant() ? tenant()->id : 'central',
            'tenant_database' => tenant() ? 'tenant_' . tenant()->id : 'central',
            'current_database' => DB::connection()->getDatabaseName()
        ]);

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
