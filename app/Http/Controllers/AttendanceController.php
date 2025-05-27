<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendances.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Filter by user if provided
        $userId = $request->input('user_id', Auth::id());
        
        // Filter by date range
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $attendances = Attendance::with('user')
                                ->where('user_id', $userId)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->orderBy('date', 'desc')
                                ->paginate(10);
        
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        return view('attendance.index', compact('attendances', 'users', 'startDate', 'endDate', 'userId'));
    }

    /**
     * Show the form for recording attendance.
     *
     * @return \Illuminate\Http\Response
     */
    public function record()
    {
        $today = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now();
        
        // Get today's attendance record for the current user
        $attendance = Attendance::where('user_id', Auth::id())
                              ->where('date', $today)
                              ->first();
        
        return view('attendance.record', compact('attendance', 'today', 'currentTime'));
    }

    /**
     * Check in the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkIn(Request $request)
    {
        $today = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now("+03:00");
        
        // Check if user has already checked in today
        $attendance = Attendance::where('user_id', Auth::id())
                              ->where('date', $today)
                              ->first();
        
        if ($attendance) {
            return redirect()->route('attendance.record')
                            ->with('error', 'You have already checked in today.');
        }
        
        // Determine status (on time or late)
        $status = 'Present';
        $workStartTime = Carbon::createFromTimeString('09:00:00');
        
        if ($currentTime->gt($workStartTime)) {
            $status = 'Late';
        }
        
        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $today,
            'check_in' => $currentTime,
            'status' => $status,
            'note' => $request->input('note'),
        ]);
        
        return redirect()->route('attendance.record')
                        ->with('success', 'Check-in successful.');
    }

    /**
     * Check out the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkOut(Request $request)
    {
        $today = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now();
        
        // Find today's attendance record for the current user
        $attendance = Attendance::where('user_id', Auth::id())
                              ->where('date', $today)
                              ->first();
        
        if (!$attendance) {
            return redirect()->route('attendance.record')
                            ->with('error', 'You need to check in first.');
        }
        
        if ($attendance->check_out) {
            return redirect()->route('attendance.record')
                            ->with('error', 'You have already checked out today.');
        }
        
        // If on break, automatically end break
        if ($attendance->break_start && !$attendance->break_end) {
            $attendance->break_end = $currentTime;
            
            // If there was a previous break, add to the multiple_breaks field
            if ($attendance->breaks) {
                $breakStartTime = Carbon::parse($attendance->break_start)->format('h:i A');
                $breakEndTime = Carbon::parse($currentTime)->format('h:i A');
                $existingBreaks = $attendance->breaks;
                $attendance->breaks = $existingBreaks . ', ' . $breakStartTime . ' - ' . $breakEndTime;
            } else {
                $breakStartTime = Carbon::parse($attendance->break_start)->format('h:i A');
                $breakEndTime = Carbon::parse($currentTime)->format('h:i A');
                $attendance->breaks = $breakStartTime . ' - ' . $breakEndTime;
            }
            
            $attendance->break_start = null;
            $attendance->break_end = null;
            $attendance->multiple_breaks = true;
        }
        
        // Determine if it's half-day
        if ($attendance->status === 'Present' || $attendance->status === 'Late') {
            $checkInTime = Carbon::parse($attendance->check_in);
            $workHours = $currentTime->diffInHours($checkInTime);
            
            if ($workHours < 4) {
                $attendance->status = 'Half-day';
            }
        }
        
        $attendance->check_out = $currentTime;
        $attendance->note = $request->filled('note') ? $request->note : $attendance->note;
        $attendance->save();
        
        return redirect()->route('attendance.record')
                        ->with('success', 'Check-out successful.');
    }
    
    /**
     * Start a break.
     *
     * @return \Illuminate\Http\Response
     */
    public function breakStart()
    {
        $today = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now();
        
        // Find today's attendance record
        $attendance = Attendance::where('user_id', Auth::id())
                              ->where('date', $today)
                              ->first();
        
        if (!$attendance) {
            return redirect()->route('attendance.record')
                            ->with('error', 'You need to check in first before taking a break.');
        }
        
        if ($attendance->check_out) {
            return redirect()->route('attendance.record')
                            ->with('error', 'You have already checked out today.');
        }
        
        if ($attendance->break_start && !$attendance->break_end) {
            return redirect()->route('attendance.record')
                            ->with('error', 'You are already on a break.');
        }
        
        // If previous break exists, store it in the multiple_breaks field
        if ($attendance->break_start && $attendance->break_end) {
            $breakStartTime = Carbon::parse($attendance->break_start)->format('h:i A');
            $breakEndTime = Carbon::parse($attendance->break_end)->format('h:i A');
            
            if ($attendance->breaks) {
                $existingBreaks = $attendance->breaks;
                $attendance->breaks = $existingBreaks . ', ' . $breakStartTime . ' - ' . $breakEndTime;
            } else {
                $attendance->breaks = $breakStartTime . ' - ' . $breakEndTime;
            }
            
            $attendance->multiple_breaks = true;
        }
        
        $attendance->break_start = $currentTime;
        $attendance->break_end = null;
        $attendance->save();
        
        return redirect()->route('attendance.record')
                        ->with('success', 'Break started.');
    }
    
    /**
     * End a break.
     *
     * @return \Illuminate\Http\Response
     */
    public function breakEnd()
    {
        $today = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now();
        
        // Find today's attendance record
        $attendance = Attendance::where('user_id', Auth::id())
                              ->where('date', $today)
                              ->first();
        
        if (!$attendance) {
            return redirect()->route('attendance.record')
                            ->with('error', 'No attendance record found for today.');
        }
        
        if (!$attendance->break_start) {
            return redirect()->route('attendance.record')
                            ->with('error', 'You have not started a break.');
        }
        
        if ($attendance->break_end) {
            return redirect()->route('attendance.record')
                            ->with('error', 'Break has already ended.');
        }
        
        $attendance->break_end = $currentTime;
        $attendance->save();
        
        return redirect()->route('attendance.record')
                        ->with('success', 'Break ended.');
    }
    
    /**
     * Display attendance reports.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request)
    {
        // Filter by user if provided or if user is not admin
        $userId = null;
        if ($request->has('user_id')) {
            $userId = $request->user_id;
        } elseif (!Auth::user()->isAdmin()) {
            $userId = Auth::id();
        }
        
        // Filter by date range
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Build query
        $query = Attendance::with('user')
                         ->orderBy('date', 'desc');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $query->whereBetween('date', [$startDate, $endDate]);
        
        // Get attendance records
        $attendances = $query->get();
        
        // Calculate statistics
        $stats = [
            'present' => $attendances->where('status', 'Present')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'half_day' => $attendances->where('status', 'Half-day')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
        ];
        
        // Get working days in the period
        $workingDays = $this->getWorkingDaysCount($startDate, $endDate);
        
        // Get employee attendance rate
        $attendanceRate = ($stats['present'] + $stats['late'] + $stats['half_day']) / max(1, $workingDays) * 100;
        
        // Get users for filter
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        return view('attendance.reports', compact(
            'attendances',
            'stats',
            'users',
            'userId',
            'startDate',
            'endDate',
            'workingDays',
            'attendanceRate'
        ));
    }
    
    /**
     * Get the count of working days in a period.
     *
     * @param  string  $startDate
     * @param  string  $endDate
     * @return int
     */
    private function getWorkingDaysCount($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = 0;
        
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            // Skip weekends (Saturday and Sunday)
            if ($date->dayOfWeek !== Carbon::FRIDAY && $date->dayOfWeek !== Carbon::SATURDAY) {
                $days++;
            }
        }
        
        return $days;
    }

    /**
     * Show the form for creating a new attendance record.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Only accessible to admins
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('attendance.index')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        return view('attendance.create', compact('users'));
    }

    /**
     * Store a newly created attendance record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Only accessible to admins
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('attendance.index')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:Present,Absent,Late,Half-day',
            'note' => 'nullable|string',
            'breaks' => 'nullable|string',
        ]);
        
        // Convert time strings to datetime objects
        if ($request->filled('check_in')) {
            $validated['check_in'] = Carbon::parse($validated['date'] . ' ' . $validated['check_in']);
        }
        
        if ($request->filled('check_out')) {
            $validated['check_out'] = Carbon::parse($validated['date'] . ' ' . $validated['check_out']);
        }
        
        // Check if attendance record already exists for this user and date
        $existingAttendance = Attendance::where('user_id', $validated['user_id'])
                                      ->where('date', $validated['date'])
                                      ->first();
        
        if ($existingAttendance) {
            return redirect()->route('attendance.create')
                            ->with('error', 'Attendance record already exists for this user and date.');
        }
        
        Attendance::create($validated);
        
        return redirect()->route('attendance.index')
                        ->with('success', 'Attendance record created successfully.');
    }

    /**
     * Show the form for editing the specified attendance record.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        // Only accessible to admins or the owner
        if (!Auth::user()->isAdmin() && Auth::id() !== $attendance->user_id) {
            return redirect()->route('attendance.index')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        return view('attendance.edit', compact('attendance', 'users'));
    }

    /**
     * Update the specified attendance record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        // Only accessible to admins or the owner
        if (!Auth::user()->isAdmin() && Auth::id() !== $attendance->user_id) {
            return redirect()->route('attendance.index')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:Present,Absent,Late,Half-day',
            'note' => 'nullable|string',
            'breaks' => 'nullable|string',
        ]);
        
        // Convert time strings to datetime objects
        if ($request->filled('check_in')) {
            $validated['check_in'] = Carbon::parse($validated['date'] . ' ' . $validated['check_in']);
        }
        
        if ($request->filled('check_out')) {
            $validated['check_out'] = Carbon::parse($validated['date'] . ' ' . $validated['check_out']);
        }
        
        $attendance->update($validated);
        
        return redirect()->route('attendance.index')
                        ->with('success', 'Attendance record updated successfully.');
    }

    /**
     * Remove the specified attendance record from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        // Only accessible to admins
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('attendance.index')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        $attendance->delete();
        
        return redirect()->route('attendance.index')
                        ->with('success', 'Attendance record deleted successfully.');
    }

    /**
     * Get attendance summary for salary calculation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        $userId = $request->input('user_id');
        $month = $request->input('month');
        $year = $request->input('year');
        
        if (!$userId || !$month || !$year) {
            return response()->json([
                'working_days' => 0,
                'present_days' => 0,
                'half_days' => 0,
                'absent_days' => 0,
                'paid_leaves' => 0,
                'unpaid_leaves' => 0,
                'overtime_hours' => 0,
                'estimated_salary' => 0
            ]);
        }
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $workingDays = $this->getWorkingDaysCount($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        
        $attendance = Attendance::where('user_id', $userId)
                         ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                         ->get();
        
        $presentDays = $attendance->whereIn('status', ['Present', 'Late'])->count();
        $halfDays = $attendance->where('status', 'Half-day')->count();
        $absentDays = $attendance->where('status', 'Absent')->count();
        
        $paidLeaves = Leave::where('user_id', $userId)
                         ->where('status', 'Approved')
                         ->whereHas('leaveType', function ($query) {
                             $query->where('is_paid', true);
                         })
                         ->where(function ($query) use ($startDate, $endDate) {
                             $query->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                   ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                         })
                         ->get();

        $paidLeaveDays = 0;
        foreach ($paidLeaves as $leave) {
            $leaveStart = max($startDate->format('Y-m-d'), $leave->start_date);
            $leaveEnd = min($endDate->format('Y-m-d'), $leave->end_date);
            
            $leaveStart = Carbon::parse($leaveStart);
            $leaveEnd = Carbon::parse($leaveEnd);
            
            for ($date = $leaveStart->copy(); $date->lte($leaveEnd); $date->addDay()) {
                if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                    $paidLeaveDays++;
                }
            }
        }

        $unpaidLeaves = Leave::where('user_id', $userId)
                          ->where('status', 'Approved')
                          ->whereHas('leaveType', function ($query) {
                              $query->where('is_paid', false);
                          })
                          ->where(function ($query) use ($startDate, $endDate) {
                              $query->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                    ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                          })
                          ->get();
        
        $unpaidLeaveDays = 0;
        foreach ($unpaidLeaves as $leave) {
            $leaveStart = max($startDate->format('Y-m-d'), $leave->start_date);
            $leaveEnd = min($endDate->format('Y-m-d'), $leave->end_date);
            
            $leaveStart = Carbon::parse($leaveStart);
            $leaveEnd = Carbon::parse($leaveEnd);
            
            for ($date = $leaveStart->copy(); $date->lte($leaveEnd); $date->addDay()) {
                if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                    $unpaidLeaveDays++;
                }
            }
        }
        
        // Get user salary
        $user = User::findOrFail($userId);
        $salary = $user->salary ?? 0;
        
        // Calculate estimated salary
        $dailyRate = ($workingDays > 0) ? ($salary / $workingDays) : 0;
        $estimatedSalary = $dailyRate * ($presentDays + $paidLeaveDays + ($halfDays * 0.5));
        
        return response()->json([
            'working_days' => $workingDays,
            'present_days' => $presentDays,
            'half_days' => $halfDays,
            'absent_days' => $absentDays,
            'paid_leaves' => $paidLeaveDays,
            'unpaid_leaves' => $unpaidLeaveDays,
            'overtime_hours' => 0, // We'll implement this later
            'estimated_salary' => round($estimatedSalary, 2)
        ]);
    }
}