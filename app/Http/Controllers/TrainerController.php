<?php

namespace App\Http\Controllers;

use App\DataTables\TrainerDataTable;
use App\Http\Requests\TrainerStoreRequest;
use App\Http\Requests\TrainerUpdateRequest;
use App\Models\Trainer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TrainerController extends Controller
{
    /**
     * Display a listing of trainers
     */
    public function index(TrainerDataTable $dataTable)
    {

        return $dataTable->render('trainers.index');
//        return view('trainers.index', compact('trainers'));
    }

    /**
     * Show the form for creating a new trainer
     */
    public function create(): View
    {
        return view('trainers.create');
    }

    public function store(TrainerStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('trainers', 'public');
        }

        // Add parent_id
        $data['parent_id'] = parentId();

        $trainer = Trainer::create($data);

        // Send welcome email to trainer
        sendNotificationEmail('trainer_create', $trainer->email, [
            'gym_name' => settings('app_name', 'FitHub'),
            'trainer_name' => $trainer->name,
            'trainer_id' => $trainer->trainer_id,
            'email' => $trainer->email,
        ]);

        return redirect()->route('trainers.index')
            ->with('success', 'Trainer created successfully with ID: '.$trainer->trainer_id);
    }

    /**
     * Display the specified trainer
     */
    public function show(Trainer $trainer): View
    {
        // Check multi-tenant isolation
        if ($trainer->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $trainer->load('user');

        return view('trainers.show', compact('trainer'));
    }

    /**
     * Show the form for editing the specified trainer
     */
    public function edit(Trainer $trainer): View
    {
        // Check multi-tenant isolation
        if ($trainer->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        return view('trainers.edit', compact('trainer'));
    }

    /**
     * Update the specified trainer
     */
    public function update(TrainerUpdateRequest $request, Trainer $trainer): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($trainer->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($trainer->photo) {
                Storage::disk('public')->delete($trainer->photo);
            }
            $data['photo'] = $request->file('photo')->store('trainers', 'public');
        }

        $trainer->update($data);

        return redirect()->route('trainers.index')
            ->with('success', 'Trainer updated successfully');
    }

    /**
     * Remove the specified trainer
     */
    public function destroy(Trainer $trainer): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($trainer->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        // Delete photo if exists
        if ($trainer->photo) {
            Storage::disk('public')->delete($trainer->photo);
        }

        $trainer->delete();

        return redirect()->route('trainers.index')
            ->with('success', 'Trainer deleted successfully');
    }
}
