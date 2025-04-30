<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\LeaveStatusUpdated;
use App\Notifications\LeaveRequestSubmitted;

class LeaveController extends Controller
{
    /**
     * Display a listing of the leaves.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // If user is admin, show all leaves
        if (Auth::user()->isAdmin()) {
            $leaves = Leave::with(['user', 'leaveType', 'approvedBy'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);
        } else {
            // Otherwise, show only the user's leaves
            $leaves = Leave::with(['leaveType', 'approvedBy'])
                          ->where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);
        }
        
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        $leaveTypes = LeaveType::orderBy('name')->get();
        
        return view('leaves.index', compact('leaves', 'users', 'leaveTypes'));
    }

    /**
     * Show the form for creating a new leave.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $leaveTypes = LeaveType::orderBy('name')->get();
        
        return view('leaves.create', compact('leaveTypes'));
    }

    /**
     * Store a newly created leave in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);
        
        // Calculate total days
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        
        // Check if user has enough leave balance
        $leaveType = LeaveType::find($validated['leave_type_id']);
        $usedLeaves = Leave::where('user_id', Auth::id())
                          ->where('leave_type_id', $validated['leave_type_id'])
                          ->where('status', 'Approved')
                          ->whereYear('start_date', date('Y'))
                          ->sum('total_days');
        
        if ($leaveType->days_allowed > 0 && ($usedLeaves + $totalDays) > $leaveType->days_allowed) {
            return redirect()->route('leaves.create')
                            ->with('error', "You don't have enough leave balance. You've used $usedLeaves days out of {$leaveType->days_allowed} days allowed.");
        }
        
        // Create the leave request
        $leave = Leave::create([
            'user_id' => Auth::id(),
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'Pending',
        ]);
        
        // Notify managers/admins about the leave request
        $managers = User::whereIn('role', ['admin', 'superadmin'])
                      ->orWhere('id', Auth::user()->manager_id)
                      ->get();
                      
        foreach ($managers as $manager) {
            $manager->notify(new LeaveRequestSubmitted($leave));
        }
        
        return redirect()->route('leaves.index')
                        ->with('success', 'Leave request submitted successfully.');
    }
    /**
     * Display the specified leave.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show(Leave $leave)
    {
        // Check if user is authorized to view this leave
        if (!Auth::user()->isAdmin() && Auth::id() !== $leave->user_id) {
            return redirect()->route('leaves.index')
                            ->with('error', 'You do not have permission to view this leave request.');
        }
        
        $leave->load(['user', 'leaveType', 'approvedBy']);
        
        return view('leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified leave.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function edit(Leave $leave)
    {
        // Check if user is authorized to edit this leave
        if (!Auth::user()->isAdmin() && Auth::id() !== $leave->user_id) {
            return redirect()->route('leaves.index')
                            ->with('error', 'You do not have permission to edit this leave request.');
        }
        
        // Check if leave can be edited (only pending leaves can be edited)
        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                            ->with('error', 'Only pending leave requests can be edited.');
        }
        
        $leaveTypes = LeaveType::orderBy('name')->get();
        
        return view('leaves.edit', compact('leave', 'leaveTypes'));
    }

    /**
     * Update the specified leave in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leave $leave)
    {
        // Check if user is authorized to update this leave
        if (!Auth::user()->isAdmin() && Auth::id() !== $leave->user_id) {
            return redirect()->route('leaves.index')
                            ->with('error', 'You do not have permission to update this leave request.');
        }
        
        // Check if leave can be updated (only pending leaves can be updated)
        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                            ->with('error', 'Only pending leave requests can be updated.');
        }
        
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);
        
        // Calculate total days
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        
        // Check if user has enough leave balance
        $leaveType = LeaveType::find($validated['leave_type_id']);
        $usedLeaves = Leave::where('user_id', $leave->user_id)
                          ->where('leave_type_id', $validated['leave_type_id'])
                          ->where('status', 'Approved')
                          ->where('id', '!=', $leave->id)
                          ->whereYear('start_date', date('Y'))
                          ->sum('total_days');
        
        if ($leaveType->days_allowed > 0 && ($usedLeaves + $totalDays) > $leaveType->days_allowed) {
            return redirect()->route('leaves.edit', $leave)
                            ->with('error', "You don't have enough leave balance. You've used $usedLeaves days out of {$leaveType->days_allowed} days allowed.");
        }
        
        // Update the leave request
        $leave->update([
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
        ]);
        
        return redirect()->route('leaves.index')
                        ->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified leave from storage.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leave $leave)
    {
        // Check if user is authorized to delete this leave
        if (!Auth::user()->isAdmin() && Auth::id() !== $leave->user_id) {
            return redirect()->route('leaves.index')
                            ->with('error', 'You do not have permission to delete this leave request.');
        }
        
        // Check if leave can be deleted (only pending leaves can be deleted)
        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                            ->with('error', 'Only pending leave requests can be deleted.');
        }
        
        $leave->delete();
        
        return redirect()->route('leaves.index')
                        ->with('success', 'Leave request deleted successfully.');
    }
    
    /**
     * Display leave requests for approval.
     *
     * @return \Illuminate\Http\Response
     */
    public function approvalList()
    {
        // Only admins can approve leaves
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('leaves.index')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        // Get pending leave requests
        $leaves = Leave::with(['user', 'leaveType'])
                    ->where('status', 'Pending')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        return view('leaves.approve', compact('leaves'));
    }
    
