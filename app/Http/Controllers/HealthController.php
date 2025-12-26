<?php

namespace App\Http\Controllers;

use App\DataTables\HealthTrackingDataTable;
use App\Models\Health;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HealthController extends Controller
{
    /**
     * Display a listing of health measurements
     */
    public function index(HealthTrackingDataTable $dataTable)
    {
        $parentId = parentId();

        $members = Member::where('parent_id', $parentId)->get();

        return $dataTable->render('healths.index', compact( 'members'));
    }

    /**
     * Show the form for creating a new health measurement
     */
    public function create(): View
    {
        $parentId = parentId();
        $members = Member::where('parent_id', $parentId)->active()->get();
        $measurementTypes = Health::MEASUREMENT_TYPES;

        return view('healths.create', compact('members', 'measurementTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'measurement_date' => 'required|date',
            'measurements' => 'required|array',
            'measurements.*' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Remove null measurements
        $validated['measurements'] = array_filter($validated['measurements'], function ($value) {
            return $value !== null && $value !== '';
        });

        $validated['parent_id'] = parentId();

        $health = Health::create($validated);

        // Send email to member
        $member = $health->member;
        $measurements = $health->measurements;
        sendNotificationEmail('health_update', $member->email, [
            'gym_name' => settings('app_name', 'FitHub'),
            'member_name' => $member->name,
            'weight' => $measurements['weight'] ?? 'N/A',
            'bmi' => $health->bmi ?? 'N/A',
            'date' => $health->measurement_date->format('M d, Y'),
        ]);

        return redirect()->route('healths.index')
            ->with('success', 'Health measurement recorded successfully');
    }

    /**
     * Display the specified health measurement
     */
    public function show(Health $health): View
    {
        // Check multi-tenant isolation
        if ($health->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $health->load('member');

        return view('healths.show', compact('health'));
    }

    /**
     * Show the form for editing the specified health measurement
     */
    public function edit(Health $health): View
    {
        // Check multi-tenant isolation
        if ($health->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $parentId = parentId();
        $members = Member::where('parent_id', $parentId)->active()->get();
        $measurementTypes = Health::MEASUREMENT_TYPES;

        return view('healths.edit', compact('health', 'members', 'measurementTypes'));
    }

    /**
     * Update the specified health measurement
     */
    public function update(Request $request, Health $health): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($health->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'measurement_date' => 'required|date',
            'measurements' => 'required|array',
            'measurements.*' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Remove null measurements
        $validated['measurements'] = array_filter($validated['measurements'], function ($value) {
            return $value !== null && $value !== '';
        });

        $health->update($validated);

        return redirect()->route('healths.index')
            ->with('success', 'Health measurement updated successfully');
    }

    /**
     * Remove the specified health measurement
     */
    public function destroy(Health $health)
    {
        // Check multi-tenant isolation
        if ($health->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $health->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
