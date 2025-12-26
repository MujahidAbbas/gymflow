<?php

namespace App\Http\Controllers;

use App\DataTables\AttendanceDataTable;
use App\DataTables\AttendanceReportDataTable;
use App\Models\Attendance;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records
     */
    public function index(AttendanceDataTable $dataTable)
    {
        $parentId = parentId();

        $members = Member::where('parent_id', $parentId)->get();

        // Stats for today
        $todayStats = [
            'total' => Attendance::where('parent_id', $parentId)->today()->count(),
            'checked_in' => Attendance::where('parent_id', $parentId)->today()->active()->count(),
            'checked_out' => Attendance::where('parent_id', $parentId)->today()->whereNotNull('check_out_time')->count(),
        ];

        return $dataTable->render('attendances.index', compact( 'members', 'todayStats'));
//        return view('attendances.index', compact('attendances', 'members', 'todayStats'));
    }

    /**
     * Show the form for check-in
     */
    public function create(): View
    {
        $parentId = parentId();
        $members = Member::where('parent_id', $parentId)->active()->get();

        return view('attendances.create', compact('members'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'date' => 'required|date',
            'check_in_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $validated['parent_id'] = parentId();

        // Check if member already checked in today
        $existing = Attendance::where('member_id', $validated['member_id'])
            ->whereDate('date', $validated['date'])
            ->active()
            ->first();

        if ($existing) {
            return back()->with('error', 'Member is already checked in today!');
        }

        $attendance = Attendance::create($validated);

        // Send email to member
        $member = $attendance->member;
        sendNotificationEmail('attendance_marked', $member->email, [
            'gym_name' => settings('app_name', 'FitHub'),
            'member_name' => $member->name,
            'date' => $attendance->date->format('M d, Y'),
            'check_in_time' => $attendance->check_in_time,
            'check_out_time' => $attendance->check_out_time ?? 'Not yet',
            'duration' => $attendance->duration ?? '0 mins',
        ]);

        return redirect()->route('attendances.index')
            ->with('success', 'Check-in recorded successfully');
    }

    /**
     * Check out a member
     */
    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($attendance->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        if ($attendance->isCheckedOut()) {
            return back()->with('error', 'Member already checked out!');
        }

        $validated = $request->validate([
            'check_out_time' => 'required|date_format:H:i',
        ]);

        $attendance->update($validated);
        $attendance->calculateDuration();

        return redirect()->route('attendances.index')
            ->with('success', 'Check-out recorded successfully');
    }

    /**
     * Remove the specified attendance record
     */
    public function destroy(Attendance $attendance): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($attendance->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $attendance->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data deleted successfully'
        ]);
    }

    /**
     * Show attendance report
     */
    public function report(AttendanceReportDataTable $dataTable,Request $request)
    {
        $parentId = parentId();

        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $attendances = Attendance::where('parent_id', $parentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('member')
            ->get();

        $stats = [
            'total_visits' => $attendances->count(),
            'unique_members' => $attendances->pluck('member_id')->unique()->count(),
            'avg_duration' => round($attendances->where('duration_minutes', '!=', null)->avg('duration_minutes')),
            'total_hours' => round($attendances->sum('duration_minutes') / 60, 1),
        ];

       return $dataTable->render('attendances.report',compact('attendances', 'stats', 'startDate', 'endDate'));
    }
}
