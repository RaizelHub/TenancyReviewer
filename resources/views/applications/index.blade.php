<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 mr-3">
                    <i class="fas fa-file-alt text-lg"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Tenant Applications') }}
                </h2>
            </div>
            <a href="{{ route('applications.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl hover:from-indigo-600 hover:to-purple-600 shadow-sm hover:shadow transition-all duration-200">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 p-4 rounded-xl shadow-sm flex items-start" role="alert">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 p-4 rounded-xl shadow-sm flex items-start" role="alert">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-4 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchInput" placeholder="Search applications..." class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg leading-5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="statusFilter" class="block w-full pl-3 pr-10 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <select id="planFilter" class="block w-full pl-3 pr-10 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Plans</option>
                                <option value="basic">Basic</option>
                                <option value="premium">Premium</option>
                                <option value="pro">Pro</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($applications->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Company/Contact</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Plan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($applications as $application)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                                        <span class="font-medium text-sm">{{ strtoupper(substr($application->company_name ?? $application->full_name, 0, 1)) }}</span>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $application->company_name }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $application->full_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-600 dark:text-gray-300 flex items-center">
                                                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                                    {{ $application->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                                    @if($application->subscription_plan === 'Premium' || $application->subscription_plan === 'premium')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                                            <i class="fas fa-crown text-amber-500 mr-1.5"></i> Premium
                                                        </span>
                                                    @elseif($application->subscription_plan === 'Pro' || $application->subscription_plan === 'pro')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                                            <i class="fas fa-star text-amber-500 mr-1.5"></i> Pro
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                                            <i class="fas fa-check mr-1.5"></i> Basic
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($application->status === 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                                                        <i class="fas fa-clock mr-1.5"></i> Pending
                                                    </span>
                                                @elseif($application->status === 'approved')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                        <i class="fas fa-check-circle mr-1.5"></i> Approved
                                                    </span>
                                                @elseif($application->status === 'rejected')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                                        <i class="fas fa-times-circle mr-1.5"></i> Rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    @if($application->status === 'pending')
                                                        <button type="button" onclick="confirmApprove('{{ $application->id }}', '{{ $application->company_name }}')" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg text-xs hover:from-green-600 hover:to-emerald-600 shadow-sm transition-all">
                                                            <i class="fas fa-check mr-1.5"></i>
                                                            Approve
                                                        </button>
                                                        <button type="button" onclick="confirmReject('{{ $application->id }}', '{{ $application->company_name }}')" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-500 to-rose-500 text-white rounded-lg text-xs hover:from-red-600 hover:to-rose-600 shadow-sm transition-all">
                                                            <i class="fas fa-times mr-1.5"></i>
                                                            Reject
                                                        </button>
                                                    @else
                                                        <span class="text-gray-400 italic flex items-center">
                                                            <i class="fas fa-calendar-check mr-1.5"></i>
                                                            {{ $application->reviewed_at ? 'Processed on ' . $application->reviewed_at->format('M d, Y') : 'Processed' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 px-4 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">
                            {{ $applications->links() }}
                        </div>
                    @else
                        <div class="text-center py-12 px-4">
                            <i class="fas fa-file-alt text-gray-300 dark:text-gray-600 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No applications found</h3>
                            <p class="text-gray-500 dark:text-gray-400">When tenants apply, their applications will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div x-data="{ show: false }" x-init="setTimeout(() => { show = true }, 100)"
            :class="{ 'opacity-0 scale-95': !show, 'opacity-100 scale-100': show }"
            class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl w-full max-w-md border border-gray-100 dark:border-gray-700 transform transition-all duration-300 ease-in-out">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-3">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Approve Application</h3>
            </div>
            <p class="mb-6 text-gray-700 dark:text-gray-300">Are you sure you want to approve the application for <span id="approveApplicationName" class="font-semibold text-indigo-600 dark:text-indigo-400"></span>? This will create a new tenant with their own database and domain.</p>
            <form id="approveForm" method="POST" action="">
                @csrf
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideApproveModal()" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg hover:from-green-600 hover:to-emerald-600 shadow-sm transition-all">
                        <i class="fas fa-check mr-1.5"></i> Approve Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div x-data="{ show: false }" x-init="setTimeout(() => { show = true }, 100)"
            :class="{ 'opacity-0 scale-95': !show, 'opacity-100 scale-100': show }"
            class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl w-full max-w-md border border-gray-100 dark:border-gray-700 transform transition-all duration-300 ease-in-out">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mr-3">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reject Application</h3>
            </div>
            <p class="mb-4 text-gray-700 dark:text-gray-300">Are you sure you want to reject the application for <span id="rejectApplicationName" class="font-semibold text-indigo-600 dark:text-indigo-400"></span>?</p>
            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for rejection</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" required placeholder="Please provide a reason for rejecting this application..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRejectModal()" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 text-white rounded-lg hover:from-red-600 hover:to-rose-600 shadow-sm transition-all">
                        <i class="fas fa-times mr-1.5"></i> Reject Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const planFilter = document.getElementById('planFilter');

            if (searchInput && statusFilter && planFilter) {
                const tableRows = document.querySelectorAll('tbody tr');

                const applyFilters = () => {
                    const searchTerm = searchInput.value.toLowerCase();
                    const statusValue = statusFilter.value.toLowerCase();
                    const planValue = planFilter.value.toLowerCase();

                    tableRows.forEach(row => {
                        const companyName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                        const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const plan = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                        const status = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                        const matchesSearch = companyName.includes(searchTerm) || email.includes(searchTerm);
                        const matchesStatus = statusValue === '' || status.includes(statusValue);

                        // Special handling for plan filtering
                        let matchesPlan = planValue === '';
                        if (planValue === 'basic' && plan.includes('basic')) {
                            matchesPlan = true;
                        } else if (planValue === 'premium' && plan.includes('premium')) {
                            matchesPlan = true;
                        } else if (planValue === 'pro' && plan.includes('pro')) {
                            matchesPlan = true;
                        }

                        if (matchesSearch && matchesStatus && matchesPlan) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                };

                searchInput.addEventListener('input', applyFilters);
                statusFilter.addEventListener('change', applyFilters);
                planFilter.addEventListener('change', applyFilters);
            }
        });

        function confirmApprove(applicationId, applicationName) {
            document.getElementById('approveApplicationName').textContent = applicationName;
            document.getElementById('approveForm').action = `/applications/${applicationId}/approve`;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function hideApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function confirmReject(applicationId, applicationName) {
            document.getElementById('rejectApplicationName').textContent = applicationName;
            document.getElementById('rejectForm').action = `/applications/${applicationId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
