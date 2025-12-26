<?php

namespace App\Http\Controllers;

use App\DataTables\GymClassDataTable;
use App\Models\Category;
use App\Models\GymClass;
use App\Models\Trainer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GymClassController extends Controller
{
    /**
     * Display a listing of classes
     */
    public function index(GymClassDataTable $dataTable)
    {
        $parentId = parentId();


        $categories = Category::where('parent_id', $parentId)->get();

        return $dataTable->render('gym-classes.index', compact( 'categories'));
    }

    /**
     * Show the form for creating a new class
     */
    public function create(): View
    {
        $parentId = parentId();
        $categories = Category::where('parent_id', $parentId)->active()->get();
        $trainers = Trainer::where('parent_id', $parentId)->active()->get();

        return view('gym-classes.create', compact('categories', 'trainers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_capacity' => 'required|integer|min:1|max:100',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive,cancelled',
            // Schedules
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.trainer_id' => 'nullable|exists:trainers,id',
            'schedules.*.room_location' => 'nullable|string|max:255',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('classes', 'public');
        }

        $validated['parent_id'] = parentId();

        // Create class
        $class = GymClass::create($validated);

        // Create schedules
        $scheduleInfo = 'No schedule';
        if ($request->has('schedules')) {
            $firstSchedule = $request->schedules[0] ?? null;
            if ($firstSchedule) {
                $trainer = $firstSchedule['trainer_id'] ? \App\Models\Trainer::find($firstSchedule['trainer_id']) : null;
                $scheduleInfo = ucfirst($firstSchedule['day_of_week']) . ' at ' . $firstSchedule['start_time'];
            }
            
            foreach ($request->schedules as $schedule) {
                $class->schedules()->create($schedule);
            }
        }

        // Send new class notification to all active members (optional - can be configured)
        // For now, we'll just log that a new class was created
        // You can extend this to send to all members if needed
        
        // Example: Send to trainer if assigned
        if ($request->has('schedules') && !empty($request->schedules)) {
            foreach ($request->schedules as $schedule) {
                if ($schedule['trainer_id'] ?? null) {
                    $trainer = \App\Models\Trainer::find($schedule['trainer_id']);
                    if ($trainer) {
                        sendNotificationEmail('new_class', $trainer->email, [
                            'gym_name' => settings('app_name', 'FitHub'),
                            'class_name' => $class->name,
                            'trainer_name' => $trainer->name,
                            'schedule_time' => $scheduleInfo,
                            'capacity' => $class->max_capacity,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('gym-classes.index')
            ->with('success', 'Class created successfully with ID: '.$class->class_id);
    }

    /**
     * Display the specified class
     */
    public function show(GymClass $gymClass): View
    {
        // Check multi-tenant isolation
        if ($gymClass->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $gymClass->load(['category', 'schedules.trainer', 'assigns.member']);

        return view('gym-classes.show', compact('gymClass'));
    }

    /**
     * Show the form for editing the specified class
     */
    public function edit(GymClass $gymClass): View
    {
        // Check multi-tenant isolation
        if ($gymClass->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $parentId = parentId();
        $categories = Category::where('parent_id', $parentId)->active()->get();
        $trainers = Trainer::where('parent_id', $parentId)->active()->get();
        $gymClass->load('schedules');

        return view('gym-classes.edit', compact('gymClass', 'categories', 'trainers'));
    }

    /**
     * Update the specified class
     */
    public function update(Request $request, GymClass $gymClass): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($gymClass->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_capacity' => 'required|integer|min:1|max:100',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive,cancelled',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($gymClass->image) {
                Storage::disk('public')->delete($gymClass->image);
            }
            $validated['image'] = $request->file('image')->store('classes', 'public');
        }

        $gymClass->update($validated);

        return redirect()->route('gym-classes.index')
            ->with('success', 'Class updated successfully');
    }

    /**
     * Remove the specified class
     */
    public function destroy(GymClass $gymClass)
    {
        // Check multi-tenant isolation
        if ($gymClass->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        // Delete image if exists
        if ($gymClass->image) {
            Storage::disk('public')->delete($gymClass->image);
        }

        $gymClass->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
