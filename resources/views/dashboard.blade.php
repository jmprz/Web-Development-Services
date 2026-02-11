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

<body class="bg-slate-50 antialiased" 
      x-data="{ 
        activeTab: 'summary', 
        editModal: false, 
        reminderModal: false, 
        reminderData: { id: null, title: '', detail: '', deadline: '', mode: 'create' },
        urgentAlert: true, 
        item: {} 
      }">
       @php 
        $urgentReminder = $reminders
    ->filter(fn($r) => $r->status !== 'completed' && $r->days_remaining <= 3 && $r->days_remaining >= 0)
    ->sortBy('days_remaining')
    ->first();
    @endphp

    {{-- Urgent Alert Modal --}}
    @if($urgentReminder)
    <div x-show="urgentAlert" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl border-t-8 border-red-600">
            <div class="text-center">
                <div class="h-16 w-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h2 class="text-xl font-black text-slate-800 mb-2">Upcoming Deadline!</h2>
                <p class="text-slate-500 text-sm mb-6">
                    <strong>{{ $urgentReminder->title }}</strong> is due <span class="text-red-600 font-bold">{{ $urgentReminder->days_remaining == 0 ? 'today' : $urgentReminder->days_remaining . ' days' }}</span>.
                </p>
                <button @click="urgentAlert = false" class="w-full bg-slate-800 text-white font-bold py-3 rounded-xl hover:bg-slate-700 transition">Acknowledge</button>
            </div>
        </div>
    </div>
    @endif
  <nav x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" class="bg-gradient-to-r from-red-700 to-red-800 text-white shadow-md no-print relative">
    <div class="max-w-[1400px] mx-auto px-6 py-3 flex justify-between items-center">
        
        <div class="flex items-center gap-3">
            <div class="h-10 w-1 bg-white/30 rounded-full hidden sm:block"></div> 
            <div class="flex flex-col">
                <span class="text-xl font-black tracking-tighter leading-none">EARIST</span>
                <span class="text-[10px] font-medium uppercase tracking-[0.2em] opacity-80 leading-tight">Web Development Services</span>
            </div>
        </div>
@if($urgentReminder)
<div class="hidden lg:flex items-center gap-2 bg-black/20 px-3 py-1.5 rounded-full border border-white/10 animate-pulse">
    <div class="flex items-center gap-2">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
        </span>
        <span class="text-[10px] font-bold uppercase tracking-wider text-red-200">Deadline:</span>
    </div>
    <div class="text-[11px] font-mono font-black text-white" 
         x-data="timer('{{ $urgentReminder->deadline }}')" 
         x-init="init()">
        <span x-text="time.days">00</span>d : 
        <span x-text="time.hours">00</span>h : 
        <span x-text="time.minutes">00</span>m
    </div>
