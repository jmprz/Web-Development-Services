<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Services Posting Request | EARIST</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
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
    <div class="min-h-screen flex flex-col items-center justify-center p-4 md:p-8">
        
        <div class="w-full max-w-4xl bg-white shadow-2xl shadow-slate-200 rounded-2xl overflow-hidden border border-slate-100">
            
            <div class="bg-gradient-to-r from-red-700 to-red-800 p-8 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight">EARIST</h1>
                        <p class="text-red-100 font-medium opacity-90">Web Development Services</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-lg px-4 py-2 border border-white/20">
                        <span class="text-xs uppercase tracking-widest font-bold">Posting Request Module</span>
                    </div>
                </div>
            </div>

            <div class="p-8 md:p-12">
                @if(session('success'))
                    <div class="mb-8 flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-sm animate-pulse">
                        <svg class="w-6 h-6 mr-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('public.request.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div class="space-y-6">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center">
                            <span class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center mr-3 text-sm">1</span>
                            Requester Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 ml-1">Department / College / Office</label>
                                <input type="text" name="department" required placeholder="e.g. College of Engineering"
                                    class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all duration-200 bg-slate-50">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 ml-1">Requesting Personnel</label>
                                <input type="text" name="personnel" required placeholder="Full Name"
                                    class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all duration-200 bg-slate-50">
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div class="space-y-6">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center">
                            <span class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center mr-3 text-sm">2</span>
                            Content & Schedule
                        </h2>
                        
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Title of Post</label>
                            <input type="text" name="doc_title" required placeholder="e.g. 2026 Student Election Results"
                                class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all duration-200 bg-slate-50">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Particulars (Details)</label>
                            <textarea name="particulars" rows="4" required placeholder="Provide a brief description or caption for the website..."
                                class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all duration-200 bg-slate-50"></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Target Posting Date</label>
                            <input type="date" name="date_to_be_posted" required
                                class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 bg-slate-50 cursor-pointer">
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div class="space-y-6">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center">
                            <span class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center mr-3 text-sm">3</span>
                            Attachments
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 ml-1">Media Upload (JPG, PNG, PDF)</label>
                                <div class="relative group">
                                    <input type="file" name="attachment_file"
                                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer bg-slate-50 rounded-xl border border-dashed border-slate-300 transition-all group-hover:border-red-300">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 ml-1">Google Docs Link (Optional)</label>
                                <input type="url" name="attachment_link" placeholder="https://docs.google.com/..."
                                    class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all duration-200 bg-slate-50 text-blue-600">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit"
                            class="w-full md:w-auto px-10 py-4 bg-red-700 text-white font-bold rounded-xl hover:bg-red-800 hover:shadow-xl hover:shadow-red-500/20 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            Submit Request
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <p class="mt-8 text-slate-400 text-sm font-medium tracking-wide">
            &copy; {{ date('Y') }} EARIST Web Development Services
        </p>
    </div>

    @if(session('recent_post'))
<div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all animate-in fade-in zoom-in duration-300">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-800 mb-2">Submission Successful!</h3>
            <p class="text-slate-500 mb-6">Your request has been logged.</p>
          
            <!--  Printing Button 
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 mb-8">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Control Number</span>
                <div class="text-3xl font-mono font-black text-red-700 mt-1">
                    {{ session('recent_post')->ctrl_no }}
                </div>
            </div>
<div class="flex flex-col sm:flex-row gap-3 mb-3">
    @if(session('recent_post'))
        <a href="{{ route('posting-request.pdf', session('recent_post')->id) }}" 
           target="_blank"
           class="flex-1 bg-slate-800 text-white font-bold py-4 rounded-xl hover:bg-slate-900 transition flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Print Request Document
        </a>
    @endif
</div>--> 

                <button onclick="document.getElementById('successModal').remove()" class="flex-1 bg-slate-100 text-slate-600 font-bold py-4 rounded-xl hover:bg-slate-200 transition w-full">
                    Done
                </button>
            </div>
        </div>
    </div>
</div>

   
@endif
</body>

</html>