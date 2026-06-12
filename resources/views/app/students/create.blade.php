<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add Student') }}
            </h2>
            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Back to Students
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Whoops!</strong> There were some problems with your input.<br><br>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Student ID -->
                        <div class="mb-4">
                            <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Student ID (Optional)</label>
                            <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('student_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profile Photo -->
                        <div class="mb-4">
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Photo (Optional)</label>
                            <input type="file" name="profile_photo" id="profile_photo" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                            @error('profile_photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Plan Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student Plan</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="relative border rounded-lg p-4 cursor-pointer hover:border-indigo-500 transition-colors {{ old('plan') == 'basic' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30' : 'border-gray-300 dark:border-gray-700' }}">
                                    <input type="radio" name="plan" id="plan_basic" value="basic" class="absolute h-4 w-4 top-4 right-4" {{ old('plan', 'basic') == 'basic' ? 'checked' : '' }} required>
                                    <label for="plan_basic" class="block cursor-pointer">
                                        <div class="font-medium text-gray-900 dark:text-white mb-1">Basic Plan</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>Access to assigned subjects</li>
                                                <li>Submit assignments</li>
                                                <li>View grades and feedback</li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>
                                <div class="relative border rounded-lg p-4 cursor-pointer hover:border-indigo-500 transition-colors {{ old('plan') == 'premium' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30' : 'border-gray-300 dark:border-gray-700' }}">
                                    <input type="radio" name="plan" id="plan_premium" value="premium" class="absolute h-4 w-4 top-4 right-4" {{ old('plan') == 'premium' ? 'checked' : '' }} required>
                                    <label for="plan_premium" class="block cursor-pointer">
                                        <div class="font-medium text-gray-900 dark:text-white mb-1">Premium Plan</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>All Basic Plan features</li>
                                                <li>Priority support</li>
                                                <li>Advanced analytics</li>
                                                <li>Additional learning resources</li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @error('plan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Credentials -->
                        <div class="mb-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="send_credentials" name="send_credentials" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('send_credentials') ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="send_credentials" class="font-medium text-gray-700 dark:text-gray-300">Send login credentials by email</label>
                                    <p class="text-gray-500 dark:text-gray-400">A random password will be generated and sent to the student's email address.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Assign to Subjects -->
                        <div class="mb-6">
                            <label for="subject_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign to Subjects (Optional)</label>
                            <select name="subject_ids[]" id="subject_ids" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" multiple>
                                @foreach(\App\Models\Subject::where('user_id', auth()->id())->orderBy('name')->get() as $subject)
                                    <option value="{{ $subject->id }}" {{ in_array($subject->id, old('subject_ids', [])) ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Hold Ctrl (or Cmd) to select multiple subjects</p>
                            @error('subject_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Add Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
