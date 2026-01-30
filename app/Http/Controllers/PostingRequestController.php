<?php

namespace App\Http\Controllers;

use App\Models\PostingRequest;
use Illuminate\Http\Request;

use Spatie\LaravelPdf\Facades\Pdf;



class PostingRequestController extends Controller
{
public function index()
{
    $requests = PostingRequest::latest()->get();
    
    $total = $requests->count();
    $pending = $requests->where('status', 'pending')->count();
    $posted = $requests->where('status', 'posted')->count();
    $archived = $requests->where('status', 'archived')->count();

    // Calculate percentages safely
    $stats = [
        'total' => $total,
        'pending' => $pending,
        'posted' => $posted,
        'archived' => $archived,
        'pending_percent' => $total > 0 ? round(($pending / $total) * 100) : 0,
        'posted_percent' => $total > 0 ? round(($posted / $total) * 100) : 0,
        'archived_percent' => $total > 0 ? round(($archived / $total) * 100) : 0,
    ];

    return view('dashboard', compact('requests', 'stats'));
}
    public function create()
{
    // This simply returns the public view
    return view('posting-request-form');
}

public function downloadPdf(PostingRequest $postingRequest)
{
    // Note: We pass $postingRequest as 'item' to match the PDF blade template
    return Pdf::view('pdfs.request-slip', ['item' => $postingRequest])
        ->format('a4')
        ->name('EARIST-WDS-' . $postingRequest->ctrl_no . '.pdf');
}

public function updateStatus(Request $request, PostingRequest $postingRequest)
{
    $request->validate([
        'status' => 'required|in:pending,posted,archived',
    ]);

    $postingRequest->update(['status' => $request->status]);

    return redirect()->back()->with('success', 'Status updated to ' . strtoupper($request->status));
}

    public function store(Request $request)
    {
        // 1. Validate - remove ctrl_no and doc_no from validation since we generate them
    $validated = $request->validate([
        'department' => 'required',
        'personnel' => 'required',
        'particulars' => 'required',
        'date_to_be_posted' => 'required|date',
        'doc_title' => 'required',
        'attachment_link' => 'nullable|url',
        'attachment_file' => 'nullable|mimes:jpg,png,pdf|max:2048',
    ]);

   // Handle File Upload
    if ($request->hasFile('attachment_file')) {
        $file = $request->file('attachment_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        // This saves to storage/app/public/attachments
        $filePath = $file->storeAs('attachments', $fileName, 'public');
        $validated['attachment_file'] = $filePath;
    }

    // Auto-generate Ctrl No
    $lastRecord = PostingRequest::latest()->first();
    $nextId = $lastRecord ? $lastRecord->id + 1 : 1;
    $validated['ctrl_no'] = 'WDS-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

    // Auto-generate Doc No (if link OR file is provided)
    if ($request->filled('attachment_link') || $request->hasFile('attachment_file')) {
        $validated['doc_no'] = 'DOC-' . now()->format('ymd') . '-' . rand(100, 999);
    }
    
    $post = PostingRequest::create($validated);

    // Pass the actual object back to the session
    return redirect()->back()->with([
        'success' => 'Request submitted successfully!',
        'recent_post' => $post
    ]);

    return redirect()->back()->with('success', 'Request recorded! Control No: ' . $validated['ctrl_no']);
    }
}
