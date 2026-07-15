<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\App\SubjectController;
use App\Http\Controllers\App\StudentController;
use App\Http\Controllers\App\ActivityController;
use App\Http\Controllers\App\SubmissionController;
use App\Http\Controllers\App\StudentSubscriptionController;
use App\Http\Controllers\App\ChatController;
use App\Http\Controllers\App\PaymentController;
use App\Http\Controllers\App\StudentChatController;
use App\Http\Controllers\App\TenantDashboardController;
use App\Http\Controllers\App\StudentDashboardController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

// API routes for direct access
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {
    // Get all students
    Route::get('/students', function () {
        try {
            $students = \App\Models\Student::orderBy('name')->get();

            return response()->json([
                'success' => true,
                'students' => $students,
            ]);
        } catch (\Exception $e) {
            \Log::error('API error getting students', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get students: ' . $e->getMessage(),
            ], 500);
        }
    });

    // Create a new student
    Route::post('/students', function (Illuminate\Http\Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'student_id' => 'nullable|string|max:50',
                'notes' => 'nullable|string',
                'plan' => 'required|string|in:basic,premium',
            ]);

            // Generate a random password
            $password = \Illuminate\Support\Str::random(8);
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($password);

            // Create the student
            $student = \App\Models\Student::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'student' => $student,
                'password' => $password,
            ]);
        } catch (\Exception $e) {
            \Log::error('API error creating student', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create student: ' . $e->getMessage(),
            ], 500);
        }
    });
});

