<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 mr-3">
                    <i class="fas fa-building text-lg"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Tenant Management') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 p-4 rounded-xl shadow-sm flex items-start" role="alert">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3 text-lg"></i>
                    <div>
                        <p class="font-bold">Success!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-5 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-indigo-400"></i>
                            </div>
                            <input type="text" id="searchInput" placeholder="Search tenants by name, email or domain..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg leading-5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-filter text-indigo-400"></i>
                                </div>
                                <select id="statusFilter" class="block w-full pl-10 pr-10 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm appearance-none">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tenants Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tenants as $tenant)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="p-5 flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="h-14 w-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-medium shadow-md">
                                    <span class="text-lg font-bold">{{ substr($tenant->name, 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $tenant->name }}
                                </h3>
                                <div class="mt-1 flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                    <span class="truncate">{{ $tenant->email }}</span>
                                </div>
                                <div class="mt-1 flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-globe text-gray-400 mr-2"></i>
                                    <span class="truncate">{{ $tenant->domains->first()->domain ?? 'No domain' }}</span>
                                </div>
                                <div class="mt-1 flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                    <span>{{ $tenant->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="mt-3">
                                    @if($tenant->active)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                            <i class="fas fa-circle text-xs mr-1.5 mt-0.5"></i> Active
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                            <i class="fas fa-circle text-xs mr-1.5 mt-0.5"></i> Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/30 px-5 py-3 flex justify-between items-center">
                            <a href="{{ route('tenants.show', $tenant) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                View Details
                            </a>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('tenants.edit', $tenant) }}" class="p-1.5 rounded-lg text-amber-600 hover:text-white dark:text-amber-400 dark:hover:text-white hover:bg-amber-600 dark:hover:bg-amber-600 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($tenant->active)
                                    <button type="button" onclick="confirmDisable('{{ $tenant->id }}', '{{ $tenant->name }}')" class="p-1.5 rounded-lg text-orange-600 hover:text-white dark:text-orange-400 dark:hover:text-white hover:bg-orange-600 dark:hover:bg-orange-600 transition-colors" title="Disable">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    <form id="disable-form-{{ $tenant->id }}" action="{{ route('tenants.disable', $tenant) }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                @else
                                    <button type="button" onclick="confirmEnable('{{ $tenant->id }}', '{{ $tenant->name }}')" class="p-1.5 rounded-lg text-green-600 hover:text-white dark:text-green-400 dark:hover:text-white hover:bg-green-600 dark:hover:bg-green-600 transition-colors" title="Enable">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    <form id="enable-form-{{ $tenant->id }}" action="{{ route('tenants.enable', $tenant) }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                @endif
                               
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State (will only show if there are no tenants) -->
            @if($tenants->isEmpty())
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-10 text-center">
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-indigo-50 dark:bg-indigo-900/20 mb-6">
                        <i class="fas fa-building text-indigo-400 dark:text-indigo-300 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No tenants found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">Get started by creating your first tenant to manage domains and access.</p>
                    <a href="{{ route('tenants.create') }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl hover:from-indigo-600 hover:to-purple-600 shadow-md hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Create Your First Tenant
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal for Disable Confirmation -->
    <div id="disableModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 dark:bg-orange-900/30 mb-4">
                    <i class="fas fa-exclamation-triangle text-orange-600 dark:text-orange-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Disable Tenant</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6" id="disableModalText">
                    Are you sure you want to disable this tenant? Their domain will be inaccessible.
                </p>
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeModal('disableModal')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="button" id="confirmDisableBtn" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        Disable
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Enable Confirmation -->
    <div id="enableModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Enable Tenant</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6" id="enableModalText">
                    Are you sure you want to enable this tenant? Their domain will become accessible again.
                </p>
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeModal('enableModal')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="button" id="confirmEnableBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Enable
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');

            if (searchInput && statusFilter) {
                const tenantCards = document.querySelectorAll('.grid > div');

                const applyFilters = () => {
                    const searchTerm = searchInput.value.toLowerCase();
                    const statusValue = statusFilter.value.toLowerCase();

                    tenantCards.forEach(card => {
                        const companyName = card.querySelector('h3').textContent.toLowerCase();
                        const email = card.querySelector('.fa-envelope').parentNode.textContent.toLowerCase();
                        const domain = card.querySelector('.fa-globe').parentNode.textContent.toLowerCase();
                        const status = card.querySelector('.rounded-full').textContent.toLowerCase();

                        const matchesSearch = companyName.includes(searchTerm) ||
                                             email.includes(searchTerm) ||
                                             domain.includes(searchTerm);
                        const matchesStatus = statusValue === '' ||
                                             (statusValue === 'active' && status.includes('active')) ||
                                             (statusValue === 'inactive' && status.includes('inactive'));

                        if (matchesSearch && matchesStatus) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                };

                searchInput.addEventListener('input', applyFilters);
                statusFilter.addEventListener('change', applyFilters);
            }
        });

        // Modal functionality
        function confirmDisable(tenantId, tenantName) {
            document.getElementById('disableModalText').textContent = `Are you sure you want to disable ${tenantName}? Their domain will be inaccessible.`;
            document.getElementById('confirmDisableBtn').onclick = function() {
                document.getElementById(`disable-form-${tenantId}`).submit();
            };
            document.getElementById('disableModal').classList.remove('hidden');
        }

        function confirmEnable(tenantId, tenantName) {
            document.getElementById('enableModalText').textContent = `Are you sure you want to enable ${tenantName}? Their domain will become accessible again.`;
            document.getElementById('confirmEnableBtn').onclick = function() {
                document.getElementById(`enable-form-${tenantId}`).submit();
            };
            document.getElementById('enableModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            const disableModal = document.getElementById('disableModal');
            const enableModal = document.getElementById('enableModal');

            if (event.target === disableModal) {
                closeModal('disableModal');
            }

            if (event.target === enableModal) {
                closeModal('enableModal');
            }
        });
    </script>
</x-app-layout>