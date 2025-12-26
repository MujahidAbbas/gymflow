<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $query = Contact::where('parent_id', parentId());

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $contacts = $query->latest()->paginate(20);

        return view('contacts.index', compact('contacts'));
    }

    public function create(): View
    {
        return view('contacts.create');
    }

    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create([
            ...$request->validated(),
            'parent_id' => parentId(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact created successfully',
                'contact' => $contact
            ]);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact created successfully');
    }

    public function show(Request $request, Contact $contact): JsonResponse|RedirectResponse
    {
        if ($contact->parent_id != parentId()) {
            abort(403);
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'contact' => $contact
            ]);
        }

        return redirect()->route('contacts.index');
    }

    public function edit(Contact $contact)
    {
        if ($contact->parent_id != parentId()) {
            abort(403);
        }

        if (request()->ajax()) {
            return response()->json(['contact' => $contact]);
        }

        return view('contacts.edit', compact('contact'));
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        if ($contact->parent_id != parentId()) {
            abort(403);
        }

        $contact->update($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact updated successfully',
                'contact' => $contact
            ]);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact updated successfully');
    }

    public function destroy(Contact $contact)
    {
        if ($contact->parent_id != parentId()) {
            abort(403);
        }

        $contact->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact deleted successfully'
            ]);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully');
    }
}
