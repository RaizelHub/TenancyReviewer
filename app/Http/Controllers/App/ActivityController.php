<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::with('subject')->latest()->paginate(10);
        return view('app.activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::all();
        return view('app.activities.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'activity_document' => 'nullable|file|max:10240', // 10MB max, allow any file type
            'reviewer_attachment' => 'nullable|file|max:10240', // 10MB max, allow any file type
            'google_docs_url' => 'nullable|url|max:255',
            'attachment' => 'nullable|file|max:20480', // Allow any file type, up to 20MB
        ];

        // Add conditional validation for due_date based on activity type
        if ($request->type === 'assignment') {
            $rules['due_date'] = 'required|date|after:today';
        } else {
            // For materials and announcements, due_date is optional
            $rules['due_date'] = 'nullable|date';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Debug output removed so the file upload process completes

        $data = $request->except(['activity_document', 'reviewer_attachment', 'attachment']);

        // Handle activity document upload
        if ($request->hasFile('activity_document')) {
            $data['activity_document_path'] = $request->file('activity_document')->store('activities/documents');
        }

        // Handle reviewer attachment upload
        if ($request->hasFile('reviewer_attachment')) {
            $data['reviewer_attachment_path'] = $request->file('reviewer_attachment')->store('activities/reviewer');
        }

        // Handle general attachment upload (any file type)
        Log::info('Default disk', ['default_disk' => config('filesystems.default')]);
        Log::info('Public disk root', ['public_root' => config('filesystems.disks.public.root')]);
        Log::info('Storage path', ['storage_path' => storage_path()]);

        if ($request->hasFile('attachment')) {
            $tenantDb = app(\Illuminate\Database\Connection::class)->getDatabaseName();
            $relativePath = "$tenantDb/activities/attachments";
            $filename = $request->file('attachment')->getClientOriginalName();
            $fullPath = "$relativePath/$filename";
            $result = Storage::disk('public')->putFileAs($relativePath, $request->file('attachment'), $filename);
            Log::info('PutFileAs result', ['result' => $result]);
            $data['attachment'] = $fullPath;
            Log::info('After forced putFileAs', ['saved_path' => $data['attachment']]);
        }

        $activity = Activity::create($data);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity->load('subject', 'submissions.student');

        // Log submission counts for debugging
        Log::info('Activity submissions', [
            'activity_id' => $activity->id,
            'submission_count' => $activity->submissions->count(),
            'graded_count' => $activity->submissions->where('status', 'graded')->count()
        ]);

        return view('app.activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $subjects = Subject::all();
        return view('app.activities.edit', compact('activity', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        // Base validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'activity_document' => 'nullable|file|max:10240', // 10MB max, allow any file type
            'reviewer_attachment' => 'nullable|file|max:10240', // 10MB max, allow any file type
            'google_docs_url' => 'nullable|url|max:255',
            'attachment' => 'nullable|file|max:20480', // Allow any file type, up to 20MB
        ];

        // Add conditional validation for due_date based on activity type
        if ($request->type === 'assignment') {
            $rules['due_date'] = 'required|date|after:today';
        } else {
            // For materials and announcements, due_date is optional
            $rules['due_date'] = 'nullable|date';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['activity_document', 'reviewer_attachment', 'attachment']);

        // Handle activity document upload
        if ($request->hasFile('activity_document')) {
            // Delete old file if exists
            if ($activity->activity_document_path) {
                Storage::delete($activity->activity_document_path);
            }
            $data['activity_document_path'] = $request->file('activity_document')->store('activities/documents');
        }

        // Handle reviewer attachment upload
        if ($request->hasFile('reviewer_attachment')) {
            // Delete old file if exists
            if ($activity->reviewer_attachment_path) {
                Storage::delete($activity->reviewer_attachment_path);
            }
            $data['reviewer_attachment_path'] = $request->file('reviewer_attachment')->store('activities/reviewer');
        }

        // Handle general attachment upload (any file type)
        Log::info('Before upload', ['path' => storage_path('app/public')]);
        if ($request->hasFile('attachment')) {
            $tenantDb = app(\Illuminate\Database\Connection::class)->getDatabaseName();
            $relativePath = "$tenantDb/activities/attachments";
            $filename = $request->file('attachment')->getClientOriginalName();
            $fullPath = "$relativePath/$filename";
            // Delete old file if exists
            if ($activity->attachment) {
                $oldFile = storage_path("app/public/" . $activity->attachment);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            Storage::disk('public')->putFileAs($relativePath, $request->file('attachment'), $filename);
            $data['attachment'] = $fullPath;
            Log::info('After forced putFileAs', ['saved_path' => $data['attachment']]);
        }

        $activity->update($data);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        // Delete associated files
        if ($activity->activity_document_path) {
            Storage::delete($activity->activity_document_path);
        }
        if ($activity->reviewer_attachment_path) {
            Storage::delete($activity->reviewer_attachment_path);
        }

        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully.');
    }

    public function publish(Activity $activity)
    {
        $activity->update(['is_published' => true]);
        return back()->with('success', 'Activity published successfully.');
    }

    public function unpublish(Activity $activity)
    {
        $activity->update(['is_published' => false]);
        return back()->with('success', 'Activity unpublished successfully.');
    }

    /**
     * Download an activity attachment from tenant-specific storage.
     */
    public function downloadAttachment(Request $request, $path)
    {
        // Allow access if authenticated as student or default user
        if (!auth('student')->check() && !auth()->check()) {
            return redirect()->route('login');
        }

        $disk = 'public'; // This is the tenant-specific disk due to Stancl tenancy
        if (!Storage::disk($disk)->exists($path)) {
            abort(404);
        }

        // Allow all users to download files regardless of plan
        if (auth('student')->check()) {
            $student = auth('student')->user();

            // Log the download for tracking purposes
            Log::info('Student downloaded file', [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'path' => $path
            ]);
        }

        // For downloading, return with Content-Disposition: attachment
        return Storage::disk($disk)->download($path);
    }

    /**
     * View an activity attachment from tenant-specific storage.
     */
    public function viewAttachment(Request $request, $path)
    {
        // Allow access if authenticated as student or default user
        if (!auth('student')->check() && !auth()->check()) {
            return redirect()->route('login');
        }

        $disk = 'public'; // This is the tenant-specific disk due to Stancl tenancy
        if (!Storage::disk($disk)->exists($path)) {
            abort(404);
        }

        // Get file information
        $filePath = Storage::disk($disk)->path($path);
        $fileName = basename($path);
        $mimeType = Storage::disk($disk)->mimeType($path);
        $fileContent = Storage::disk($disk)->get($path);
        $fileSize = Storage::disk($disk)->size($path);

        // Log information for debugging
        Log::info('Viewing file', [
            'path' => $path,
            'filePath' => $filePath,
            'fileName' => $fileName,
            'mimeType' => $mimeType,
            'fileSize' => $fileSize,
            'exists' => file_exists($filePath)
        ]);

        // Get file extension to determine how to handle it
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        // Check if the file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // Special handling for PDF files
        if ($extension === 'pdf') {
            // For PDFs, we'll use a direct file path approach which works better for inline viewing
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);
        }

        // Special handling for image files
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        if (in_array($extension, $imageExtensions)) {
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);
        }

        // For other file types, use standard response
        $response = response($fileContent);
        $response->header('Content-Type', $mimeType);
        $response->header('Content-Length', $fileSize);
        $response->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        $response->header('Cache-Control', 'public, max-age=3600');

        return $response;
    }

    /**
     * Display a file in a dedicated viewer page.
     */
    public function fileViewer(Request $request, $path)
    {
        // Allow access if authenticated as student or default user
        if (!auth('student')->check() && !auth()->check()) {
            return redirect()->route('login');
        }

        $disk = 'public'; // This is the tenant-specific disk due to Stancl tenancy
        if (!Storage::disk($disk)->exists($path)) {
            abort(404);
        }

        // Get file information
        $fileName = basename($path);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Create URLs for the file
        $fileUrl = url('/raw-file/' . $path); // We'll create this route
        $downloadUrl = route('activities.download-attachment', ['path' => $path]);
        $backUrl = url()->previous();

        // For text files, get the content
        $fileContent = '';
        $textExtensions = ['txt', 'html', 'htm', 'css', 'js', 'json', 'xml'];
        if (in_array($fileType, $textExtensions)) {
            $fileContent = Storage::disk($disk)->get($path);
        }

        // For Office documents, create a URL for Microsoft's Office Online Viewer
        $officeViewerUrl = null;
        $officeExtensions = ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];
        if (in_array($fileType, $officeExtensions)) {
            // Get the full URL to the file
            $fullFileUrl = url('/raw-file/' . $path);

            // Create the Office Online Viewer URL
            // This uses Microsoft's Office Online service to view Office documents
            $officeViewerUrl = 'https://view.officeapps.live.com/op/view.aspx?src=' . urlencode($fullFileUrl);

            // Log the URL for debugging
            Log::info('Office Viewer URL', [
                'url' => $officeViewerUrl,
                'fileUrl' => $fullFileUrl
            ]);
        }

        return view('app.file-viewer', compact(
            'fileName',
            'fileType',
            'fileUrl',
            'downloadUrl',
            'backUrl',
            'fileContent',
            'officeViewerUrl'
        ));
    }

    /**
     * Serve a raw file with appropriate headers.
     */
    public function rawFile(Request $request, $path)
    {
        // Allow access if authenticated as student or default user
        if (!auth('student')->check() && !auth()->check()) {
            return redirect()->route('login');
        }

        $disk = 'public'; // This is the tenant-specific disk due to Stancl tenancy
        if (!Storage::disk($disk)->exists($path)) {
            abort(404);
        }

        $filePath = Storage::disk($disk)->path($path);
        $fileName = basename($path);
        $mimeType = Storage::disk($disk)->mimeType($path);
        $fileSize = Storage::disk($disk)->size($path);
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Log information for debugging
        Log::info('Serving raw file', [
            'path' => $path,
            'filePath' => $filePath,
            'fileName' => $fileName,
            'mimeType' => $mimeType,
            'fileSize' => $fileSize,
            'exists' => file_exists($filePath)
        ]);

        // Log download attempts for tracking purposes
        if ($request->has('download') && auth('student')->check()) {
            $student = auth('student')->user();

            // Log the download
            Log::info('Student downloaded file via raw file', [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'path' => $path
            ]);
        }

        // For Office documents, we need to ensure they can be accessed by Microsoft's Office Online Viewer
        $officeExtensions = ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];

        if (in_array($extension, $officeExtensions)) {
            // For Office documents, we need to ensure CORS headers are set
            $headers = [
                'Content-Type' => $mimeType,
                'Content-Length' => $fileSize,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept',
                'Cache-Control' => 'public, max-age=3600'
            ];

            return response()->file($filePath, $headers);
        }

        // For PDF files, ensure proper headers for PDF.js
        if ($extension === 'pdf') {
            // For PDFs, we'll use a direct file content approach which works better with PDF.js
            $fileContent = file_get_contents($filePath);

            return response($fileContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Length' => $fileSize,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Accept-Ranges' => 'bytes',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Range',
                'Cache-Control' => 'public, max-age=3600',
            ]);
        }

        // For other file types, use standard response
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }
}
