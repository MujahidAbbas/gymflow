<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of email templates
     */
    public function index()
    {
        $notifications = Notification::where('parent_id', parentId())
            ->orderBy('module')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Show the form for editing the specified template
     */
    public function edit($id)
    {
        $notification = Notification::where('parent_id', parentId())
            ->findOrFail($id);

        return view('notifications.edit', compact('notification'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, $id)
    {
        $notification = Notification::where('parent_id', parentId())
            ->findOrFail($id);

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'enabled_email' => 'boolean',
            'enabled_web' => 'boolean',
        ]);

        $notification->update([
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'enabled_email' => $request->has('enabled_email'),
            'enabled_web' => $request->has('enabled_web'),
        ]);

        return redirect()->route('notifications.index')
            ->with('success', 'Email template updated successfully');
    }
}
