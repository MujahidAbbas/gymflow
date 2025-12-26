<?php

namespace App\Http\Controllers;

use App\DataTables\MemberDataTable;
use App\Http\Requests\MemberStoreRequest;
use App\Http\Requests\MemberUpdateRequest;
use App\Models\Member;
use App\Models\MembershipPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Display a listing of members
     */
    public function index(MemberDataTable $dataTable)
    {
        $parentId = parentId();


        $plans = MembershipPlan::where('parent_id', $parentId)->get();

        return $dataTable->render('members.index',compact('plans'));

    }

    /**
     * Show the form for creating a new member
     */
    public function create(): View
    {
        $parentId = parentId();
        $plans = MembershipPlan::where('parent_id', $parentId)->active()->get();

        return view('members.create', compact('plans'));
    }

    public function store(MemberStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        // Add parent_id
        $data['parent_id'] = parentId();

        // Calculate membership end date
        $plan = null;
        if ($data['membership_plan_id'] && $data['membership_start_date']) {
            $plan = MembershipPlan::find($data['membership_plan_id']);
            $data['membership_end_date'] = $plan->calculateExpiryDate($data['membership_start_date']);
        }

        $member = Member::create($data);

        // Send welcome email to member
        sendNotificationEmail('member_create', $member->email, [
            'gym_name' => settings('app_name', 'FitHub'),
            'member_name' => $member->name,
            'member_id' => $member->member_id,
            'membership_plan' => $plan ? $plan->name : 'N/A',
            'expiry_date' => $member->membership_end_date ? $member->membership_end_date->format('M d, Y') : 'Lifetime',
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Member created successfully with ID: '.$member->member_id);
    }

    /**
     * Display the specified member
     */
    public function show(Member $member): View
    {
        // Check multi-tenant isolation
        if ($member->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $member->load('membershipPlan', 'user');

        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member
     */
    public function edit(Member $member): View
    {
        // Check multi-tenant isolation
        if ($member->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $parentId = parentId();
        $plans = MembershipPlan::where('parent_id', $parentId)->active()->get();

        return view('members.edit', compact('member', 'plans'));
    }

    /**
     * Update the specified member
     */
    public function update(MemberUpdateRequest $request, Member $member): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($member->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        // Recalculate membership end date if plan or start date changed
        if (isset($data['membership_plan_id']) && isset($data['membership_start_date'])) {
            $plan = MembershipPlan::find($data['membership_plan_id']);
            $data['membership_end_date'] = $plan->calculateExpiryDate($data['membership_start_date']);
        }

        $member->update($data);

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully');
    }

    /**
     * Remove the specified member
     */
    public function destroy(Member $member)
    {
        // Check multi-tenant isolation
        if ($member->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        // Delete photo if exists
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
