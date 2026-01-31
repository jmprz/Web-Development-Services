<?php

namespace App\Http\Controllers;

use App\Models\PostingRequest;
use App\Models\Activity;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Auth;

class PostingRequestController extends Controller
{
    public function index()
    {
        // Increased take to 20 so the separate tab feels full
        $activities = Activity::with('user')->latest()->take(20)->get();
        $requests = PostingRequest::latest()->get();

        $total = $requests->count();
        $pending = $requests->where('status', 'pending')->count();
        $posted = $requests->where('status', 'posted')->count();
        $archived = $requests->where('status', 'archived')->count();

        $stats = [
            'total' => $total,
            'pending' => $pending,
            'posted' => $posted,
            'archived' => $archived,
            'pending_percent' => $total > 0 ? round(($pending / $total) * 100) : 0,
            'posted_percent' => $total > 0 ? round(($posted / $total) * 100) : 0,
            'archived_percent' => $total > 0 ? round(($archived / $total) * 100) : 0,
        ];

        return view('dashboard', compact('requests', 'stats', 'activities'));
    }

    public function create()
    {
        return view('posting-request-form');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department' => 'required',
            'personnel' => 'required',
            'particulars' => 'required',
            'date_to_be_posted' => 'required|date',
            'doc_title' => 'required',
            'attachment_link' => 'nullable|url',
            'attachment_file' => 'nullable|mimes:jpg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('attachment_file')) {
            $file = $request->file('attachment_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['attachment_file'] = $file->storeAs('attachments', $fileName, 'public');
        }

        $lastRecord = PostingRequest::latest()->first();
        $nextId = $lastRecord ? $lastRecord->id + 1 : 1;
        $validated['ctrl_no'] = 'WDS-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        if ($request->filled('attachment_link') || $request->hasFile('attachment_file')) {
            $validated['doc_no'] = 'DOC-' . now()->format('ymd') . '-' . rand(100, 999);
        }

        if (Auth::check()) {
            $validated['encoded_by'] = Auth::user()->name;
        }

        $post = PostingRequest::create($validated);

        // LOG: Create
        Activity::create([
            'user_id' => auth()->id(),
            'action' => 'Admin Entry',
            'target_ctrl_no' => $post->ctrl_no,
            'details' => "Record manually created by " . auth()->user()->name,
        ]);

        return redirect()->back()->with(['success' => 'Created: ' . $post->ctrl_no]);
    }

    public function update(Request $request, $id)
    {
        $item = PostingRequest::findOrFail($id);
        $data = $request->all();

        if (Auth::check()) {
            $data['encoded_by'] = Auth::user()->name;
        }

        $item->update($data);

        // LOG: Update
        Activity::create([
            'user_id' => auth()->id(),
            'action' => 'Updated Record',
            'target_ctrl_no' => $item->ctrl_no,
            'details' => "Information updated by " . auth()->user()->name,
        ]);

        return redirect()->route('dashboard')->with('success', 'Updated successfully');
    }

    public function updateStatus(Request $request, PostingRequest $postingRequest)
    {
        $request->validate(['status' => 'required|in:pending,posted,archived']);

        $oldStatus = $postingRequest->status;
        $postingRequest->update(['status' => $request->status]);

        // LOG: Status Change
        Activity::create([
            'user_id' => auth()->id(),
            'action' => 'Status Changed',
            'target_ctrl_no' => $postingRequest->ctrl_no,
            'details' => "Status moved from $oldStatus to " . strtoupper($request->status),
        ]);

        return redirect()->back()->with('success', 'Status updated.');
    }

    public function destroy($id)
    {
        $item = PostingRequest::findOrFail($id);

        // LOG: Delete (Do this before deleting the actual record)
        Activity::create([
            'user_id' => auth()->id(),
            'action' => 'Deleted Record',
            'target_ctrl_no' => $item->ctrl_no,
            'details' => "Record deleted by " . auth()->user()->name,
        ]);

        $item->delete();
        return redirect()->back()->with('success', 'Deleted successfully');
    }

    public function downloadPdf(PostingRequest $postingRequest)
    {
        return Pdf::view('pdfs.request-slip', ['item' => $postingRequest])
            ->format('a4')
            ->name('EARIST-WDS-' . $postingRequest->ctrl_no . '.pdf');
    }

}