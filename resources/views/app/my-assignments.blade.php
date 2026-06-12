<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 mr-3">
                    <i class="fas fa-tasks"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('My Assignments') }}
                </h2>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                    <i class="fas fa-check-circle mr-1.5"></i> {{ $submissions->count() }} Submitted
                </span>
                @php
                    // Count truly pending assignments (not submitted yet)
                    $pendingCount = 0;
                    $pendingReviewCount = 0;

                    foreach($activeActivities as $activity) {
                        $hasSubmission = false;
                        foreach($submissions as $sub) {
                            if ($sub->activity_id == $activity->id && $sub->grade === null) {
                                $hasSubmission = true;
                                $pendingReviewCount++;
                                break;
                            }
                        }
                        if (!$hasSubmission) {
                            $pendingCount++;
                        }
                    }
                @endphp
                <span class="ml-2 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                    <i class="fas fa-hourglass-half mr-1.5"></i> {{ $pendingCount }} Pending
                </span>
                <span class="ml-2 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                    <i class="fas fa-clock mr-1.5"></i> {{ $pendingReviewCount }} Awaiting Review
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Active Assignments -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700 mb-8">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400 mr-3">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Active Assignments</h3>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $activeActivities->count() }} {{ Str::plural('assignment', $activeActivities->count()) }} pending
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                    @forelse($activeActivities as $activity)
                        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:border-yellow-200 dark:hover:border-yellow-900 hover-lift">
                            <div class="h-2 bg-gradient-to-r from-yellow-500 to-amber-500"></div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="flex items-center mb-2">
                                            <h4 class="font-semibold text-lg text-gray-900 dark:text-white group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors">{{ $activity->title }}</h4>
                                            <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                <i class="fas fa-clock mr-1"></i>
                                                Due: {{ $activity->due_date->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-2">
                                            <div class="p-1 rounded-md bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 mr-2">
                                                <i class="fas fa-book text-xs"></i>
                                            </div>
                                            <span>{{ $activity->subject->name }}</span>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3 mb-4">
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $activity->description }}</p>
                                        </div>
                                        @if($activity->attachment)
                                            <div class="mt-3 mb-4">
                                                @if(Auth::guard('student')->check())
                                                    <a href="{{ route('activities.download-attachment', ['path' => $activity->attachment]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-medium shadow-sm">
                                                        <i class="fas fa-paperclip mr-1.5"></i>
                                                        Download Instructions
                                                    </a>
                                                @else
                                                    <a href="{{ route('activities.download-attachment.tenant', ['path' => $activity->attachment]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-medium shadow-sm">
                                                        <i class="fas fa-paperclip mr-1.5"></i>
                                                        Download Instructions
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Submission Form or Status -->
                                @php
                                    $hasSubmission = false;
                                    $submission = null;

                                    // Check if this activity has a submission that's not graded yet
                                    foreach($submissions as $sub) {
                                        if ($sub->activity_id == $activity->id && $sub->grade === null) {
                                            $hasSubmission = true;
                                            $submission = $sub;
                                            break;
                                        }
                                    }
                                @endphp

                                @if($hasSubmission)
                                    <div class="bg-blue-50 dark:bg-blue-900/10 rounded-xl p-4 border border-blue-100 dark:border-blue-900/30">
                                        <h5 class="font-medium text-blue-800 dark:text-blue-300 mb-3 flex items-center">
                                            <i class="fas fa-hourglass-half mr-2"></i> Submission Pending Review
                                        </h5>
                                        <div class="flex items-center mb-3">
                                            <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 mr-3">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ basename($submission->file_path) }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Your submission has been received and is waiting to be graded by your teacher.</p>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 dark:bg-yellow-900/10 rounded-xl p-4 border border-yellow-100 dark:border-yellow-900/30">
                                        <h5 class="font-medium text-yellow-800 dark:text-yellow-300 mb-3 flex items-center">
                                            <i class="fas fa-upload mr-2"></i> Submit Your Work
                                        </h5>
                                        <form action="{{ route('submissions.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                                            <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                                                <div class="flex-1 mb-3 md:mb-0">
                                                    <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-yellow-100 file:text-yellow-700 hover:file:bg-yellow-200 dark:file:bg-yellow-900/30 dark:file:text-yellow-300 dark:hover:file:bg-yellow-900/40" required>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload any file type (max 20MB)</p>
                                                </div>
                                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-lg hover:from-yellow-600 hover:to-amber-600 transition-all duration-200 text-sm font-medium shadow-sm">
                                                    <i class="fas fa-paper-plane mr-1.5"></i>
                                                    Submit Assignment
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 text-right">
                                    <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center justify-end">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        Posted {{ $activity->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-50 dark:bg-yellow-900/20 text-yellow-500 dark:text-yellow-400 mb-4">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No active assignments</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">You don't have any pending assignments at the moment. Great job!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Submitted Assignments -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 mr-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Submitted Assignments</h3>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $submissions->count() }} {{ Str::plural('submission', $submissions->count()) }}
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        @forelse($submissions as $submission)
                            <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:border-green-200 dark:hover:border-green-900 hover-lift">
                                <div class="h-2 bg-gradient-to-r from-green-500 to-emerald-500"></div>
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <div class="flex items-center mb-2">
                                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">{{ $submission->activity->title }}</h4>
                                                @if($submission->grade)
                                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                        <i class="fas fa-star text-amber-500 mr-1"></i>
                                                        Grade: {{ $submission->grade }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                <div class="p-1 rounded-md bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 mr-2">
                                                    <i class="fas fa-book text-xs"></i>
                                                </div>
                                                <span>{{ $submission->activity->subject->name }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                <span>Submitted: {{ $submission->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3 mb-4">
                                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $submission->activity->description }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($submission->feedback)
                                        <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/10 rounded-lg border border-green-100 dark:border-green-900/30">
                                            <h5 class="font-medium text-green-800 dark:text-green-300 mb-2 flex items-center">
                                                <i class="fas fa-comment-dots mr-2"></i> Teacher Feedback
                                            </h5>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $submission->feedback }}</p>
                                        </div>
                                    @endif

                                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-wrap justify-between items-center">
                                        <div class="flex items-center mb-3 md:mb-0">
                                            <div class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 mr-2">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ basename($submission->file_path) }}</span>
                                        </div>
                                        <div class="flex space-x-2">
                                            @php
                                                $extension = pathinfo($submission->file_path, PATHINFO_EXTENSION);
                                                $isPdf = strtolower($extension) === 'pdf';
                                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            @endphp

                                            @if($isPdf || $isImage)
                                                <button onclick="openFileViewer('{{ Storage::url($submission->file_path) }}', '{{ $isPdf ? 'pdf' : 'image' }}')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-medium shadow-sm">
                                                    <i class="fas fa-eye mr-1.5"></i>
                                                    Preview
                                                </button>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-50 dark:bg-green-900/20 text-green-500 dark:text-green-400 mb-4">
                                    <i class="fas fa-file-upload text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No submissions yet</h3>
                                <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">You haven't submitted any assignments yet. Complete your active assignments to see them here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Viewer Modal -->
    <div id="pdfViewerModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-11/12 max-w-5xl h-5/6 flex flex-col">
            <div class="flex justify-between items-center p-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                <div class="flex items-center">
                    <div id="viewerIcon" class="p-2 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-3">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h3 id="viewerTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Document Preview</h3>
                </div>
                <button onclick="closePdfViewer()" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex-1 p-6 overflow-auto bg-gray-50 dark:bg-gray-900/30">
                <iframe id="pdfViewer" class="w-full h-full rounded-lg shadow-md" frameborder="0"></iframe>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <button onclick="closePdfViewer()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                    <i class="fas fa-times mr-1.5"></i>
                    Close Preview
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openFileViewer(url, type) {
            const modal = document.getElementById('pdfViewerModal');
            const viewerTitle = document.getElementById('viewerTitle');
            const viewerIcon = document.getElementById('viewerIcon').querySelector('i');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Add animation
            modal.animate([
                { opacity: 0 },
                { opacity: 1 }
            ], { duration: 200, easing: 'ease-out' });

            // Update modal title and icon based on file type
            if (type === 'pdf') {
                viewerTitle.textContent = 'PDF Document Preview';
                viewerIcon.className = 'fas fa-file-pdf';
            } else {
                viewerTitle.textContent = 'Image Preview';
                viewerIcon.className = 'fas fa-image';
            }

            // Set the iframe source
            document.getElementById('pdfViewer').src = url;
        }

        function closePdfViewer() {
            const modal = document.getElementById('pdfViewerModal');

            // Add fade-out animation
            const animation = modal.animate([
                { opacity: 1 },
                { opacity: 0 }
            ], { duration: 200, easing: 'ease-in' });

            animation.onfinish = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.getElementById('pdfViewer').src = '';
            };
        }
    </script>
    @endpush
</x-tenant-app-layout>