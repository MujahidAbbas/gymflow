<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LockerController extends Controller
{
    /**
     * Display a listing of lockers
     */
    public function index(Request $request): View
    {
        $parentId = parentId();

        $query = Locker::where('parent_id', $parentId)
            ->with('currentAssignment.member');

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $lockers = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => Locker::where('parent_id', $parentId)->count(),
            'available' => Locker::where('parent_id', $parentId)->available()->count(),
            'occupied' => Locker::where('parent_id', $parentId)->occupied()->count(),
        ];

        return view('lockers.index', compact('lockers', 'stats'));
    }

    /**
     * Show the form for creating a new locker
     */
    public function create(): View
    {
        return view('lockers.create');
    }

    /**
     * Store a newly created locker
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:available,occupied,maintenance',
            'monthly_fee' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['parent_id'] = parentId();

        $locker = Locker::create($validated);

        return redirect()->route('lockers.index')
            ->with('success', 'Locker created successfully with number: '.$locker->locker_number);
    }

    /**
     * Display the specified locker
     */
    public function show(Locker $locker): View
    {
        // Check multi-tenant isolation
        if ($locker->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $locker->load(['assignments.member' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('lockers.show', compact('locker'));
    }

    /**
     * Show the form for editing the specified locker
     */
    public function edit(Locker $locker): View
    {
        // Check multi-tenant isolation
        if ($locker->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $parentId = parentId();
        $members = Member::where('parent_id', $parentId)->get();

        return view('lockers.edit', compact('locker', 'members'));
    }

    /**
     * Update the specified locker
     */
    public function update(Request $request, Locker $locker): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($locker->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:available,occupied,maintenance',
            'monthly_fee' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $locker->update($validated);

        return redirect()->route('lockers.index')
            ->with('success', 'Locker updated successfully');
    }

    /**
     * Assign locker to a member
     */
    public function assign(Request $request, Locker $locker): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($locker->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'assigned_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:assigned_date',
            'notes' => 'nullable|string',
        ]);

        // Check if locker is already assigned
        $existingAssignment = $locker->assignments()->where('status', 'active')->first();
        if ($existingAssignment) {
            return back()->with('error', 'Locker is already assigned to another member!');
        }

        // Create assignment
        $assignment = $locker->assignments()->create([
            'member_id' => $validated['member_id'],
            'assigned_date' => $validated['assigned_date'],
            'expiry_date' => $validated['expiry_date'],
            'status' => 'active',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update locker status
        $locker->update(['status' => 'occupied']);

        // Send email to member
        $member = $assignment->member;
        sendNotificationEmail('locker_assign', $member->email, [
            'gym_name' => settings('app_name', 'FitHub'),
            'member_name' => $member->name,
            'locker_number' => $locker->locker_number,
            'start_date' => $assignment->assigned_date->format('M d, Y'),
            'expiry_date' => $assignment->expiry_date->format('M d, Y'),
        ]);

        return redirect()->route('lockers.show', $locker->id)
            ->with('success', 'Locker assigned successfully to ' . $member->name);
    }

    /**
     * Remove the specified locker
     */
    public function destroy(Locker $locker): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($locker->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        // Check if locker has active assignments
        if ($locker->assignments()->where('status', 'active')->count() > 0) {
            return back()->with('error', 'Cannot delete locker with active assignments');
        }

        $locker->delete();

        return redirect()->route('lockers.index')
            ->with('success', 'Locker deleted successfully');
    }
}