// Web routes
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', [TenantDashboardController::class, 'welcome'])->name('tenant.welcome');

    Route::get('/dashboard', [TenantDashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('tenant.dashboard');

    Route::get('/student/dashboard', [StudentDashboardController::class, 'dashboard'])->middleware(['auth:student'])->name('student.dashboard');

    // Student's My Subjects route
    Route::get('/my-subjects', [StudentDashboardController::class, 'subjects'])->middleware(['auth:student'])->name('student.subjects');

    // Student's Subject Details route
    Route::get('/my-subjects/{subject}', [StudentDashboardController::class, 'subjectShow'])->middleware(['auth:student'])->name('student.subject.show');

    // Student's Assignments route
    Route::get('/my-assignments', [StudentDashboardController::class, 'assignments'])->middleware(['auth:student'])->name('student.assignments');

    // Student's Materials route
    Route::get('/my-materials', [StudentDashboardController::class, 'materials'])->middleware(['auth:student'])->name('student.materials');

    // Student's Announcements route
    Route::get('/my-announcements', [StudentDashboardController::class, 'announcements'])->middleware(['auth:student'])->name('student.announcements');

    // Quiz routes
    Route::prefix('quizzes')->middleware(['auth:student'])->group(function () {
        Route::get('/', [App\Http\Controllers\App\QuizController::class, 'index'])->name('quizzes.index');
        Route::get('/{id}', [App\Http\Controllers\App\QuizController::class, 'show'])->name('quizzes.show');
        Route::get('/{id}/start', [App\Http\Controllers\App\QuizController::class, 'start'])->name('quizzes.start');
        Route::post('/attempt/{attemptId}/submit', [App\Http\Controllers\App\QuizController::class, 'submit'])->name('quizzes.submit');
        Route::get('/results/{attemptId}', [App\Http\Controllers\App\QuizController::class, 'results'])->name('quizzes.results');
    });

    // Student Plan Management routes
    Route::get('/plan', [App\Http\Controllers\App\StudentUpgradeController::class, 'showPlanManagement'])
        ->middleware(['auth:student'])
        ->name('student.plan');
    Route::get('/upgrade', [App\Http\Controllers\App\StudentUpgradeController::class, 'showUpgradePage'])
        ->middleware(['auth:student'])
        ->name('student.upgrade');
    Route::post('/upgrade', [App\Http\Controllers\App\StudentUpgradeController::class, 'processUpgrade'])
        ->middleware(['auth:student'])
        ->name('student.upgrade.process');
    Route::post('/downgrade', [App\Http\Controllers\App\StudentUpgradeController::class, 'processDowngrade'])
        ->middleware(['auth:student'])
        ->name('student.downgrade');

    // Submission routes
    Route::post('/submissions', [App\Http\Controllers\App\SubmissionController::class, 'store'])
        ->middleware(['auth:student'])
        ->name('submissions.store');
    Route::get('/submissions/{submission}', [App\Http\Controllers\App\SubmissionController::class, 'show'])
        ->middleware(['auth:student'])
        ->name('submissions.show');
    Route::get('/submissions/{submission}/download', [App\Http\Controllers\App\SubmissionController::class, 'download'])
        ->middleware(['auth:student'])
        ->name('submissions.download');
    Route::get('/submissions/{submission}/preview', [App\Http\Controllers\App\SubmissionController::class, 'preview'])
        ->middleware(['auth:student'])
        ->name('submissions.preview');

    // Student Profile routes
    Route::middleware('auth:student')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('student.profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('student.profile.update');
    });

    // Student settings/profile
    Route::middleware(['auth:student'])->group(function () {
        Route::get('/student/settings', [\App\Http\Controllers\App\ProfileController::class, 'edit'])->name('student.settings.edit');
        Route::patch('/student/settings', [\App\Http\Controllers\App\ProfileController::class, 'update'])->name('student.settings.update');
    });

    // Allow students to download and view attachments
    Route::middleware(['auth:student'])->group(function () {
        Route::get('/download-attachment/{path}', [\App\Http\Controllers\App\ActivityController::class, 'downloadAttachment'])
            ->where('path', '.*')
            ->name('activities.download-attachment');

        Route::get('/view-attachment/{path}', [\App\Http\Controllers\App\ActivityController::class, 'viewAttachment'])
            ->where('path', '.*')
            ->name('activities.view-attachment');
    });

    // Allow tenants/teachers to download and view attachments
    Route::middleware(['auth'])->group(function () {
        Route::get('/download-attachment/{path}', [\App\Http\Controllers\App\ActivityController::class, 'downloadAttachment'])
            ->where('path', '.*')
            ->name('activities.download-attachment.tenant');

        Route::get('/view-attachment/{path}', [\App\Http\Controllers\App\ActivityController::class, 'viewAttachment'])
            ->where('path', '.*')
            ->name('activities.view-attachment.tenant');
    });

    // Classroom routes
    Route::middleware('auth')->group(function () {
        // Subscription Plan
        Route::get('/subscription/plan', [\App\Http\Controllers\App\SubscriptionPlanController::class, 'index'])->name('subscription.plan');
        Route::get('/subscription/checkout', [PaymentController::class, 'checkout'])->name('subscription.checkout');
        Route::post('/subscription/change-plan', [\App\Http\Controllers\App\SubscriptionPlanController::class, 'changePlan'])->name('subscription.change-plan');

        // Subjects
        Route::resource('subjects', SubjectController::class);
        Route::post('/subjects/{subject}/students', [SubjectController::class, 'addStudents'])->name('subjects.students.add');
        Route::delete('/subjects/{subject}/students/{student}', [SubjectController::class, 'removeStudent'])->name('subjects.students.remove');
        Route::get('/subjects/{subject}/grade-report', [\App\Http\Controllers\App\GradeReportController::class, 'generateReport'])->name('subjects.grade-report');
        Route::get('/subjects/{subject}/export-grades', [\App\Http\Controllers\App\GradeReportController::class, 'exportGrades'])->name('subjects.export-grades');

        // Students
        Route::resource('students', StudentController::class);
        Route::post('/students/{student}/enroll', [StudentController::class, 'enroll'])->name('students.enroll');

        // Student Subscriptions
        Route::resource('subscriptions', StudentSubscriptionController::class);
        Route::post('/subscriptions/{id}/activate', [StudentSubscriptionController::class, 'activate'])->name('subscriptions.activate');
        Route::post('/subscriptions/{id}/deactivate', [StudentSubscriptionController::class, 'deactivate'])->name('subscriptions.deactivate');


        // Activities
        Route::resource('activities', ActivityController::class);
        Route::post('/activities/{activity}/publish', [ActivityController::class, 'publish'])->name('activities.publish');
        Route::post('/activities/{activity}/unpublish', [ActivityController::class, 'unpublish'])->name('activities.unpublish');

        // Teacher Quiz Management
        Route::resource('teacher-quizzes', App\Http\Controllers\App\TeacherQuizController::class)->parameters([
            'teacher-quizzes' => 'quiz'
        ]);
        Route::post('/teacher-quizzes/{quiz}/toggle-published', [App\Http\Controllers\App\TeacherQuizController::class, 'togglePublished'])->name('teacher-quizzes.toggle-published');

        // Quiz Questions Management
        Route::get('/teacher-quizzes/{quiz}/questions/create', [App\Http\Controllers\App\TeacherQuizController::class, 'createMultipleQuestions'])->name('teacher-quizzes.questions.create');
        Route::post('/teacher-quizzes/{quiz}/questions', [App\Http\Controllers\App\TeacherQuizController::class, 'storeMultipleQuestions'])->name('teacher-quizzes.questions.store');
        Route::get('/teacher-quizzes/{quiz}/questions/{question}/edit', [App\Http\Controllers\App\TeacherQuizController::class, 'editQuestion'])->name('teacher-quizzes.questions.edit');
        Route::put('/teacher-quizzes/{quiz}/questions/{question}', [App\Http\Controllers\App\TeacherQuizController::class, 'updateQuestion'])->name('teacher-quizzes.questions.update');
        Route::delete('/teacher-quizzes/{quiz}/questions/{question}', [App\Http\Controllers\App\TeacherQuizController::class, 'destroyQuestion'])->name('teacher-quizzes.questions.destroy');

        // Submissions
        Route::get('/submissions', [App\Http\Controllers\App\SubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [App\Http\Controllers\App\SubmissionController::class, 'show'])->name('submissions.show');
        Route::post('/activities/{activity}/submit', [App\Http\Controllers\App\SubmissionController::class, 'store'])->name('activities.submit');
        Route::post('/submissions/{submission}/grade', [App\Http\Controllers\App\SubmissionController::class, 'grade'])->name('submissions.grade');
        Route::get('/submissions/{submission}/download', [App\Http\Controllers\App\SubmissionController::class, 'download'])->name('submissions.download');
        Route::get('/submissions/{submission}/preview', [App\Http\Controllers\App\SubmissionController::class, 'preview'])->name('submissions.preview');
    });

    // Tenant/teacher settings/profile
    Route::middleware(['auth'])->group(function () {
        Route::get('/settings', [\App\Http\Controllers\App\ProfileController::class, 'edit'])->name('tenant.settings.edit');
        Route::patch('/settings', [\App\Http\Controllers\App\ProfileController::class, 'update'])->name('tenant.profile.update');

        // Chat routes for teachers
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/chat/create', [ChatController::class, 'create'])->name('chat.create');
        Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
        Route::get('/chat/{channelUrl}', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/chat/{channelUrl}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
        Route::delete('/chat/{channelUrl}', [ChatController::class, 'destroy'])->name('chat.destroy');

        // Catch-all route for Sendbird URLs - redirect to our custom chat view
        Route::get('/chat/sendbird_group_channel_{channelUrl}', function($channelUrl) {
            // Log the redirect
            \Illuminate\Support\Facades\Log::info('Redirecting from Sendbird URL', [
                'from' => request()->url(),
                'to' => route('chat.show', $channelUrl)
            ]);

            // Redirect to our custom chat view
            return redirect()->route('chat.show', $channelUrl);
        })->where('channelUrl', '.*'); // Match any characters in the channel URL

        // Subject-specific chat routes for teachers
        Route::get('/subjects/{subject}/chat/create', [ChatController::class, 'createSubjectChat'])->name('subjects.chat.create');
        Route::post('/subjects/{subject}/chat', [ChatController::class, 'storeSubjectChat'])->name('subjects.chat.store');
    });

    // Chat routes for students
    Route::middleware(['auth:student'])->group(function () {
        Route::get('/student/chat', [StudentChatController::class, 'index'])->name('student.chat.index');
        Route::get('/student/chat/create', [StudentChatController::class, 'create'])->name('student.chat.create');
        Route::post('/student/chat', [StudentChatController::class, 'store'])->name('student.chat.store');
        Route::get('/student/chat/{channelUrl}', [StudentChatController::class, 'show'])->name('student.chat.show');
        Route::post('/student/chat/{channelUrl}/send', [StudentChatController::class, 'sendMessage'])->name('student.chat.send');
        Route::delete('/student/chat/{channelUrl}', [StudentChatController::class, 'destroy'])->name('student.chat.destroy');

        // Subject-specific chat routes for students
        Route::get('/student/subjects/{subject}/chat/create', [StudentChatController::class, 'createSubjectChat'])->name('student.subjects.chat.create');
        Route::post('/student/subjects/{subject}/chat', [StudentChatController::class, 'storeSubjectChat'])->name('student.subjects.chat.store');

        // Catch-all route for Sendbird URLs - redirect to our custom student chat view
        Route::get('/student/chat/sendbird_group_channel_{channelUrl}', function($channelUrl) {
            // Log the redirect
            \Illuminate\Support\Facades\Log::info('Redirecting from Sendbird URL (student)', [
                'from' => request()->url(),
                'to' => route('student.chat.show', $channelUrl)
            ]);

            // Redirect to our custom student chat view
            return redirect()->route('student.chat.show', $channelUrl);
        })->where('channelUrl', '.*'); // Match any characters in the channel URL
    });

    // DEBUG: Register the file handling routes globally to ensure they are always available
    Route::get('/download-attachment/{path}', [\App\Http\Controllers\App\ActivityController::class, 'downloadAttachment'])
        ->where('path', '.*')
        ->name('activities.download-attachment');

    Route::get('/view-attachment/{path}', [\App\Http\Controllers\App\ActivityController::class, 'viewAttachment'])
        ->where('path', '.*')
        ->name('activities.view-attachment');

    Route::get('/file-viewer/{path}', [\App\Http\Controllers\App\ActivityController::class, 'fileViewer'])
        ->where('path', '.*')
        ->name('activities.file-viewer');

    Route::get('/raw-file/{path}', [\App\Http\Controllers\App\ActivityController::class, 'rawFile'])
        ->where('path', '.*')
        ->name('activities.raw-file');

    // Teacher routes
    Route::middleware('auth')->group(function () {
        // Quiz Questions
        Route::get('/quizzes/{quiz}/questions', [App\Http\Controllers\App\QuestionController::class, 'index'])->name('teacher.quizzes.questions.index');
        Route::get('/quizzes/{quiz}/questions/create', [App\Http\Controllers\App\QuestionController::class, 'create'])->name('teacher.quizzes.questions.create');
        Route::post('/quizzes/{quiz}/questions', [App\Http\Controllers\App\QuestionController::class, 'store'])->name('teacher.quizzes.questions.store');
        Route::get('/quizzes/{quiz}/questions/{question}', [App\Http\Controllers\App\QuestionController::class, 'show'])->name('teacher.quizzes.questions.show');
        Route::get('/quizzes/{quiz}/questions/{question}/edit', [App\Http\Controllers\App\QuestionController::class, 'edit'])->name('teacher.quizzes.questions.edit');
        Route::put('/quizzes/{quiz}/questions/{question}', [App\Http\Controllers\App\QuestionController::class, 'update'])->name('teacher.quizzes.questions.update');
        Route::delete('/quizzes/{quiz}/questions/{question}', [App\Http\Controllers\App\QuestionController::class, 'destroy'])->name('teacher.quizzes.questions.destroy');
    });

    require __DIR__.'/tenant-auth.php';
});
