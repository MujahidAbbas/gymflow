<?php

namespace App\Http\Controllers;

use App\DataTables\SupportTicketDataTable;
use App\Models\Member;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(SupportTicketDataTable $dataTable)
    {

        return $dataTable->render('support-tickets.index');
    }

    public function create(): View
    {
        $members = Member::where('parent_id', parentId())->get();
        $users = User::where('parent_id', parentId())->get();
        
        return view('support-tickets.create', compact('members', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['parent_id'] = parentId();
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'open';

        SupportTicket::create($validated);

        return redirect()->route('support-tickets.index')
            ->with('success', 'Support ticket created successfully');
    }

    public function show(SupportTicket $supportTicket): View
    {
        if ($supportTicket->parent_id != parentId()) {
            abort(403);
        }

        $supportTicket->load(['member', 'createdBy', 'assignedTo', 'replies.user']);

        return view('support-tickets.show', compact('supportTicket'));
    }

    public function edit(SupportTicket $supportTicket): View
    {
        if ($supportTicket->parent_id != parentId()) {
            abort(403);
        }

        $members = Member::where('parent_id', parentId())->get();
        $users = User::where('parent_id', parentId())->get();

        return view('support-tickets.edit', compact('supportTicket', 'members', 'users'));
    }

    public function update(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        if ($supportTicket->parent_id != parentId()) {
            abort(403);
        }

        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($request->status == 'resolved' && !$supportTicket->resolved_at) {
            $validated['resolved_at'] = now();
        }

        $supportTicket->update($validated);

        return redirect()->route('support-tickets.index')
            ->with('success', 'Ticket updated successfully');
    }

    public function addReply(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        if ($supportTicket->parent_id != parentId()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal_note' => 'boolean',
        ]);

        $supportTicket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_internal_note' => $request->has('is_internal_note'),
        ]);

        // Update ticket status to in_progress if it's open
        if ($supportTicket->status == 'open') {
            $supportTicket->update(['status' => 'in_progress']);
        }

        return redirect()->route('support-tickets.show', $supportTicket)
            ->with('success', 'Reply added successfully');
    }

    public function destroy(SupportTicket $supportTicket): RedirectResponse
    {
        if ($supportTicket->parent_id != parentId()) {
            abort(403);
        }

        $supportTicket->delete();

        return redirect()->route('support-tickets.index')
            ->with('success', 'Ticket deleted successfully');
    }
}
