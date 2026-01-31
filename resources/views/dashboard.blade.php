<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WDS Dashboard | EARIST</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-slate-50 antialiased" x-data="{ activeTab: 'summary', editModal: false, item: {} }">
  <nav x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" class="bg-gradient-to-r from-red-700 to-red-800 text-white shadow-md no-print relative">
    <div class="max-w-[1400px] mx-auto px-6 py-3 flex justify-between items-center">
        
        <div class="flex items-center gap-3">
            <div class="h-10 w-1 bg-white/30 rounded-full hidden sm:block"></div> 
            <div class="flex flex-col">
                <span class="text-xl font-black tracking-tighter leading-none">EARIST</span>
                <span class="text-[10px] font-medium uppercase tracking-[0.2em] opacity-80 leading-tight">Web Development Services</span>
            </div>
        </div>

        <div class="hidden md:flex items-center gap-6">
            <a href="{{ route('posting-request-form') }}" target="_blank"
                class="text-[11px] bg-white/10 hover:bg-white/20 backdrop-blur-md px-4 py-2 border border-white/20 rounded-lg transition tracking-widest uppercase font-bold">
                Posting Request Module
            </a>
            
            <div class="relative">
                <button @click="userDropdownOpen = !userDropdownOpen" @click.away="userDropdownOpen = false" 
                    class="flex items-center gap-2 group outline-none">
                    <div class="h-9 w-9 rounded-full bg-white text-red-700 flex items-center justify-center font-bold text-sm shadow-sm group-hover:ring-4 group-hover:ring-white/20 transition-all">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <svg class="w-4 h-4 opacity-60 group-hover:opacity-100 transition-transform" :class="userDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="userDropdownOpen" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-[60]" x-cloak>
                    <div class="px-4 py-2 border-b border-slate-50 mb-1">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Account</p>
                        <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="md:hidden flex items-center">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileMenuOpen" x-transition class="md:hidden absolute top-full left-0 w-full bg-red-800 border-t border-red-600 shadow-2xl z-50" x-cloak>
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-3 pb-4 border-b border-white/10 text-white">
                <div class="h-10 w-10 rounded-full bg-white text-red-700 flex items-center justify-center font-bold">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <p class="font-bold">{{ Auth::user()->name }}</p>
            </div>
            <a href="{{ route('posting-request-form') }}" class="block w-full text-center text-sm bg-white/10 py-3 rounded-lg font-bold">POSTING REQUEST MODULE</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center py-2 text-sm font-bold text-red-200 uppercase tracking-widest">Logout</button>
            </form>
        </div>
    </div>
