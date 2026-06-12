<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-3">
                    <i class="fas fa-question-circle text-lg"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Quiz Management') }}
                </h2>
            </div>
            <a href="{{ route('teacher-quizzes.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-xl hover:from-blue-600 hover:to-indigo-600 shadow-sm hover:shadow transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Create New Quiz
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 p-4 mb-6 rounded-xl flex items-start shadow-sm" role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-xl"></i>
                    </div>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 p-4 mb-6 rounded-xl flex items-start shadow-sm" role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 text-xl"></i>
                    </div>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-3">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Quizzes</h3>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $quizzes->count() }} {{ Str::plural('quiz', $quizzes->count()) }} available
                    </div>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($quizzes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3.5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3.5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3.5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Questions</th>
                                        <th class="px-6 py-3.5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3.5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($quizzes as $quiz)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $quiz->title }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($quiz->description, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">{{ $quiz->activity->subject->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                                    <i class="fas fa-tasks text-xs mr-1.5 text-gray-400"></i>
                                                    {{ $quiz->activity->title }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                    <i class="fas fa-list-ul mr-1"></i>
                                                    {{ $quiz->questions->count() }} {{ Str::plural('question', $quiz->questions->count()) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($quiz->is_published)
                                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Published
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Draft
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-1">
                                                    <a href="{{ route('teacher-quizzes.show', $quiz->id) }}" class="p-2 text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-500 dark:hover:text-white rounded-lg transition-colors">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('teacher-quizzes.edit', $quiz->id) }}" class="p-2 text-amber-600 hover:text-white bg-amber-50 hover:bg-amber-600 dark:bg-amber-900/20 dark:text-amber-400 dark:hover:bg-amber-500 dark:hover:text-white rounded-lg transition-colors">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <form action="{{ route('teacher-quizzes.toggle-published', $quiz->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="p-2 {{ $quiz->is_published ? 'text-gray-600 hover:text-white bg-gray-50 hover:bg-gray-600 dark:bg-gray-900/20 dark:text-gray-400 dark:hover:bg-gray-500 dark:hover:text-white' : 'text-green-600 hover:text-white bg-green-50 hover:bg-green-600 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-500 dark:hover:text-white' }} rounded-lg transition-colors">
                                                            @if($quiz->is_published)
                                                                <i class="fas fa-eye-slash"></i>
                                                            @else
                                                                <i class="fas fa-check"></i>
                                                            @endif
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('teacher-quizzes.destroy', $quiz->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-red-600 hover:text-white bg-red-50 hover:bg-red-600 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white rounded-lg transition-colors">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 px-4 py-4 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">
                            {{ $quizzes->links() }}
                        </div>
                    @else
                        <div class="p-10 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 dark:text-blue-400 mb-4">
                                <i class="fas fa-question-circle text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">No quizzes found</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">Get started by creating your first quiz to test your students' knowledge.</p>
                            <div class="mt-6">
                                <a href="{{ route('teacher-quizzes.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-xl hover:from-blue-600 hover:to-indigo-600 shadow-sm hover:shadow transition-all duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create New Quiz
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
