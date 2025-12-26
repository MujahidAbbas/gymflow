<?php

namespace App\Http\Controllers\SuperAdmin;

use App\DataTables\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(CustomerDataTable $dataTable)
    {
        abort_unless(auth()->user()->can('view customers'), 403);

        return $dataTable->render('super-admin.customers.index');

//        return view('super-admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        abort_unless(auth()->user()->can('create customers'), 403);

        return view('super-admin.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        DB::beginTransaction();

        try {
            // Create owner user
            $owner = User::create([
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'password' => Hash::make($request->owner_password),
                'email_verified_at' => now(),
                'parent_id' => null, // Owner has no parent
            ]);

            // Assign owner role
            $owner->assignRole('owner');

            // Create tenant record
            $tenant = Tenant::create([
                'user_id' => $owner->id,
                'business_name' => $request->business_name,
                'subdomain' => $request->subdomain,
                'status' => 'active',
                'max_members' => $request->max_members,
                'max_trainers' => $request->max_trainers,
                'trial_ends_at' => $request->trial_days ? now()->addDays((int)$request->trial_days) : null,
            ]);

            DB::commit();

            return redirect()
                ->route('super-admin.customers.index')
                ->with('success', 'Customer created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified customer.
     */
    public function show(Tenant $customer)
    {
        abort_unless(auth()->user()->can('view customers'), 403);

        $customer->load('owner', 'users');

        // Get member count
        $memberCount = User::where('parent_id', $customer->user_id)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'member');
            })->count();

        // Get trainer count
        $trainerCount = User::where('parent_id', $customer->user_id)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'trainer');
            })->count();

        return view('super-admin.customers.show', compact('customer', 'memberCount', 'trainerCount'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Tenant $customer)
    {
        abort_unless(auth()->user()->can('edit customers'), 403);

        $customer->load('owner');

        return view('super-admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(UpdateCustomerRequest $request, Tenant $customer)
    {
        $customer->update([
            'business_name' => $request->business_name,
            'subdomain' => $request->subdomain,
            'status' => $request->status,
            'max_members' => $request->max_members,
            'max_trainers' => $request->max_trainers,
        ]);

        // Update trial period if provided
        if ($request->filled('trial_days')) {
            $customer->trial_ends_at = now()->addDays($request->trial_days);
            $customer->save();
        }

        return redirect()
            ->route('super-admin.customers.show', $customer)
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Tenant $customer)
    {
        abort_unless(auth()->user()->can('delete customers'), 403);

        DB::beginTransaction();

        try {
            // Delete all users under this tenant
            User::where('parent_id', $customer->user_id)->delete();

            // Delete owner user
            $customer->owner->delete();

            // Delete tenant
            $customer->delete();

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Data deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Impersonate a customer.
     */
    public function impersonate(Tenant $customer)
    {
        abort_unless(auth()->user()->can('impersonate customers'), 403);

        $owner = $customer->owner;

        if (!$owner) {
            return response()->json([
                'status' => false,
                'message' => 'No owner found for this customer!',
            ]);
        }

        if (!$owner->canBeImpersonated()) {
            return response()->json([
                'status' => false,
                'message' => 'This user cannot be impersonated.',
            ]);
        }

        auth()->user()->impersonate($owner);

        return response()->json([
            'status' => true,
            'message' => 'Impersonation successful',
        ]);
    }

    /**
     * Suspend a customer account.
     */
    public function suspend(Tenant $customer)
    {
        abort_unless(auth()->user()->can('edit customers'), 403);

        $customer->update(['status' => 'suspended']);

        return back()->with('success', 'Customer suspended successfully!');
    }
}