</nav>

    <main class="max-w-[1400px] mx-auto p-6">
        <div class="flex border-b border-slate-200 mb-8 gap-8 no-print">
            <button @click="activeTab = 'summary'"
                :class="activeTab === 'summary' ? 'border-red-600 text-red-600' : 'border-transparent text-slate-500'"
                class="pb-4 px-2 font-bold text-sm border-b-2 transition-all focus:outline-none">
                Overview Summary
            </button>
            <button @click="activeTab = 'records'"
                :class="activeTab === 'records' ? 'border-red-600 text-red-600' : 'border-transparent text-slate-500'"
                class="pb-4 px-2 font-bold text-sm border-b-2 transition-all focus:outline-none">
                Request Records
            </button>
            <button @click="activeTab = 'audit'"
                :class="activeTab === 'audit' ? 'border-red-600 text-red-600' : 'border-transparent text-slate-500'"
                class="pb-4 px-2 font-bold text-sm border-b-2 transition-all focus:outline-none">
                System Audit Trail
            </button>
        </div>

        <div x-show="activeTab === 'summary'" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Requests</p>
                    <h3 class="text-3xl font-black text-amber-500 mt-2">{{ $stats['pending'] }}</h3>
                    <div class="w-full bg-slate-100 h-2 mt-4 rounded-full overflow-hidden">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $stats['pending_percent'] }}%">
                        </div>
                    </div>
                    <p class="text-[10px] mt-2 text-slate-400 font-medium">{{ $stats['pending_percent'] }}% of total
                        volume</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Posted Documents</p>
                    <h3 class="text-3xl font-black text-emerald-500 mt-2">{{ $stats['posted'] }}</h3>
                    <div class="w-full bg-slate-100 h-2 mt-4 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $stats['posted_percent'] }}%">
                        </div>
                    </div>
                    <p class="text-[10px] mt-2 text-slate-400 font-medium">{{ $stats['posted_percent'] }}% completion
                        rate</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Records</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['total'] }}</h3>
                    <p class="text-[10px] mt-6 text-slate-400 font-medium tracking-tight italic">Includes archived
                        items: {{ $stats['archived'] }}</p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 mb-6">Performance Analytics</h4>
                <div class="h-[350px] w-full">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'records'" x-transition x-cloak>
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-800">Posting Request Records</h2>
                    <p class="text-slate-500 text-sm">Manage and track website update requests from different colleges.
                    </p>
                </div>
                <div class="w-full md:w-96">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" id="tableSearch" placeholder="Search department, personnel, or ctrl no..."
                            class="block w-full pl-10 pr-4 py-3 border-slate-200 rounded-xl focus:ring-red-500 focus:border-red-500 shadow-sm text-sm">
                    </div>
                </div>
            </div>
             


            
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-2xl overflow-hidden border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[1200px]" id="requestTable">
                        <thead>
                            <tr
                                class="bg-slate-50 border-b border-slate-100 text-xs uppercase text-slate-500 font-bold">
                                <th class="p-4">Control No.</th>
                                <th class="p-4">Origin</th>
                                <th class="p-4">Particulars</th>
                                <th class="p-4">Doc Title & No.</th>
                                <th class="p-4">Schedule</th>
                                <th class="p-4 text-center">Attachments</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4">Encoded By</th>
                                <th class="p-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            @foreach($requests as $item)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-4 font-mono font-bold text-red-700 tracking-tighter">{{ $item->ctrl_no }}
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-slate-800">{{ $item->department }}</div>
                                        <div class="text-xs text-slate-500">{{ $item->personnel }}</div>
                                    </td>
                                    <td class="p-4 max-w-xs text-slate-600 truncate" title="{{ $item->particulars }}">
                                        {{ $item->particulars }}</td>
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-800 leading-tight">{{ $item->doc_title }}</div>
                                        <div class="text-[10px] font-mono text-slate-400 mt-1 uppercase">
                                            {{ $item->doc_no ?? 'No Document No' }}</div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-slate-700 font-medium">
                                        {{ \Carbon\Carbon::parse($item->date_to_be_posted)->format('M d, Y') }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($item->attachment_link)
                                                <a href="{{ $item->attachment_link }}" target="_blank"
                                                    class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition"
                                                    title="Google Docs">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            @if($item->attachment_file)
                                                <a href="{{ asset('storage/' . $item->attachment_file) }}" target="_blank"
                                                    class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition"
                                                    title="Download File">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <form action="{{ route('posting-request.update-status', $item) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                class="text-[10px] font-black uppercase rounded-full px-4 py-1.5 border-none shadow-sm cursor-pointer transition-all
                                                    {{ $item->status == 'pending' ? 'bg-amber-100 text-amber-700 ring-1 ring-amber-200' : '' }}
                                                    {{ $item->status == 'posted' ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200' : '' }}
                                                    {{ $item->status == 'archived' ? 'bg-slate-200 text-slate-700 ring-1 ring-slate-300' : '' }}">
                                                <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="posted" {{ $item->status == 'posted' ? 'selected' : '' }}>
                                                    Posted</option>
                                                <option value="archived" {{ $item->status == 'archived' ? 'selected' : '' }}>
                                                    Archived</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-md text-xs font-medium">
                                            {{ $item->encoded_by ?? 'System' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('posting-request.pdf', $item->id) }}" target="_blank"
                                                class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200"
                                                title="Print PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <button @click="editModal = true; item = {{ json_encode($item) }}"
                                                class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition"
                                                title="Edit Request">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <form action="{{ route('posting-request.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100"
                                                    title="Delete Request">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

      <div x-show="activeTab === 'audit'" x-transition x-cloak>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">System Audit Trail</h2>
        <p class="text-slate-500 text-sm">Real-time log of administrative actions and record changes.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($activities as $log)
                <div class="p-4 md:p-5 flex flex-col md:flex-row md:items-center gap-5 hover:bg-slate-50/80 transition-all group">
                    
                    <div class="w-24 shrink-0 flex flex-col">
                        <span class="text-xs font-bold text-slate-700">{{ $log->created_at->format('M d, Y') }}</span>
                        <span class="text-[10px] font-medium text-slate-400 tabular-nums">{{ $log->created_at->format('h:i A') }}</span>
                    </div>

                    <div class="flex items-center gap-3 w-48 shrink-0">
                        <div class="h-8 w-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                            {{ substr($log->user->name ?? 'S', 0, 1) }}
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-xs font-bold text-slate-900 truncate">{{ $log->user->name ?? 'System' }}</span>
                            <span class="text-[10px] text-slate-400 leading-none">Administrator</span>
                        </div>
                    </div>

                    <div class="flex-1 flex items-center gap-3 text-sm">
                        <div class="shrink-0">
                            @if($log->action == 'Deleted Record')
                                <div class="h-2 w-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]"></div>
                            @elseif($log->action == 'Admin Entry')
                                <div class="h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                            @else
                                <div class="h-2 w-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"></div>
                            @endif
                        </div>

                        <p class="text-slate-600 leading-relaxed">
                            <span class="font-semibold text-slate-800">{{ $log->action }}</span> 
                            <span class="text-slate-400 mx-1">for</span>
                            <code class="text-[14px] font-mono font-bold px-1.5 py-0.5 bg-slate-100 text-red-700 rounded">
                                {{ $log->target_ctrl_no }}
                            </code>
                        </p>
                    </div>

                    <div class="shrink-0 opacity-0 group-hover:opacity-100 transition-opacity hidden md:block">
                        <span class="text-[10px] font-medium py-1 px-2 bg-slate-200/50 text-slate-500 rounded-md">
                            ID: #{{ $log->id }}
                        </span>
                    </div>

                </div>
            @empty
                <div class="p-20 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-slate-400 font-medium">No system activity recorded.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

        <div x-show="editModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" x-show="editModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="editModal" @click.away="editModal = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden border border-slate-200">

                    <div class="bg-red-700 p-4 text-white flex justify-between items-center">
                        <h3 class="font-bold uppercase tracking-tight">Edit Request Record</h3>
                        <button @click="editModal = false"
                            class="text-white/80 hover:text-white text-2xl">&times;</button>
                    </div>

                    <form :action="'/admin/request/' + item.id" method="POST" class="p-6 space-y-4">
                        @csrf @method('PUT')

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Office / College</label>
                                <input type="text" name="department" x-model="item.department"
                                    class="w-full border-slate-200 rounded-lg text-sm focus:ring-red-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Requested By</label>
                                <input type="text" name="personnel" x-model="item.personnel"
                                    class="w-full border-slate-200 rounded-lg text-sm focus:ring-red-500">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase">Doc Title</label>
                            <input type="text" name="doc_title" x-model="item.doc_title"
                                class="w-full border-slate-200 rounded-lg text-sm focus:ring-red-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase">Particulars</label>
                            <textarea name="particulars" x-model="item.particulars" rows="3"
                                class="w-full border-slate-200 rounded-lg text-sm focus:ring-red-500"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Posting Schedule</label>
                                <input type="date" name="date_to_be_posted" x-model="item.date_to_be_posted"
                                    class="w-full border-slate-200 rounded-lg text-sm focus:ring-red-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Status</label>
                                <select name="status" x-model="item.status"
                                    class="w-full border-slate-200 rounded-lg text-sm focus:ring-red-500">
                                    <option value="pending">Pending</option>
                                    <option value="posted">Posted</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>


                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="editModal = false"
                                class="flex-1 px-4 py-2 bg-slate-100 text-slate-600 rounded-lg font-bold hover:bg-slate-200 transition">Cancel</button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-red-700 text-white rounded-lg font-bold hover:bg-red-800 shadow-lg shadow-red-700/20 transition">Save
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Search Logic
        document.getElementById('tableSearch').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#requestTable tbody tr');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });

        // Chart.js Logic
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Pending Requests', 'Posted Requests', 'Archived Records'],
                    datasets: [{
                        label: 'Requests Count',
                        data: [{{ $stats['pending'] }}, {{ $stats['posted'] }}, {{ $stats['archived'] }}],
                        backgroundColor: ['#f59e0b', '#10b981', '#94a3b8'],
                        borderRadius: 12,
                        barThickness: 60
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                            grid: { color: '#f1f5f9' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</body>

</html>