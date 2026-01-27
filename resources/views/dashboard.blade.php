<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WDS Admin Dashboard | EARIST</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<style>
    @media print {
        @page {
            margin: 0;
        }

        body {
            margin: 1.6cm;
        }

        .hidden {
            display: block !important;
        }

        nav,
        button,
        form,
        .no-print {
            display: none !important;
        }
    }
</style>

<body class="bg-slate-50 antialiased">

    <nav class="bg-red-700 text-white shadow-md">
        <div class="max-w-[1400px] mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-bold tracking-tight">EARIST <span class="font-light opacity-75">WDS Admin
                        Dashboard</span>
                </h1>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('public.request.form') }}" target="_blank"
                    class="text-sm bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg transition">View Public Form</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold hover:underline">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-[1400px] mx-auto p-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800">Posting Request Records</h2>
                <p class="text-slate-500 text-sm">Manage and track website update requests from different colleges.</p>
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
                <table class="w-full text-left border-collapse min-w-[1100px]" id="requestTable">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase text-slate-500 font-bold">
                            <th class="p-4">Control No.</th>
                            <th class="p-4">Origin</th>
                            <th class="p-4">Particulars</th>
                            <th class="p-4">Doc Title & No.</th>
                            <th class="p-4">Schedule</th>
                            <th class="p-4 text-center">Attachments</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-50">
                        @foreach($requests as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-4 font-mono font-bold text-red-700 tracking-tighter">{{ $item->ctrl_no }}</td>

                                <td class="p-4">
                                    <div class="font-bold text-slate-800">{{ $item->department }}</div>
                                    <div class="text-xs text-slate-500">{{ $item->personnel }}</div>
                                </td>

                                <td class="p-4 max-w-xs text-slate-600 truncate" title="{{ $item->particulars }}">
                                    {{ $item->particulars }}
                                </td>

                                <td class="p-4">
                                    <div class="font-semibold text-slate-800 leading-tight">{{ $item->doc_title }}</div>
                                    <div class="text-[10px] font-mono text-slate-400 mt-1 uppercase">
                                        {{ $item->doc_no ?? 'No Document No' }}
                                    </div>
                                </td>

                                <td class="p-4 whitespace-nowrap text-slate-700 font-medium">
                                    {{ \Carbon\Carbon::parse($item->date_to_be_posted)->format('M d, Y') }}
                                </td>

                                <td class="p-4">
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
                                                title="Download Uploaded File">
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
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="text-[10px] font-black uppercase rounded-full px-4 py-1.5 border-none shadow-sm cursor-pointer transition-all
                                                {{ $item->status == 'pending' ? 'bg-amber-100 text-amber-700 ring-1 ring-amber-200' : '' }}
                                                {{ $item->status == 'posted' ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200' : '' }}
                                                {{ $item->status == 'archived' ? 'bg-slate-200 text-slate-700 ring-1 ring-slate-300' : '' }}">
                                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="posted" {{ $item->status == 'posted' ? 'selected' : '' }}>Posted
                                            </option>
                                            <option value="archived" {{ $item->status == 'archived' ? 'selected' : '' }}>
                                                Archived</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="p-4 text-center">
                                    <button onclick='printRequest({!! json_encode($item) !!})'
                                        class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition"
                                        title="Print Slip">
                                        <a href="{{ route('posting-request.pdf', $item->id) }}"
                                            class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition inline-block"
                                            target="_blank" title="Download PDF Slip">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                            </path>
                                        </svg>
                                        </a>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>

        document.getElementById('tableSearch').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#requestTable tbody tr');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    </script>
    <script>
        function printRequest(data) {
            // 1. Map the data to the print template fields
            document.getElementById('p-ctrl-no').innerText = data.ctrl_no;
            document.getElementById('p-dept').innerText = data.department;
            document.getElementById('p-person').innerText = data.personnel;
            document.getElementById('p-title').innerText = data.doc_title;
            document.getElementById('p-date').innerText = data.date_to_be_posted;
            document.getElementById('p-particulars').innerText = data.particulars;

            // 2. Capture the template HTML
            const printContents = document.getElementById('printSection').innerHTML;
            const originalContents = document.body.innerHTML;

            // 3. Swap and Print
            document.body.innerHTML = printContents;
            window.print();

            // 4. Restore original page
            document.body.innerHTML = originalContents;
            window.location.reload(); // Essential to keep JS working
        }
    </script>
</body>

</html>