<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoticeBoardRequest;
use App\Http\Requests\UpdateNoticeBoardRequest;
use App\Models\NoticeBoard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NoticeBoardController extends Controller
{
    /**
     * Display a listing of notices
     */
    public function index(Request $request): View
    {
        $parentId = parentId();

        $query = NoticeBoard::where('parent_id', $parentId);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Priority filter
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        $notices = $query->latest('publish_date')->paginate(20);
        $activeNotices = NoticeBoard::where('parent_id', $parentId)->active()->highPriority()->get();

        return view('notice-boards.index', compact('notices', 'activeNotices'));
    }

    /**
     * Show the form for creating a new notice
     */
    public function create(): View
    {
        return view('notice-boards.create');
    }

    /**
     * Store a newly created notice
     */
    public function store(StoreNoticeBoardRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $validated['parent_id'] = parentId();

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('notices', 'public');
        }

        $notice = NoticeBoard::create($validated);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notice created successfully',
                'notice' => $notice,
            ]);
        }

        return redirect()->route('notice-boards.index')
            ->with('success', 'Notice created successfully');
    }

    /**
     * Display the specified notice
     */
    public function show(Request $request, NoticeBoard $noticeBoard): View|JsonResponse|RedirectResponse
    {
        // Check multi-tenant isolation
        if ($noticeBoard->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'notice' => $noticeBoard,
            ]);
        }

        return view('notice-boards.show', compact('noticeBoard'));
    }

    /**
     * Show the form for editing the specified notice
     */
    public function edit(NoticeBoard $noticeBoard)
    {
        // Check multi-tenant isolation
        if ($noticeBoard->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json(['notice' => $noticeBoard]);
        }

        return view('notice-boards.edit', compact('noticeBoard'));
    }

    /**
     * Update the specified notice
     */
    public function update(UpdateNoticeBoardRequest $request, NoticeBoard $noticeBoard): JsonResponse|RedirectResponse
    {
        // Check multi-tenant isolation
        if ($noticeBoard->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validated();

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($noticeBoard->attachment) {
                Storage::disk('public')->delete($noticeBoard->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('notices', 'public');
        }

        $noticeBoard->update($validated);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notice updated successfully',
                'notice' => $noticeBoard,
            ]);
        }

        return redirect()->route('notice-boards.index')
            ->with('success', 'Notice updated successfully');
    }

    /**
     * Remove the specified notice
     */
    public function destroy(NoticeBoard $noticeBoard): JsonResponse|RedirectResponse
    {
        // Check multi-tenant isolation
        if ($noticeBoard->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        // Delete attachment if exists
        if ($noticeBoard->attachment) {
            Storage::disk('public')->delete($noticeBoard->attachment);
        }

        $noticeBoard->delete();

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notice deleted successfully',
            ]);
        }

        return redirect()->route('notice-boards.index')
            ->with('success', 'Notice deleted successfully');
    }
}
