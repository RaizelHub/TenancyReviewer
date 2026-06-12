<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-3">
                    <i class="fas fa-user-graduate text-lg"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Students') }}
                </h2>
            </div>
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 shadow-sm hover:shadow transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Add Student
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 p-4 mb-6 rounded-xl flex items-start" role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-xl"></i>
                    </div>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 p-4 mb-6 rounded-xl flex items-start" role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 text-xl"></i>
                    </div>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Subscription Plan Info -->
            <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="p-2 rounded-full {{ $currentPlan == 'Pro' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400' : ($currentPlan == 'Premium' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400') }} mr-3">
                            <i class="fas {{ $currentPlan == 'Pro' ? 'fa-crown' : ($currentPlan == 'Premium' ? 'fa-star' : 'fa-user') }} text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $currentPlan }} Plan</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $remainingStudents < PHP_INT_MAX ? "$remainingStudents of $maxStudents students remaining" : "Unlimited students" }}
                            </p>
                        </div>
                    </div>
                    @if($currentPlan != 'Pro')
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-lg hover:from-purple-600 hover:to-indigo-600 shadow-sm transition-all duration-200 text-sm">
                            <i class="fas fa-arrow-up mr-2"></i>
                            Upgrade Plan
                        </a>
                    @endif
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 mb-6 border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('students.index') }}">
                    <div class="flex flex-wrap gap-5">
                        <div class="w-full md:w-1/3">
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Search Students</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name or email" class="pl-10 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 w-full shadow-sm">
                            </div>
                        </div>
                        <div class="w-full md:w-1/3">
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Filter by Subject</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-book text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <select name="subject_id" id="subject_id" class="pl-10 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 w-full shadow-sm">
                                    <option value="">All Subjects</option>
                                    @foreach(\App\Models\Subject::where('user_id', auth()->id())->orderBy('name')->get() as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="w-full md:w-auto flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-xl hover:from-blue-600 hover:to-indigo-600 shadow-sm hover:shadow transition-all duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Students List -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                @if($students->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($students as $student)
                            <li class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-all duration-200">
                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-4">
                                            @if($student->profile_photo)
                                                <img src="{{ $student->profile_photo }}" alt="{{ $student->name }}" class="h-12 w-12 rounded-full object-cover border-2 border-white dark:border-gray-700 shadow-sm">
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-semibold text-lg shadow-sm border-2 border-white dark:border-gray-700">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white text-base">{{ $student->name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center">
                                                <i class="fas fa-envelope text-xs mr-1.5 text-gray-400 dark:text-gray-500"></i>
                                                {{ $student->email }}
                                            </p>
                                            @if($student->student_id)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-0.5">
                                                    <i class="fas fa-id-card text-xs mr-1.5 text-gray-400 dark:text-gray-500"></i>
                                                    ID: {{ $student->student_id }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="flex flex-col items-end mr-6">
                                            <span class="text-xs font-medium {{ $student->plan == 'premium' ? 'bg-gradient-to-r from-purple-500 to-indigo-500 text-white' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }} px-2.5 py-1 rounded-full mb-1.5 shadow-sm">
                                                @if($student->plan == 'premium')
                                                    <i class="fas fa-crown text-amber-300 mr-1"></i>
                                                @endif
                                                {{ ucfirst($student->plan) }}
                                            </span>
                                            <span class="text-xs bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 px-2.5 py-1 rounded-full flex items-center">
                                                <i class="fas fa-book text-gray-400 dark:text-gray-500 mr-1"></i>
                                                {{ $student->subjects->count() }} {{ Str::plural('subject', $student->subjects->count()) }}
                                            </span>
                                        </div>
                                        <div class="flex space-x-1">
                                            <a href="{{ route('students.show', $student->id) }}" class="p-2 text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-500 dark:hover:text-white rounded-lg transition-colors">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student->id) }}" class="p-2 text-amber-600 hover:text-white bg-amber-50 hover:bg-amber-600 dark:bg-amber-900/20 dark:text-amber-400 dark:hover:bg-amber-500 dark:hover:text-white rounded-lg transition-colors">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $students->links() }}
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 dark:text-blue-400 mb-4">
                            <i class="fas fa-user-graduate text-2xl"></i>
                        </div>
                        <h3 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">No students found</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">Add students to your classroom to start managing your courses and assignments.</p>
                        <div class="mt-6">
                            <a href="{{ route('students.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 shadow-sm hover:shadow transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Add Student
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-tenant-app-layout>