    /**
     * Approve a leave request.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function approve(Leave $leave)
    {
        // Only admins can approve leaves
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('leaves.index')
                            ->with('error', 'You do not have permission to approve this leave request.');
        }
        
        // Check if leave can be approved (only pending leaves can be approved)
        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.approve')
                            ->with('error', 'Only pending leave requests can be approved.');
        }
        
        // Approve the leave request
        $leave->update([
            'status' => 'Approved',
            'approved_by' => Auth::id(),
        ]);
        
        // Notify the employee
        $leave->user->notify(new LeaveStatusUpdated($leave));
        
        // Process the queue immediately
        Artisan::call('queue:work', ['--stop-when-empty' => true]);
        
        return redirect()->route('leaves.approve')
                        ->with('success', 'Leave request approved successfully.');
    }
    
    /**
     * Reject a leave request.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function reject(Leave $leave)
    {
        // Only admins can reject leaves
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('leaves.index')
                            ->with('error', 'You do not have permission to reject this leave request.');
        }
        
        // Check if leave can be rejected (only pending leaves can be rejected)
        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.approve')
                            ->with('error', 'Only pending leave requests can be rejected.');
        }
        
        // Reject the leave request
        $leave->update([
            'status' => 'Rejected',
            'approved_by' => Auth::id(),
        ]);
        
        // Notify the employee
        $leave->user->notify(new LeaveStatusUpdated($leave));
        
        // Process the queue immediately
        Artisan::call('queue:work', ['--stop-when-empty' => true]);
        
        return redirect()->route('leaves.approve')
                        ->with('success', 'Leave request rejected successfully.');
    }
    /**
     * Display leave reports.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request)
    {
        // Only accessible to admins
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('leaves.index')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        // Filter by user if provided
        $userId = $request->input('user_id');
        
        // Filter by leave type if provided
        $leaveTypeId = $request->input('leave_type_id');
        
        // Filter by status if provided
        $status = $request->input('status');
        
        // Filter by date range
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Build query
        $query = Leave::with(['user', 'leaveType', 'approvedBy']);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        if ($leaveTypeId) {
            $query->where('leave_type_id', $leaveTypeId);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $query->whereBetween('start_date', [$startDate, $endDate]);
        
        // Get leave records
        $leaves = $query->orderBy('start_date', 'desc')->get();
        
        // Calculate statistics
        $stats = [
            'pending' => $leaves->where('status', 'Pending')->count(),
            'approved' => $leaves->where('status', 'Approved')->count(),
            'rejected' => $leaves->where('status', 'Rejected')->count(),
            'cancelled' => $leaves->where('status', 'Cancelled')->count(),
            'total_days' => $leaves->where('status', 'Approved')->sum('total_days'),
        ];
        
        // Get users for filter
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        // Get leave types for filter
        $leaveTypes = LeaveType::orderBy('name')->get();
        
        return view('leaves.reports', compact(
            'leaves',
            'stats',
            'users',
            'leaveTypes',
            'userId',
            'leaveTypeId',
            'status',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Display leave requests.
     *
     * @return \Illuminate\Http\Response
     */
    public function requests(Request $request)
    {
        // Get leave types for filter
        $leaveTypes = LeaveType::orderBy('name')->get();

        // Build query
        $query = Leave::with(['leaveType', 'approvedBy', 'user']);
        
        // Apply filters if provided
        if ($request->has('leave_type_id') && !empty($request->leave_type_id)) {
            $query->where('leave_type_id', $request->leave_type_id);
        }
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_range') && !empty($request->date_range)) {
            [$startDate, $endDate] = explode(' - ', $request->date_range);
            $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $startDate)->format('Y-m-d');
            $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $endDate)->format('Y-m-d');
            
            $query->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate]);
            });
        }
        
        // Get users for filter if admin
        if (Auth::user()->isAdmin()) {
            $users = User::orderBy('first_name')->get();
            
            // Apply user filter if provided
            if ($request->has('user_id') && !empty($request->user_id)) {
                $query->where('user_id', $request->user_id);
            }
            
            // Get all leave requests for admin
            $leaves = $query->orderBy('created_at', 'desc')->paginate(10);
            
            return view('leaves.requests', compact('leaves', 'leaveTypes', 'users'));
        } else {
            // Only show user's leave requests
            $leaves = $query->where('user_id', Auth::id())
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
            
            return view('leaves.requests', compact('leaves', 'leaveTypes'));
        }
    }
}