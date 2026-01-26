<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Web Development Services - Posting Request Module') }}
        </h2>
    </x-slot>

    <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h3 class="font-bold text-lg text-gray-800">Posting Request Records</h3>
            <div class="flex gap-2 w-full md:w-auto">
                <input type="text" id="tableSearch" placeholder="Search department or ctrl no..."
                    class="text-sm border-gray-300 rounded-md shadow-sm w-full">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1100px]" id="requestTable">
                <thead>
                    <tr class="border-b bg-gray-50 text-xs uppercase text-gray-600 font-bold">
                        <th class="p-3">Control No.</th>
                        <th class="p-3">Origin</th>
                        <th class="p-3">Particulars</th>
                        <th class="p-3">Doc Title & No.</th>
                        <th class="p-3">Schedule</th>
                        <th class="p-3 text-center">Attachments</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @foreach($requests as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 font-mono font-bold text-red-700">{{ $item->ctrl_no }}</td>

                                    <td class="p-3">
                                        <div class="font-medium text-gray-900">{{ $item->department }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->personnel }}</div>
                                    </td>

                                    <td class="p-3 max-w-xs text-gray-600 truncate" title="{{ $item->particulars }}">
                                        {{ $item->particulars }}
                                    </td>

                                    <td class="p-3">
                                        <div class="font-medium text-gray-800">{{ $item->doc_title }}</div>
                                        <div class="text-xs font-mono text-gray-400">{{ $item->doc_no ?? 'N/A' }}</div>
                                    </td>

                                    <td class="p-3 whitespace-nowrap text-gray-700">
                                        {{ \Carbon\Carbon::parse($item->date_to_be_posted)->format('M d, Y') }}
                                    </td>

                                    <td class="p-3">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($item->attachment_link)
                                                <a href="{{ $item->attachment_link }}" target="_blank"
                                                    class="p-2 bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100"
                                                    title="Google Docs Link">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                                                    </svg>
                                                </a>
                                            @endif

                                            @if($item->attachment_file)
                                                <a href="{{ asset('storage/' . $item->attachment_file) }}" target="_blank"
                                                    class="p-2 bg-red-50 text-red-600 rounded-full hover:bg-red-100"
                                                    title="Download File">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <form action="{{ route('posting-request.update-status', $item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="text-xs font-bold uppercase rounded-full px-3 py-1 border-none
                        {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $item->status == 'posted' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $item->status == 'archived' ? 'bg-gray-100 text-gray-700' : '' }}">
                                                <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="posted" {{ $item->status == 'posted' ? 'selected' : '' }}>Posted
                                                </option>
                                                <option value="archived" {{ $item->status == 'archived' ? 'selected' : '' }}>Archived
                                                </option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>