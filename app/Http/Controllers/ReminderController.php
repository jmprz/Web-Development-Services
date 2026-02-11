<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'detail' => 'required',
            'deadline' => 'required|date',
        ]);

        Reminder::create($request->all());

        return back()->with('success', 'Reminder set successfully!');
    }

    public function update(Request $request, Reminder $reminder)
    {
        // Case 1: Updating via the Checkmark (Status Toggle)
    if ($request->has('status')) {
        $request->validate([
            'status' => 'required|string|in:pending,completed',
        ]);
        
        $reminder->update(['status' => $request->status]);
        return back()->with('success', 'Reminder status updated!');
    }

    // Case 2: Updating via the Modal (Edit Title/Detail/Date)
    $request->validate([
        'title' => 'required|string|max:255',
        'detail' => 'required',
        'deadline' => 'required|date',
    ]);

    $reminder->update([
        'title' => $request->title,
        'detail' => $request->detail,
        'deadline' => $request->deadline,
    ]);

    return back()->with('success', 'Reminder updated successfully!');
    }

    public function destroy(Reminder $reminder)
    {
        $reminder->delete();
        return back()->with('success', 'Reminder removed.');
    }
}
