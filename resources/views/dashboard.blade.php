<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex space-x-3">
                <span class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 rounded-full text-sm font-medium shadow-sm">
                    <i class="fas fa-calendar-alt mr-1.5"></i>
                    {{ now()->format('F d, Y') }}
                </span>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all shadow-sm">
                    <i class="fas fa-sync-alt mr-1.5"></i>
                    Refresh
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="animated-gradient rounded-xl shadow-lg mb-6 overflow-hidden">
                <div class="px-6 py-8 md:px-10 md:py-12 flex flex-col md:flex-row items-center justify-between bg-black/20">
                    <div class="text-white mb-6 md:mb-0">
                        <h2 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h2>
                        <p class="text-white/80">Here's what's happening with your multi-tenant application today.</p>
                    </div>
                    <div class="flex space-x-3">
                        @if(!tenant())
                        <a href="{{ route('applications.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white/90 backdrop-blur-sm text-indigo-600 rounded-lg font-medium hover:bg-white transition-colors shadow-sm">
                            <i class="fas fa-file-alt mr-2"></i>
                            Applications
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                @php
                    $totalTenants = \App\Models\Tenant::count();
                    $activeTenants = \App\Models\Tenant::where('active', true)->count();

                    // Only query TenantApplication if not in tenant context
                    if (!tenant()) {
                        $pendingApplications = \App\Models\TenantApplication::where('status', 'pending')->count();
                        $totalApplications = \App\Models\TenantApplication::count();
                    } else {
                        $pendingApplications = 0;
                        $totalApplications = 0;
                    }
                @endphp

                <!-- Total Tenants -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                <i class="fas fa-building text-xl"></i>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Total Tenants</p>
                                <p class="text-gray-900 dark:text-gray-100 text-2xl font-semibold">{{ $totalTenants }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Tenants -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Active Tenants</p>
                                <p class="text-gray-900 dark:text-gray-100 text-2xl font-semibold">{{ $activeTenants }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Applications -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Pending Applications</p>
                                <p class="text-gray-900 dark:text-gray-100 text-2xl font-semibold">{{ $pendingApplications }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Applications -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                                <i class="fas fa-file-alt text-xl"></i>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Total Applications</p>
                                <p class="text-gray-900 dark:text-gray-100 text-2xl font-semibold">{{ $totalApplications }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Tenants -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 lg:col-span-2 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Tenants</h3>
                            <a href="{{ route('tenants.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline flex items-center">
                                View All
                                <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Domain</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach(\App\Models\Tenant::orderBy('created_at', 'desc')->take(5)->get() as $tenant)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $tenant->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                @if($tenant->domains->isNotEmpty())
                                                    <span class="flex items-center">
                                                        <i class="fas fa-globe text-blue-500 mr-1.5"></i>
                                                        {{ $tenant->domains->first()->domain }}
                                                    </span>
                                                @else
                                                    <span class="flex items-center text-red-500">
                                                        <i class="fas fa-exclamation-circle mr-1.5"></i>
                                                        No domain
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                @if($tenant->active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                        <i class="fas fa-check-circle mr-1"></i> Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                                        <i class="fas fa-times-circle mr-1"></i> Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                <span class="flex items-center">
                                                    <i class="fas fa-clock text-gray-400 mr-1.5"></i>
                                                    {{ $tenant->created_at->diffForHumans() }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if(\App\Models\Tenant::count() === 0)
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                                <i class="fas fa-building text-gray-300 dark:text-gray-600 text-4xl mb-3"></i>
                                                <p>No tenants found</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pending Applications -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pending Applications</h3>
                            @if(!tenant())
                            <a href="{{ route('applications.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline flex items-center">
                                View All
                                <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </a>
                            @endif
                        </div>

                        @if(!tenant() && $pendingApplications > 0)
                            <div class="space-y-4">
                                @foreach(\App\Models\TenantApplication::where('status', 'pending')->orderBy('created_at', 'desc')->take(3)->get() as $application)
                                    <div class="border border-gray-100 dark:border-gray-700 rounded-lg p-4 hover:shadow-sm transition-shadow bg-gray-50 dark:bg-gray-800/50">
                                        <div class="flex justify-between">
                                            <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $application->company_name }}</h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 flex items-center">
                                            <i class="fas fa-envelope text-gray-400 mr-1.5"></i>
                                            {{ $application->email }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center">
                                            <i class="fas fa-globe text-gray-400 mr-1.5"></i>
                                            {{ $application->domain_name }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center">
                                            <i class="fas fa-tag text-gray-400 mr-1.5"></i>
                                            @if(strtolower($application->subscription_plan) === 'premium')
                                                <span class="inline-flex items-center ml-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                                    <i class="fas fa-crown text-amber-500 mr-1"></i> Premium
                                                </span>
                                            @elseif(strtolower($application->subscription_plan) === 'pro')
                                                <span class="inline-flex items-center ml-1 px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                                    <i class="fas fa-star text-amber-500 mr-1"></i> Pro
                                                </span>
                                            @else
                                                <span class="inline-flex items-center ml-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                                    <i class="fas fa-check mr-1"></i> Basic
                                                </span>
                                            @endif
                                        </p>
                                        <div class="mt-3 flex space-x-2">
                                            <form method="POST" action="{{ route('applications.approve', $application->id) }}">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg text-xs hover:from-green-600 hover:to-emerald-600 shadow-sm transition-all">
                                                    <i class="fas fa-check mr-1.5"></i>
                                                    Approve
                                                </button>
                                            </form>
                                            <button type="button" onclick="confirmReject('{{ $application->id }}', '{{ $application->company_name }}')" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-500 to-rose-500 text-white rounded-lg text-xs hover:from-red-600 hover:to-rose-600 shadow-sm transition-all">
                                                <i class="fas fa-times mr-1.5"></i>
                                                Reject
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 px-4">
                                <i class="fas fa-file-alt text-gray-300 dark:text-gray-600 text-5xl mb-4"></i>
                                <p class="text-gray-500 dark:text-gray-400">
                                    @if(tenant())
                                        Applications are managed in the central dashboard
                                    @else
                                        No pending applications
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(!tenant())
                        <a href="{{ route('applications.index') }}" class="flex flex-col items-center p-5 bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-xl hover:shadow-md transition-all group border border-amber-100 dark:border-amber-800/30">
                            <div class="p-3 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 group-hover:bg-amber-200 dark:group-hover:bg-amber-800/40 transition-colors">
                                <i class="fas fa-file-alt text-xl"></i>
                            </div>
                            <span class="mt-3 text-sm font-medium text-gray-900 dark:text-gray-100">Applications</span>
                        </a>

                        <a href="{{ route('tenants.index') }}" class="flex flex-col items-center p-5 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl hover:shadow-md transition-all group border border-blue-100 dark:border-blue-800/30">
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/40 transition-colors">
                                <i class="fas fa-building text-xl"></i>
                            </div>
                            <span class="mt-3 text-sm font-medium text-gray-900 dark:text-gray-100">Manage Tenants</span>
                        </a>
                        @endif

                        <a href="#" class="flex flex-col items-center p-5 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl hover:shadow-md transition-all group border border-green-100 dark:border-green-800/30">
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 group-hover:bg-green-200 dark:group-hover:bg-green-800/40 transition-colors">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                            <span class="mt-3 text-sm font-medium text-gray-900 dark:text-gray-100">Reports</span>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center p-5 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl hover:shadow-md transition-all group border border-purple-100 dark:border-purple-800/30">
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/40 transition-colors">
                                <i class="fas fa-user-cog text-xl"></i>
                            </div>
                            <span class="mt-3 text-sm font-medium text-gray-900 dark:text-gray-100">Settings</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Reject Application</h3>
            <p class="mb-4 text-gray-700 dark:text-gray-300">Are you sure you want to reject the application for <span id="rejectApplicationName" class="font-semibold"></span>?</p>
            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for rejection</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Reject Application</button>
                </div>
            </form>
        </div>
    </div>

    <script>
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
