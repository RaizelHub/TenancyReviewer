<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Notifications\StudentCredentials;
use App\Services\CloudinaryService;
use App\Services\SubscriptionLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * The Cloudinary service instance.
     *
     * @var CloudinaryService
     */
    protected $cloudinaryService;

    /**
     * The Subscription Limit service instance.
     *
     * @var SubscriptionLimitService
     */
    protected $subscriptionLimitService;

    /**
     * Create a new controller instance.
     *
     * @param CloudinaryService $cloudinaryService
     * @param SubscriptionLimitService $subscriptionLimitService
     * @return void
     */
    public function __construct(CloudinaryService $cloudinaryService, SubscriptionLimitService $subscriptionLimitService)
    {
        $this->cloudinaryService = $cloudinaryService;
        $this->subscriptionLimitService = $subscriptionLimitService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::orderBy('name');

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Apply subject filter if provided
        if ($request->has('subject_id') && !empty($request->subject_id)) {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject_id);
            });
        }

        // Paginate the results
        $students = $query->paginate(10)->withQueryString();

        // Get subscription plan limits
        $maxStudents = $this->subscriptionLimitService->getMaxStudents();
        $remainingStudents = $this->subscriptionLimitService->getRemainingStudents();
        $currentPlan = $this->subscriptionLimitService->getCurrentSubscriptionPlan();

        return view('app.students.index', compact('students', 'maxStudents', 'remainingStudents', 'currentPlan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if the user can create more students based on their subscription plan
        if (!$this->subscriptionLimitService->canCreateStudent()) {
            return redirect()->route('students.index')
                ->with('error', 'You have reached the maximum number of students allowed for your subscription plan. Please upgrade to add more students.');
        }

        $subjects = Subject::where('user_id', Auth::id())->orderBy('name')->get();
        return view('app.students.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if the user can create more students based on their subscription plan
        if (!$this->subscriptionLimitService->canCreateStudent()) {
            return redirect()->route('students.index')
                ->with('error', 'You have reached the maximum number of students allowed for your subscription plan. Please upgrade to add more students.');
        }

        \Log::info('StudentController@store called', [
            'request_data' => $request->all(),
            'tenant_id' => tenant() ? tenant()->id : 'none',
        ]);

        // Convert checkbox to boolean BEFORE validation
        $request->merge([
            'send_credentials' => $request->has('send_credentials') ? 1 : 0,
        ]);

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'student_id' => 'nullable|string|max:50',
                'profile_photo' => 'nullable|image|max:2048',
                'notes' => 'nullable|string',
                'plan' => 'required|string|in:basic,premium',
                'subject_ids' => 'nullable|array',
                'subject_ids.*' => 'exists:subjects,id',
                'send_credentials' => 'nullable|boolean',
            ]);
            \Log::info('Validation passed', $request->only(['name', 'email', 'plan']));

            $data = $request->except(['_token', 'profile_photo', 'subject_ids', 'send_credentials']);

            // Set default password to '12345678'
            $password = '12345678';
            $data['password'] = \Illuminate\Support\Facades\Hash::make($password);
            \Log::info('Password set to default 12345678 and hashed');

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                \Log::info('Profile photo detected, attempting upload', [
                    'file_name' => $request->file('profile_photo')->getClientOriginalName(),
                    'file_size' => $request->file('profile_photo')->getSize(),
                    'file_mime' => $request->file('profile_photo')->getMimeType(),
                ]);

                try {
                    $cloudinaryUrl = $this->cloudinaryService->uploadImage($request->file('profile_photo'), 'student-photos');
                    if ($cloudinaryUrl) {
                        $data['profile_photo'] = $cloudinaryUrl;
                        \Log::info('Profile photo uploaded', ['url' => $cloudinaryUrl]);
                    } else {
                        // If Cloudinary upload fails, create a fallback URL using UI Avatars
                        $name = urlencode($request->name);
                        $data['profile_photo'] = "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
                        \Log::warning('Profile photo upload failed, using UI Avatars fallback');
                    }
                } catch (\Exception $e) {
                    \Log::error('Exception during profile photo upload: ' . $e->getMessage(), [
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);

                    // Use UI Avatars as fallback
                    $name = urlencode($request->name);
                    $data['profile_photo'] = "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
                }
            }

            // Create the student
            $student = Student::create($data);
            \Log::info('Student created successfully', [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'student_email' => $student->email
            ]);

            // Attach subjects if provided
            if ($request->has('subject_ids')) {
                $student->subjects()->attach($request->subject_ids);
                \Log::info('Subjects attached to student', ['subject_ids' => $request->subject_ids]);
            }

            // Always send credentials email
                try {
                \Illuminate\Support\Facades\Notification::route('mail', $student->email)
                    ->notify(new \App\Notifications\StudentCredentials($student, $password));
                \Log::info('Student credentials email sent');
                } catch (\Exception $e) {
                \Log::error('Failed to send student credentials email: ' . $e->getMessage());
            }

            return redirect()->route('students.index')
                ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating student', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()->withErrors(['error' => 'Failed to create student: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::with('subjects')->findOrFail($id);
        return view('app.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        $subjects = Subject::where('user_id', Auth::id())->orderBy('name')->get();
        $enrolledSubjectIds = $student->subjects->pluck('id')->toArray();

        return view('app.students.edit', compact('student', 'subjects', 'enrolledSubjectIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'student_id' => 'nullable|string|max:50',
            'profile_photo' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
            'plan' => 'required|string|in:basic,premium',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'reset_password' => 'nullable|boolean',
        ]);

        $data = $request->except(['_token', '_method', 'profile_photo', 'subject_ids', 'reset_password']);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            \Log::info('Profile photo update detected', [
                'file_name' => $request->file('profile_photo')->getClientOriginalName(),
                'file_size' => $request->file('profile_photo')->getSize(),
                'file_mime' => $request->file('profile_photo')->getMimeType(),
            ]);

            // Delete old image if exists
            if ($student->profile_photo && strpos($student->profile_photo, 'cloudinary') !== false) {
                try {
                    $this->cloudinaryService->deleteImage($student->profile_photo);
                    \Log::info('Old profile photo deleted from Cloudinary');
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old profile photo: ' . $e->getMessage());
                }
            }

            // Upload new image
            try {
                $cloudinaryUrl = $this->cloudinaryService->uploadImage($request->file('profile_photo'), 'student-photos');
                if ($cloudinaryUrl) {
                    $data['profile_photo'] = $cloudinaryUrl;
                    \Log::info('New profile photo uploaded', ['url' => $cloudinaryUrl]);
                } else {
                    // If Cloudinary upload fails, create a fallback URL using UI Avatars
                    $name = urlencode($request->name);
                    $data['profile_photo'] = "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
                    \Log::warning('Profile photo upload failed, using UI Avatars fallback');
                }
            } catch (\Exception $e) {
                \Log::error('Exception during profile photo upload: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);

                // Use UI Avatars as fallback
                $name = urlencode($request->name);
                $data['profile_photo'] = "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
            }
        }

        // Reset password if requested
        $password = null;
        if ($request->has('reset_password') && $request->reset_password) {
            $password = Str::random(8);
            $data['password'] = Hash::make($password);
        }

        // Update the student
        $student->update($data);

        // Sync subjects if provided
        if ($request->has('subject_ids')) {
            $student->subjects()->sync($request->subject_ids);
        }

        // Send new credentials email if password was reset
        if ($password) {
            try {
                Notification::route('mail', $student->email)
                    ->notify(new StudentCredentials($student, $password));
            } catch (\Exception $e) {
                // Log the error but don't stop the process
                Log::error('Failed to send student credentials email: ' . $e->getMessage());
            }
        }

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);

        // Delete profile photo from Cloudinary if exists
        if ($student->profile_photo) {
            $this->cloudinaryService->deleteImage($student->profile_photo);
        }

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Enroll a student in subjects.
     */
    public function enroll(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $student->subjects()->attach($request->subject_ids);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Student enrolled in subjects successfully.');
    }
}
