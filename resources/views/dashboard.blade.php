<x-app-layout>
    @php
        $totalTenants = \App\Models\Tenant::count();
        $activeTenants = \App\Models\Tenant::where('active', true)->count();
        $pendingApplications = \App\Models\TenantApplication::where('status', 'pending')->count();
        $totalApplications = \App\Models\TenantApplication::count();
        $recentTenants = \App\Models\Tenant::with('domains')->latest()->take(5)->get();
        $recentApplications = \App\Models\TenantApplication::where('status', 'pending')->latest()->take(4)->get();

        // Calculate Plan Distribution from active tenants data
        $plans = ['Basic' => 0, 'Premium' => 0, 'Pro' => 0];
        $allTenantsList = \App\Models\Tenant::all();
        foreach ($allTenantsList as $t) {
            $pData = $t->data;
            $plan = 'Basic';
            if (is_array($pData) && isset($pData['subscription_plan'])) {
                $plan = $pData['subscription_plan'];
            } elseif (is_string($pData)) {
                $decoded = json_decode($pData, true);
                if (isset($decoded['subscription_plan'])) {
                    $plan = $decoded['subscription_plan'];
                }
            }
            $plan = ucfirst(strtolower($plan));
            if (isset($plans[$plan])) {
                $plans[$plan]++;
            } else {
                $plans['Basic']++;
            }
        }

        // Calculate Registration Timeline growth
        $growthRaw = [];
        foreach (\App\Models\Tenant::orderBy('created_at', 'asc')->get() as $t) {
            $day = $t->created_at->format('M d');
            $growthRaw[$day] = ($growthRaw[$day] ?? 0) + 1;
        }
        $cumulativeTotal = 0;
        $growthLabels = [];
        $growthValues = [];
        foreach ($growthRaw as $day => $count) {
            $cumulativeTotal += $count;
            $growthLabels[] = $day;
            $growthValues[] = $cumulativeTotal;
        }

        // Calculate System Health Diagnostics
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();
        $dbStatus = false;
        $dbPingTime = 0;
        try {
            $startTime = microtime(true);
            \DB::connection()->getPdo();
            $dbStatus = true;
            $dbPingTime = round((microtime(true) - $startTime) * 1000, 1);
        } catch (\Exception $e) {
            $dbStatus = false;
        }

        $diskTotal = @disk_total_space(base_path()) ?: 0;
        $diskFree = @disk_free_space(base_path()) ?: 0;
        $diskUsed = $diskTotal - $diskFree;
        $diskUsedPercent = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100) : 0;
        $diskTotalFormatted = $diskTotal > 0 ? round($diskTotal / (1024 * 1024 * 1024), 1) . ' GB' : 'N/A';
        $diskFreeFormatted = $diskFree > 0 ? round($diskFree / (1024 * 1024 * 1024), 1) . ' GB' : 'N/A';
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-700">System overview</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Good to see you, {{ Auth::user()->name }}.</h2>
                <p class="mt-1 text-sm text-gray-500">Manage your academy network, tenant requests, and platform health.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="app-btn-secondary self-start">
                <i class="fas fa-rotate-right"></i>Refresh data
            </a>
        </div>
    </x-slot>

    <div class="super-admin-dashboard mx-auto max-w-7xl">
        <section class="overflow-hidden rounded-2xl border border-emerald-800 bg-emerald-700 px-6 py-7 shadow-sm sm:px-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-emerald-50"><span class="h-1.5 w-1.5 rounded-full bg-emerald-200"></span>Platform operations</span>
                    <h3 class="mt-4 text-2xl font-semibold tracking-tight text-white">Your multi-tenant platform is ready to grow.</h3>
                    <p class="mt-2 text-sm leading-6 text-emerald-100">Review new academy applications, monitor active tenants, and keep daily operations moving from one place.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('applications.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-emerald-700 shadow-sm transition-colors hover:bg-emerald-50"><i class="fas fa-file-circle-check"></i>Review applications</a>
                    <a href="{{ route('tenants.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-white/10"><i class="fas fa-building"></i>Manage tenants</a>
                </div>
            </div>
        </section>

        <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Platform statistics">
            <article class="app-surface p-5">
                <div class="flex items-start justify-between">
                    <div><p class="text-sm font-medium text-gray-500">Total tenants</p><p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalTenants }}</p></div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fas fa-building text-lg"></i></span>
                </div>
                <a href="{{ route('tenants.index') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-emerald-700 hover:text-emerald-800">View tenants <i class="fas fa-arrow-right text-xs"></i></a>
            </article>
            <article class="app-surface p-5">
                <div class="flex items-start justify-between">
                    <div><p class="text-sm font-medium text-gray-500">Active tenants</p><p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $activeTenants }}</p></div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fas fa-circle-check text-lg"></i></span>
                </div>
                <p class="mt-4 text-sm text-gray-500">{{ $totalTenants ? number_format(($activeTenants / $totalTenants) * 100) : 0 }}% of all tenants are active</p>
            </article>
            <article class="app-surface p-5">
                <div class="flex items-start justify-between">
                    <div><p class="text-sm font-medium text-gray-500">Pending requests</p><p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $pendingApplications }}</p></div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-700"><i class="fas fa-clock text-lg"></i></span>
                </div>
                <a href="{{ route('applications.index') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-amber-700 hover:text-amber-800">Review queue <i class="fas fa-arrow-right text-xs"></i></a>
            </article>
            <article class="app-surface p-5">
                <div class="flex items-start justify-between">
                    <div><p class="text-sm font-medium text-gray-500">All applications</p><p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalApplications }}</p></div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-700"><i class="fas fa-file-lines text-lg"></i></span>
                </div>
                <p class="mt-4 text-sm text-gray-500">Applications received by the platform</p>
            </article>
        </section>

        <!-- Analytics & Charts Section -->
        <section class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Plan Distribution Chart -->
            <div class="app-surface p-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-emerald-600"></i> Plan Distribution
                    </h3>
                    <span class="text-xs text-gray-400 font-medium">Active Tenants</span>
                </div>
                <div class="relative h-64 flex justify-center items-center">
                    <canvas id="planChart"></canvas>
                </div>
            </div>
            <!-- Tenant Growth Chart -->
            <div class="app-surface p-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-chart-line text-emerald-600"></i> Registration Growth
                    </h3>
                    <span class="text-xs text-gray-400 font-medium">Cumulative Total</span>
                </div>
                <div class="relative h-64">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
            <!-- System Health Diagnostics -->
            <div class="app-surface p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-heartbeat text-emerald-600"></i> System Diagnostics
                        </h3>
                        <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700">Online</span>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 font-medium">PHP Version</span>
                            <span class="font-mono text-gray-900 font-bold bg-gray-100 px-2 py-0.5 rounded">{{ $phpVersion }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 font-medium">Laravel Version</span>
                            <span class="font-mono text-gray-900 font-bold bg-gray-100 px-2 py-0.5 rounded">{{ $laravelVersion }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 font-medium">Database Latency</span>
                            <span class="font-mono text-gray-900 font-bold {{ $dbStatus ? 'text-emerald-600 bg-emerald-50' : 'text-red-600 bg-red-50' }} px-2 py-0.5 rounded">
                                {{ $dbStatus ? $dbPingTime . ' ms' : 'Offline' }}
                            </span>
                        </div>
                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1.5">
                                <span>Storage Usage ({{ $diskFreeFormatted }} free)</span>
                                <span>{{ $diskUsedPercent }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $diskUsedPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-[10px] text-gray-400 text-center mt-6">
                    Last diagnosed: {{ now()->format('h:i:s A') }}
                </div>
            </div>
        </section>

        <section class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-5">
            <div class="app-surface overflow-hidden xl:col-span-3">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">
                    <div><h3 class="font-semibold text-gray-900">Recent tenants</h3><p class="mt-1 text-sm text-gray-500">Latest academy workspaces created on the platform.</p></div>
                    <a href="{{ route('tenants.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">View all</a>
                </div>
                @if($recentTenants->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead><tr><th class="px-6 py-3 font-semibold text-gray-500">Tenant</th><th class="px-6 py-3 font-semibold text-gray-500">Domain</th><th class="px-6 py-3 font-semibold text-gray-500">Status</th><th class="px-6 py-3 font-semibold text-gray-500">Created</th></tr></thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentTenants as $tenant)
                                    <tr>
                                        <td class="px-6 py-4"><div class="flex items-center gap-3"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-xs font-bold text-emerald-700">{{ strtoupper(substr($tenant->name, 0, 1)) }}</span><div><p class="font-medium text-gray-900">{{ $tenant->name }}</p><p class="text-xs text-gray-500">{{ $tenant->email }}</p></div></div></td>
                                        <td class="px-6 py-4 text-gray-600">{{ optional($tenant->domains->first())->domain ?? 'No domain' }}</td>
                                        <td class="px-6 py-4">@if($tenant->active)<span class="app-badge app-badge-success">Active</span>@else<span class="app-badge app-badge-danger">Inactive</span>@endif</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $tenant->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-14 text-center"><span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-400"><i class="fas fa-building"></i></span><p class="mt-4 font-medium text-gray-900">No tenants yet</p><p class="mt-1 text-sm text-gray-500">Approved applications will appear here.</p></div>
                @endif
            </div>

            <div class="app-surface xl:col-span-2">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5"><div><h3 class="font-semibold text-gray-900">Review queue</h3><p class="mt-1 text-sm text-gray-500">Applications awaiting a decision.</p></div><a href="{{ route('applications.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Open queue</a></div>
                @if($recentApplications->isNotEmpty())
                    <div class="divide-y divide-gray-100">
                        @foreach($recentApplications as $application)
                            <div class="px-6 py-4"><div class="flex items-start justify-between gap-3"><div class="min-w-0"><p class="truncate font-medium text-gray-900">{{ $application->company_name }}</p><p class="mt-1 truncate text-sm text-gray-500">{{ $application->email }}</p></div><span class="app-badge app-badge-warning">Pending</span></div><div class="mt-3 flex items-center justify-between"><span class="text-xs text-gray-500">{{ ucfirst($application->subscription_plan) }} plan</span><a href="{{ route('applications.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Review</a></div></div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-14 text-center"><span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fas fa-check"></i></span><p class="mt-4 font-medium text-gray-900">You’re all caught up</p><p class="mt-1 text-sm text-gray-500">There are no pending applications.</p></div>
                @endif
            </div>
        </section>
    </div>

    @push('head')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Plan Distribution Chart
            const ctxPlan = document.getElementById('planChart').getContext('2d');
            new Chart(ctxPlan, {
                type: 'doughnut',
                data: {
                    labels: ['Basic', 'Premium', 'Pro'],
                    datasets: [{
                        data: [{{ $plans['Basic'] }}, {{ $plans['Premium'] }}, {{ $plans['Pro'] }}],
                        backgroundColor: ['#3b82f6', '#8b5cf6', '#10b981'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 16,
                                font: { family: 'Inter, sans-serif', size: 12 }
                            }
                        }
                    }
                }
            });

            // Growth Chart
            const ctxGrowth = document.getElementById('growthChart').getContext('2d');
            new Chart(ctxGrowth, {
                type: 'line',
                data: {
                    labels: {!! json_encode($growthLabels) !!},
                    datasets: [{
                        label: 'Tenant registrations',
                        data: {!! json_encode($growthValues) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { family: 'Inter, sans-serif' }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { family: 'Inter, sans-serif' }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
 