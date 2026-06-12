@props(['activity' => null, 'subjects'])

<div class="space-y-6">
    <!-- Title -->
    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $activity?->title)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
    </div>

    <!-- Description -->
    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="4" required>{{ old('description', $activity?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <!-- Subject -->
    <div>
        <x-input-label for="subject_id" :value="__('Subject')" />
        <select id="subject_id" name="subject_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
            <option value="">Select a subject</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ old('subject_id', $activity?->subject_id) == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('subject_id')" />
    </div>

    <!-- Due Date -->
    <div>
        <x-input-label for="due_date" :value="__('Due Date')" />
        <x-text-input id="due_date" name="due_date" type="datetime-local" class="mt-1 block w-full" :value="old('due_date', $activity?->due_date?->format('Y-m-d\TH:i'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
    </div>

    <!-- Activity Document -->
    <div id="activityDocumentField" class="p-4 rounded-lg border">
        <x-input-label for="activity_document" :value="__('Activity Document (PDF, DOC, DOCX)')" />
        <input type="file" id="activity_document" name="activity_document" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx" />
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Maximum file size: 10MB
            <span class="material-note hidden text-green-600 dark:text-green-400 font-medium ml-2">
                <i class="fas fa-info-circle"></i> Important for material type activities
            </span>
        </p>
        @if($activity?->activity_document_path)
            <div class="mt-2">
                <a href="{{ Storage::url($activity->activity_document_path) }}" target="_blank" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    <i class="fas fa-file-alt mr-1"></i> Current document
                </a>
            </div>
        @endif
        <x-input-error class="mt-2" :messages="$errors->get('activity_document')" />
    </div>

    <!-- Reviewer Attachment (PDF only) -->
    <div id="reviewerAttachmentField" class="p-4 rounded-lg border">
        <x-input-label for="reviewer_attachment" :value="__('Reviewer Attachment (PDF only)')" />
        <input type="file" id="reviewer_attachment" name="reviewer_attachment" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf" />
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Maximum file size: 10MB
            <span class="material-note hidden text-green-600 dark:text-green-400 font-medium ml-2">
                <i class="fas fa-info-circle"></i> Useful for reference materials
            </span>
        </p>
        @if($activity?->reviewer_attachment_path)
            <div class="mt-2">
                <a href="{{ Storage::url($activity->reviewer_attachment_path) }}" target="_blank" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    <i class="fas fa-file-pdf mr-1"></i> Current reviewer attachment
                </a>
            </div>
        @endif
        <x-input-error class="mt-2" :messages="$errors->get('reviewer_attachment')" />
    </div>

    <!-- Google Docs URL -->
    <div>
        <x-input-label for="google_docs_url" :value="__('Google Docs URL (Optional)')" />
        <x-text-input id="google_docs_url" name="google_docs_url" type="url" class="mt-1 block w-full" :value="old('google_docs_url', $activity?->google_docs_url)" placeholder="https://docs.google.com/document/d/..." />
        <x-input-error class="mt-2" :messages="$errors->get('google_docs_url')" />
    </div>
</div>