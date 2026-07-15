<x-app-layout>
    @php
        $domain = optional($tenant->domains->first())->domain;
        $port = request()->getPort();
        $isNonStandard = (request()->getScheme() === 'http' && $port != 80) || (request()->getScheme() === 'https' && $port != 443);
        $tenantUrl = $domain ? request()->getScheme() . '://' . $domain . ($isNonStandard ? ':' . $port : '') : null;
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-lg font-bold text-emerald-700">{{ strtoupper(substr($tenant->name, 0, 1)) }}</span>
                <div><p class="text-sm font-medium text-emerald-700">Tenant workspace</p><h2 class="text-2xl font-semibold tracking-tight text-gray-900">{{ $tenant->name }}</h2></div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('tenants.index') }}" class="app-btn-secondary"><i class="fas fa-arrow-left"></i>All tenants</a>
                <a href="{{ route('tenants.edit', $tenant) }}" class="app-btn-primary"><i class="fas fa-pen"></i>Edit tenant</a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl space-y-6">
        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-700 flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 flex items-center gap-3">
                <i class="fas fa-times-circle text-lg"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <section class="app-surface overflow-hidden">
            <div class="flex flex-col gap-5 border-b border-gray-200 px-6 py-6 sm:flex-row sm:items-center sm:justify-between">
                <div><h3 class="text-lg font-semibold text-gray-900">Tenant overview</h3><p class="mt-1 text-sm text-gray-500">Identity, access, and workspace status at a glance.</p></div>
                @if($tenant->active)<span class="app-badge app-badge-success"><i class="fas fa-check-circle mr-1.5"></i>Active tenant</span>@else<span class="app-badge app-badge-danger"><i class="fas fa-circle-xmark mr-1.5"></i>Inactive tenant</span>@endif
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-3">
                <section class="lg:col-span-2">
                    <h4 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Workspace information</h4>
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Company name</dt>
                            <dd class="mt-3 flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 font-bold text-emerald-700">{{ strtoupper(substr($tenant->name, 0, 2)) }}</span><span class="font-semibold text-gray-900">{{ $tenant->name }}</span></dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Contact email</dt>
                            <dd class="mt-3 flex items-center gap-2 text-sm font-medium text-gray-800"><i class="fas fa-envelope text-emerald-600"></i><span class="break-all">{{ $tenant->email }}</span></dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500 flex items-center justify-between">
                                <span>Workspace domain</span>
                                @if($tenantUrl)
                                    <form action="{{ route('tenants.check-domain', $tenant) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-emerald-600 hover:text-emerald-700 font-semibold bg-emerald-50 hover:bg-emerald-100/80 px-2 py-0.5 rounded-full transition-colors flex items-center gap-1">
                                            <i class="fas fa-circle-nodes text-[8px]"></i> Verify Status
                                        </button>
                                    </form>
                                @endif
                            </dt>
                            <dd class="mt-3 text-sm font-medium">@if($tenantUrl)<a href="{{ $tenantUrl }}" target="_blank" class="inline-flex items-center gap-2 break-all text-emerald-700 hover:text-emerald-800"><i class="fas fa-globe"></i>{{ $domain }}<i class="fas fa-arrow-up-right-from-square text-xs"></i></a>@else<span class="text-gray-500">No domain configured</span>@endif</dd>
                        </div>
                    </dl>

                    <h4 class="mt-8 mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 font-mono flex items-center gap-2">
                        <i class="fas fa-database text-gray-400"></i> Database details
                    </h4>
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Database name</dt>
                            <dd class="mt-3 flex items-center gap-2 text-sm font-mono font-medium text-gray-800">
                                <i class="fas fa-server text-emerald-600"></i>
                                <span>{{ $tenant->tenancy_db_name ?? 'tenant_' . $tenant->id }}</span>
                            </dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Connection status</dt>
                            <dd class="mt-3 flex items-center gap-2 text-sm font-medium text-gray-800">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <span class="text-emerald-700 font-semibold">Active & Connected</span>
                            </dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Database Driver</dt>
                            <dd class="mt-3 flex items-center gap-2 text-sm font-medium text-gray-800">
                                <i class="fas fa-bolt text-amber-500"></i>
                                <span>MySQL (PDO)</span>
                            </dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Charset & Engine</dt>
                            <dd class="mt-3 flex items-center gap-2 text-sm font-medium text-gray-800">
                                <i class="fas fa-microchip text-blue-500"></i>
                                <span>utf8mb4 / InnoDB</span>
                            </dd>
                        </div>
                    </dl>

                    <h4 class="mt-8 mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 font-mono flex items-center gap-2">
                        <i class="fas fa-chart-simple text-gray-400"></i> Resource usage
                    </h4>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-center">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Students</span>
                            <span class="text-2xl font-bold text-emerald-600 mt-2 block">{{ $stats['students'] ?? 0 }}</span>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-center">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Subjects</span>
                            <span class="text-2xl font-bold text-blue-600 mt-2 block">{{ $stats['subjects'] ?? 0 }}</span>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-center">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Quizzes</span>
                            <span class="text-2xl font-bold text-purple-600 mt-2 block">{{ $stats['quizzes'] ?? 0 }}</span>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-center">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Activities</span>
                            <span class="text-2xl font-bold text-amber-600 mt-2 block">{{ $stats['activities'] ?? 0 }}</span>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-center">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Users</span>
                            <span class="text-2xl font-bold text-slate-700 mt-2 block">{{ $stats['users'] ?? 0 }}</span>
                        </div>
                    </div>
                </section>

                <aside class="rounded-2xl border border-gray-200 bg-gray-50 p-5 self-start space-y-6">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Tenant actions</h4>
                        <p class="mt-1 text-sm leading-6 text-gray-500">Changes affect this workspace immediately.</p>
                        <div class="mt-4 space-y-3">
                            <a href="{{ route('tenants.edit', $tenant) }}" class="app-btn-secondary w-full justify-center"><i class="fas fa-pen"></i>Edit details</a>
                            @if($tenant->active)
                                <button type="button" onclick="confirmDisable('{{ $tenant->id }}', '{{ $tenant->name }}')" class="app-btn-danger w-full justify-center"><i class="fas fa-ban"></i>Disable tenant</button>
                                <form id="disable-form-{{ $tenant->id }}" action="{{ route('tenants.disable', $tenant) }}" method="POST" class="hidden">@csrf</form>
                            @else
                                <button type="button" onclick="confirmEnable('{{ $tenant->id }}', '{{ $tenant->name }}')" class="app-btn-primary w-full justify-center"><i class="fas fa-check-circle"></i>Enable tenant</button>
                                <form id="enable-form-{{ $tenant->id }}" action="{{ route('tenants.enable', $tenant) }}" method="POST" class="hidden">@csrf</form>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold text-gray-900">Database tools</h4>
                        <p class="mt-1 text-sm leading-6 text-gray-500">Manage database state and backups.</p>
                        <div class="mt-4 space-y-3">
                            <form action="{{ route('tenants.migrate', $tenant) }}" method="POST">
                                @csrf
                                <button type="submit" class="app-btn-secondary w-full justify-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-database text-emerald-600"></i> Run Migrations
                                </button>
                            </form>
                            <form action="{{ route('tenants.backup', $tenant) }}" method="POST">
                                @csrf
                                <button type="submit" class="app-btn-secondary w-full justify-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-download text-indigo-600"></i> Export SQL Backup
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>
            </div>

            <div class="grid grid-cols-1 border-t border-gray-200 sm:grid-cols-2">
                <div class="px-6 py-5"><p class="text-xs font-medium uppercase tracking-wider text-gray-500">Created</p><p class="mt-2 flex items-center gap-2 text-sm font-medium text-gray-800"><i class="fas fa-calendar-plus text-emerald-600"></i>{{ $tenant->created_at->format('F j, Y · g:i A') }}</p></div>
                <div class="border-t border-gray-200 px-6 py-5 sm:border-l sm:border-t-0"><p class="text-xs font-medium uppercase tracking-wider text-gray-500">Last updated</p><p class="mt-2 flex items-center gap-2 text-sm font-medium text-gray-800"><i class="fas fa-clock-rotate-left text-emerald-600"></i>{{ $tenant->updated_at->format('F j, Y · g:i A') }}</p></div>
            </div>
        </section>
    </div>

    <div id="disableModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-md">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600"><i class="fas fa-ban"></i></span>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Disable tenant?</h3><p id="disableModalText" class="mt-2 text-sm leading-6 text-gray-600">This workspace will no longer be accessible.</p>
            <div class="mt-6 flex justify-end gap-3"><button type="button" onclick="closeModal('disableModal')" class="app-btn-secondary">Cancel</button><button type="button" id="confirmDisableBtn" class="app-btn-danger">Disable tenant</button></div>
        </div>
    </div>
    <div id="enableModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-md">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fas fa-check"></i></span>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Enable tenant?</h3><p id="enableModalText" class="mt-2 text-sm leading-6 text-gray-600">This workspace will become accessible again.</p>
            <div class="mt-6 flex justify-end gap-3"><button type="button" onclick="closeModal('enableModal')" class="app-btn-secondary">Cancel</button><button type="button" id="confirmEnableBtn" class="app-btn-primary">Enable tenant</button></div>
        </div>
    </div>

    <script>
        function confirmDisable(tenantId, tenantName) {
            document.getElementById('disableModalText').textContent = `Disable ${tenantName}? Its domain will no longer be accessible.`;
            document.getElementById('confirmDisableBtn').onclick = () => document.getElementById(`disable-form-${tenantId}`).submit();
            document.getElementById('disableModal').classList.remove('hidden');
            document.getElementById('disableModal').classList.add('flex');
        }
        function confirmEnable(tenantId, tenantName) {
            document.getElementById('enableModalText').textContent = `Enable ${tenantName}? Its domain will become accessible again.`;
            document.getElementById('confirmEnableBtn').onclick = () => document.getElementById(`enable-form-${tenantId}`).submit();
            document.getElementById('enableModal').classList.remove('hidden');
            document.getElementById('enableModal').classList.add('flex');
        }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }
    </script>
</x-app-layout>