</div>
@endif
        <div class="hidden md:flex items-center gap-6">
            <button @click="reminderModal = true" class="text-[11px] bg-amber-500 hover:bg-amber-400 px-4 py-2 rounded-lg transition tracking-widest uppercase font-bold text-white shadow-lg shadow-amber-900/20">
                    + Set Reminder
                </button>
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
            <button @click="activeTab = 'reminders'" :class="activeTab === 'reminders' ? 'border-red-600 text-red-600' : 'border-transparent text-slate-500'" class="pb-4 px-2 font-bold text-sm border-b-2 transition-all">
                Reminders 
                @if($reminders->count() > 0)
                <span class="ml-2 bg-red-100 text-red-600 px-2 py-0.5 rounded-full text-[10px]">{{ $reminders->count() }}</span>
                @endif
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

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($reminders as $rem)
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all relative {{ $rem->status === 'completed' ? 'opacity-70 grayscale-[0.2]' : '' }}">
        
        <div class="flex justify-between items-start mb-4">
            <div class="h-10 w-10 rounded-xl {{ $rem->status === 'completed' ? 'bg-emerald-50 text-emerald-400' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="flex flex-col items-end gap-2">
                @if($rem->status !== 'completed')
                    <span class="text-[10px] font-black uppercase px-2 py-1 rounded {{ $rem->days_remaining <= 3 ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                        {{ $rem->days_remaining == 0 ? 'Due Today' : $rem->days_remaining . ' Days Remaining' }}
                    </span>
                @else
                    <span class="text-[10px] font-black uppercase px-2 py-1 rounded bg-emerald-100 text-emerald-700 flex items-center gap-1">Finished</span>
                @endif
            </div>
        </div>

        <h3 class="font-bold text-slate-800 text-lg leading-tight mb-2 {{ $rem->status === 'completed' ? 'line-through text-slate-400' : '' }}">{{ $rem->title }}</h3>
        <p class="text-sm text-slate-500 mb-6">{{ $rem->detail }}</p>

        <div class="pt-4 border-t border-slate-100">
            <div class="flex justify-between items-center mb-4">
                <span class="text-xs font-bold text-slate-400 italic">Due: {{ \Carbon\Carbon::parse($rem->deadline)->format('M d, Y') }}</span>
                
                {{-- Toggle Done/Undo --}}
                <form action="{{ route('reminders.update', $rem->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $rem->status === 'completed' ? 'pending' : 'completed' }}">
                    <button type="submit" class="flex items-center gap-1 text-[11px] font-black uppercase {{ $rem->status === 'completed' ? 'text-emerald-600' : 'text-slate-400' }}">
                        {{ $rem->status === 'completed' ? 'Undo' : 'Done' }}
                    </button>
                </form>
            </div>

            <div class="flex items-center gap-2">
                {{-- Edit Button: This populates the reminderData object --}}
                <button @click="reminderData = { id: {{ $rem->id }}, title: '{{ addslashes($rem->title) }}', detail: '{{ addslashes($rem->detail) }}', deadline: '{{ $rem->deadline }}', mode: 'edit' }; reminderModal = true;" 
                    class="flex-1 bg-slate-50 hover:bg-blue-50 text-slate-500 py-2 rounded-lg text-[10px] font-bold uppercase transition">
                    Edit
                </button>
                
                {{-- Delete Button --}}
                <form action="{{ route('reminders.destroy', $rem->id) }}" method="POST" onsubmit="return confirm('Delete this reminder?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-2 bg-slate-50 hover:bg-red-50 text-slate-400 hover:text-red-600 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
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
<div x-show="reminderModal" class="fixed inset-0 z-[70] overflow-y-auto" x-cloak>
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="reminderModal = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-200">
            
            <div class="p-6 text-white font-black uppercase tracking-widest transition-colors"
                 :class="reminderData.mode === 'edit' ? 'bg-blue-600' : 'bg-amber-500'"
                 x-text="reminderData.mode === 'edit' ? 'Edit Office Reminder' : 'Add Office Reminder'">
            </div>

           <form :action="reminderData.mode === 'edit' ? '{{ url('admin/reminders') }}/' + reminderData.id : '{{ route('reminders.store') }}'" 
      method="POST" 
      class="p-8 space-y-5">
                @csrf
                
                <template x-if="reminderData.mode === 'edit'">
                    @method('PATCH')
                </template>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase">Reminder Title</label>
                    <input type="text" name="title" x-model="reminderData.title" required placeholder="e.g. Dept B Letter Submission" class="w-full mt-1 border-slate-200 rounded-xl focus:ring-amber-500">
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase">Full Details</label>
                    <textarea name="detail" rows="3" x-model="reminderData.detail" required placeholder="Describe the task or requirement..." class="w-full mt-1 border-slate-200 rounded-xl focus:ring-amber-500"></textarea>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase">Deadline Schedule</label>
                    <input type="date" name="deadline" x-model="reminderData.deadline" required class="w-full mt-1 border-slate-200 rounded-xl focus:ring-amber-500">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="reminderModal = false" class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold">Cancel</button>
                    
                    <button type="submit" 
                            class="flex-1 py-3 text-white rounded-xl font-bold shadow-lg transition-colors"
                            :class="reminderData.mode === 'edit' ? 'bg-blue-600 shadow-blue-200' : 'bg-amber-500 shadow-amber-200'"
                            x-text="reminderData.mode === 'edit' ? 'Update Reminder' : 'Set Deadline'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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
    <script>
function timer(expiryDate) {
    return {
        expiry: new Date(expiryDate).getTime(),
        time: { days: '00', hours: '00', minutes: '00' },
        init() {
            this.update();
            setInterval(() => this.update(), 60000); // Update every minute
        },
        update() {
            let now = new Date().getTime();
            let distance = this.expiry - now;

            if (distance < 0) {
                this.time = { days: '0', hours: '0', minutes: '0' };
                return;
            }

            this.time.days = Math.floor(distance / (1000 * 60 * 60 * 24));
            this.time.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            this.time.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        }
    }
}
</script>
</body>

</html>