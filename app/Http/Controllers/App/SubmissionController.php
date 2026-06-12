<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Add debug logging
            Log::info('Fetching submissions for index view');

            // Check if we're in a tenant context
            Log::info('Tenant context check', [
                'is_tenant' => tenant() ? 'Yes' : 'No',
                'tenant_id' => tenant() ? tenant()->id : 'None',
                'database' => DB::connection()->getDatabaseName()
            ]);

            // Get all submissions from the database
            $allSubmissions = Submission::all();
            Log::info('Raw submissions count: ' . $allSubmissions->count());

            // Get submissions with relationships
            $submissions = Submission::with(['student', 'activity'])->latest()->paginate(10);

            // Log the count of submissions
            Log::info('Found ' . $submissions->count() . ' submissions for display');

            // Log some details about the first few submissions if any exist
            if ($submissions->count() > 0) {
                $sampleSubmissions = $submissions->take(3);
                foreach ($sampleSubmissions as $index => $submission) {
                    Log::info("Submission #{$index} details", [
                        'id' => $submission->id,
                        'student_id' => $submission->student_id,
                        'activity_id' => $submission->activity_id,
                        'status' => $submission->status,
                        'has_student' => $submission->student ? 'Yes' : 'No',
                        'has_activity' => $submission->activity ? 'Yes' : 'No'
                    ]);
                }
            }

            return view('app.submissions.index', compact('submissions'));
        } catch (\Exception $e) {
            Log::error('Error in submissions index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('app.submissions.index', ['submissions' => collect(), 'error' => 'An error occurred while loading submissions.']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Submission store method called', $request->all());

            $request->validate([
                'activity_id' => 'required|exists:activities,id',
                'file' => 'required|file|max:20480', // 20MB max, accept any file type
            ]);

            Log::info('Validation passed');

            try {
                $activity = Activity::findOrFail($request->activity_id);
                Log::info('Activity found', [
                    'activity_id' => $activity->id,
                    'activity_title' => $activity->title
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to find activity', [
                    'activity_id' => $request->activity_id,
                    'error' => $e->getMessage()
                ]);
                return back()->with('error', 'Activity not found. Please try again.');
            }

            $student = Auth::guard('student')->user();

            if (!$student) {
                Log::error('Student authentication failed');
                return redirect()->route('student.login')->with('error', 'Please log in to submit assignments.');
            }

            Log::info('Student and activity found', [
                'student_id' => $student->id,
                'activity_id' => $activity->id,
                'activity_title' => $activity->title
            ]);

        // Check if student is enrolled in the subject
        if (!$student->subjects()->where('subjects.id', $activity->subject_id)->exists()) {
            Log::warning('Student not enrolled in subject', [
                'student_id' => $student->id,
                'subject_id' => $activity->subject_id
            ]);
            return back()->with('error', 'You are not enrolled in this subject.');
        }

        // Check if due date has passed
        if ($activity->due_date < now()) {
            Log::warning('Due date passed', [
                'due_date' => $activity->due_date,
                'now' => now()
            ]);
            return back()->with('error', 'The due date for this assignment has passed.');
        }

        // Check if already submitted
        if ($activity->submissions()->where('student_id', $student->id)->exists()) {
            Log::warning('Already submitted', [
                'student_id' => $student->id,
                'activity_id' => $activity->id
            ]);
            return back()->with('error', 'You have already submitted this assignment.');
        }

        // Store the file
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // Generate a unique filename that preserves the original name
        $filename = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $extension;

        // Store the file with the custom filename
        $path = $file->storeAs('submissions', $filename);
        Log::info('File stored', [
            'path' => $path,
            'original_name' => $originalName,
            'size' => $file->getSize()
        ]);

        // Create submission
        $submission = new Submission([
            'student_id' => $student->id,
            'activity_id' => $activity->id,
            'file_path' => $path,
            'status' => 'submitted',
        ]);

        $submission->save();
        Log::info('Submission saved', ['submission_id' => $submission->id]);

        return back()->with('success', 'Assignment submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Error in submission process', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred while submitting your assignment. Please try again or contact support.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $submission)
    {
        // Use Gate facade instead of $this->authorize
        Gate::authorize('view', $submission);

        $student = Auth::guard('student')->user();
        $isPremium = $student ? $student->subscription_type === 'premium' : false;

        return view('app.submissions.show', compact('submission', 'isPremium'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function grade(Request $request, Submission $submission)
    {
        $validator = Validator::make($request->all(), [
            'grade' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $submission->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'status' => 'graded',
            'graded_at' => now(),
        ]);

        return back()->with('success', 'Submission graded successfully.');
    }

    public function download(Submission $submission)
    {
        // Use Gate facade instead of $this->authorize
        Gate::authorize('view', $submission);

        $student = Auth::guard('student')->user();

        // Get the file extension
        $extension = pathinfo($submission->file_path, PATHINFO_EXTENSION);
        $isPdf = strtolower($extension) === 'pdf';

        // Check if student is premium or if the file is not a PDF
        if (($student && $student->subscription_type === 'premium') || !$isPdf) {
            return Storage::download($submission->file_path);
        }

        // For basic users with PDF files, return a watermarked version
        return $this->getWatermarkedPdf($submission->file_path);
    }

    public function preview(Submission $submission)
    {
        // Use Gate facade instead of $this->authorize
        Gate::authorize('view', $submission);

        $student = Auth::guard('student')->user();

        // Get the file extension
        $extension = pathinfo($submission->file_path, PATHINFO_EXTENSION);
        $isPdf = strtolower($extension) === 'pdf';

        // If not a PDF, just download it
        if (!$isPdf) {
            return Storage::download($submission->file_path);
        }

        // Check if student is premium
        if ($student && $student->subscription_type === 'premium') {
            return response()->file(Storage::path($submission->file_path));
        }

        // For basic users with PDF files, return a watermarked preview
        return $this->getWatermarkedPdf($submission->file_path, true);
    }

    private function getWatermarkedPdf($filePath, $isPreview = false)
    {
        // Get the PDF content
        $pdfContent = Storage::get($filePath);

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'pdf_');
        file_put_contents($tempFile, $pdfContent);

        // Load the PDF
        $pdf = new \setasign\Fpdi\Fpdi();
        $pageCount = $pdf->setSourceFile($tempFile);

        // Create a new PDF
        $newPdf = new \setasign\Fpdi\Fpdi();

        // Add watermark to each page
        for ($i = 1; $i <= $pageCount; $i++) {
            $tplId = $pdf->importPage($i);
            $newPdf->AddPage();
            $newPdf->useTemplate($tplId);

            // Add watermark
            $newPdf->SetFont('Arial', 'B', 50);
            $newPdf->SetTextColor(200, 200, 200);
            $newPdf->Rotate(45, 105, 150);
            $newPdf->Text(105, 150, 'PREVIEW');

            // For preview, only show first 3 pages
            if ($isPreview && $i >= 3) {
                break;
            }
        }

        // Clean up
        unlink($tempFile);

        // Return the watermarked PDF
        return response($newPdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $isPreview ? 'inline' : 'attachment; filename="document.pdf"'
        ]);
    }
}
