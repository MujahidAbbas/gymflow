<?php

namespace App\Http\Controllers;

use App\DataTables\MembershipPlanDataTable;
use App\Http\Requests\MembershipPlanStoreRequest;
use App\Http\Requests\MembershipPlanUpdateRequest;
use App\Models\MembershipPlan;

class MembershipPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(MembershipPlanDataTable $dataTable)
    {

        return $dataTable->render('membership-plans.index');
//        return view('membership-plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('membership-plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MembershipPlanStoreRequest $request)
    {
        $data = $request->validated();
        $data['parent_id'] = parentId();

        // Handle unchecked checkboxes - boolean() returns false if missing
        $data['personal_training'] = $request->boolean('personal_training');
        $data['is_active'] = $request->boolean('is_active');

        MembershipPlan::create($data);

        return redirect()->route('membership-plans.index')
            ->with('success', 'Membership plan created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(MembershipPlan $membershipPlan)
    {
        if ($membershipPlan->parent_id != parentId()) {
            abort(403);
        }

        return view('membership-plans.show', compact('membershipPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MembershipPlan $membershipPlan)
    {
        if ($membershipPlan->parent_id != parentId()) {
            abort(403);
        }

        return view('membership-plans.edit', compact('membershipPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MembershipPlanUpdateRequest $request, MembershipPlan $membershipPlan)
    {
        if ($membershipPlan->parent_id != parentId()) {
            abort(403);
        }

        $data = $request->validated();
        // Handle unchecked checkboxes - boolean() returns false if missing
        $data['personal_training'] = $request->boolean('personal_training');
        $data['is_active'] = $request->boolean('is_active');

        $membershipPlan->update($data);

        return redirect()->route('membership-plans.index')
            ->with('success', 'Membership plan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MembershipPlan $membershipPlan)
    {
        if ($membershipPlan->parent_id != parentId()) {
            abort(403);
        }

        $membershipPlan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
