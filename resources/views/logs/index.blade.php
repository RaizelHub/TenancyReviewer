<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                <i class="fas fa-history text-lg"></i>
            </span>
            <div>
                <p class="text-sm font-medium text-emerald-700">Platform events</p>
                <h2 class="text-2xl font-semibold tracking-tight text-gray-900">Platform Audit Logs</h2>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl space-y-6">
        @if(!empty($analytics))
            <div class="app-surface p-6">
                <h3 class="font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-column text-emerald-600"></i> Event Distribution
                </h3>
                <div class="relative h-48">
                    <canvas id="logAnalyticsChart"></canvas>
                </div>
            </div>
        @endif

        <div class="app-surface overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-5">
                <h3 class="font-semibold text-gray-900">Audit Trail</h3>
                <p class="mt-1 text-sm text-gray-500">Track all super administrator actions and operations.</p>
            </div>

            @if($logs->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th class="px-6 py-3 font-semibold text-gray-500">Time</th>
                                <th class="px-6 py-3 font-semibold text-gray-500">Action</th>
                                <th class="px-6 py-3 font-semibold text-gray-500">Details</th>
                                <th class="px-6 py-3 font-semibold text-gray-500">Operator</th>
                                <th class="px-6 py-3 font-semibold text-gray-500">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($logs as $log)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-mono text-xs">
                                        {{ $log->created_at }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/10">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 max-w-xs sm:max-w-sm md:max-w-md truncate">
                                        {{ $log->description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                        {{ $log->user->name ?? 'System / Anonymous' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-mono text-xs">
                                        {{ $log->ip_address ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="px-6 py-14 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
                        <i class="fas fa-folder-open text-xl"></i>
                    </span>
                    <p class="mt-4 font-medium text-gray-900 font-semibold">No activity logs</p>
                    <p class="mt-1 text-sm text-gray-500">Events will appear here as administrators perform actions.</p>
                </div>
            @endif
        </div>
    </div>

    @if(!empty($analytics))
        @push('head')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @endpush

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctxLog = document.getElementById('logAnalyticsChart').getContext('2d');
                new Chart(ctxLog, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($analytics)) !!},
                        datasets: [{
                            label: 'Event frequency',
                            data: {!! json_encode(array_values($analytics)) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                            borderColor: '#10b981',
                            borderWidth: 1.5,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: { family: 'Inter, sans-serif' }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            y: {
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
    @endif
</x-app-layout>
