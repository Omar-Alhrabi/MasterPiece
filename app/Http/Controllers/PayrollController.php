<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Display a listing of the salaries.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        $salaries = Salary::with('user')
                        ->orderBy('payment_date', 'desc')
                        ->paginate(10);
        
        return view('payroll.index', compact('salaries'));
    }

    /**
     * Show the form for calculating salaries.
     *
     * @return \Illuminate\Http\Response
     */
    public function calculate()
    {
        // Only accessible to admins
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        $users = User::where('role', 'user')
                    ->whereNotNull('salary')
                    ->orderBy('first_name')
                    ->get();
        
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::createFromDate(null, $i, 1)->format('F');
        }
        
        $years = range(date('Y') - 2, date('Y') + 1);
        
        return view('payroll.calculate', compact('users', 'months', 'years'));
    }

    /**
     * Process the salary calculation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        // Only accessible to admins
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2000,2100',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:bank_transfer,cash,cheque',
        ]);
        
        $user = User::findOrFail($validated['user_id']);
        
        // Check if salary already processed for this month and year
        $existingSalary = Salary::where('user_id', $user->id)
                               ->where('month', $validated['month'])
                               ->where('year', $validated['year'])
                               ->where('type', 'basic')
                               ->first();
        
        if ($existingSalary) {
            return redirect()->route('payroll.calculate')
                            ->with('error', "Salary for {$user->first_name} {$user->last_name} for " . 
                                   Carbon::createFromDate($validated['year'], $validated['month'], 1)->format('F Y') . 
                                   " has already been processed.");
        }
        
        // Get month start and end dates
        $startDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->endOfMonth();
        
        // Calculate working days in the month
        $workingDays = $this->getWorkingDaysCount($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        
        // Get attendance data for the month
        $attendance = Attendance::where('user_id', $user->id)
                              ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                              ->get();
        
        $presentDays = $attendance->whereIn('status', ['Present', 'Late'])->count();
        $halfDays = $attendance->where('status', 'Half-day')->count();
        $absentDays = $attendance->where('status', 'Absent')->count();
        
        // Get approved paid leaves for the month
        $paidLeaves = Leave::where('user_id', $user->id)
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
            
            // Count only working days
            for ($date = $leaveStart; $date->lte($leaveEnd); $date->addDay()) {
                if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                    $paidLeaveDays++;
                }
            }
        }
        
        // Calculate salary based on attendance
        $dailyRate = $user->salary / $workingDays;
        $basicSalary = $dailyRate * ($presentDays + $paidLeaveDays + ($halfDays * 0.5));
        
        // Create the salary record
        Salary::create([
            'user_id' => $user->id,
            'amount' => $basicSalary,
            'type' => 'basic',
            'description' => 'Basic salary for ' . Carbon::createFromDate($validated['year'], $validated['month'], 1)->format('F Y'),
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'is_paid' => false,
            'month' => $validated['month'],
            'year' => $validated['year'],
        ]);
        
        return redirect()->route('payroll.history')
                        ->with('success', "Salary for {$user->first_name} {$user->last_name} for " . 
                               Carbon::createFromDate($validated['year'], $validated['month'], 1)->format('F Y') . 
                               " has been processed successfully.");
    }

    /**
     * Display the payment history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request)
    {
        // Only accessible to admins
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        // Filter by user if provided
        $userId = $request->input('user_id');
        
        // Filter by month and year if provided
        $month = $request->input('month');
        $year = $request->input('year', date('Y'));
        
        // Filter by payment status if provided
        $isPaid = $request->input('is_paid');
        
        // Build query
        $query = Salary::with('user')->orderBy('payment_date', 'desc');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        if ($month) {
            $query->where('month', $month);
        }
        
        if ($year) {
            $query->where('year', $year);
        }
        
        if ($isPaid !== null) {
            $query->where('is_paid', $isPaid);
        }
        
        $salaries = $query->paginate(10);
        
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::createFromDate(null, $i, 1)->format('F');
        }
        
        $years = range(date('Y') - 2, date('Y') + 1);
        
        return view('payroll.history', compact('salaries', 'users', 'months', 'years', 'userId', 'month', 'year', 'isPaid'));
    }

    /**
     * Display the payroll reports.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request)
    {
        // Only accessible to admins
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        // Filter by year if provided
        $year = $request->input('year', date('Y'));
        
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        $years = range(date('Y') - 2, date('Y') + 1);
        
        // Get monthly salary data for all users
        $monthlyData = [];
        
        foreach ($users as $user) {
            $userData = [
                'user' => $user,
                'months' => [],
                'total' => 0,
            ];
            
            for ($month = 1; $month <= 12; $month++) {
                $salary = Salary::where('user_id', $user->id)
                              ->where('month', $month)
                              ->where('year', $year)
                              ->where('type', 'basic')
                              ->first();
                
                $userData['months'][$month] = $salary ? $salary->amount : 0;
                $userData['total'] += $userData['months'][$month];
            }
            
            $monthlyData[] = $userData;
        }
        
        // Get total salaries paid per month
        $monthlyTotals = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthlyTotals[$month] = Salary::where('month', $month)
                                         ->where('year', $year)
                                         ->where('type', 'basic')
                                         ->sum('amount');
        }
        
        $totalSalaries = array_sum($monthlyTotals);
        
        return view('payroll.reports', compact('monthlyData', 'monthlyTotals', 'totalSalaries', 'years', 'year'));
    }

    /**
     * Show the form for creating a new salary record.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Only accessible to admins
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        return view('payroll.create', compact('users'));
    }

    /**
     * Store a newly created salary record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Only accessible to admins
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:basic,bonus,allowance,deduction,overtime',
            'description' => 'required|string',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:bank_transfer,cash,cheque',
            'is_paid' => 'required|boolean',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2000,2100',
        ]);
        
        Salary::create($validated);
        
        return redirect()->route('payroll.index')
                        ->with('success', 'Salary record created successfully.');
    }

    /**
     * Display the specified salary record.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show(Salary $salary)
    {
        // Only accessible to admins
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        $salary->load('user');
        
        return view('payroll.show', compact('salary'));
    }

    /**
     * Show the form for editing the specified salary record.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit(Salary $salary)
    {
        // Only accessible to admins
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        return view('payroll.edit', compact('salary', 'users'));
    }

    /**
     * Update the specified salary record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        // Only accessible to admins
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:basic,bonus,allowance,deduction,overtime',
            'description' => 'required|string',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:bank_transfer,cash,cheque',
            'is_paid' => 'required|boolean',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2000,2100',
        ]);
        
        $salary->update($validated);
        
        return redirect()->route('payroll.index')
                        ->with('success', 'Salary record updated successfully.');
    }

    /**
     * Remove the specified salary record from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {
        // Only accessible to admins
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        $salary->delete();
        
        return redirect()->route('payroll.index')
                        ->with('success', 'Salary record deleted successfully.');
    }
    public function generateSlip(Salary $salary)
{
    // Only accessible to admins
 /*    if (!Auth::user()->isAdmin()) {
        return redirect()->route('dashboard')
                        ->with('error', 'You do not have permission to access this page.');
    } */
    
    $salary->load('user');
    
    // In a real application, you would generate a PDF here
    // For now, we'll just return a view with the pay slip details
    
    $monthNames = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    
    return view('payroll.slip', compact('salary', 'monthNames'));
}
public function markAsPaid(Salary $salary)
{
    // Only accessible to admins
/*     if (!Auth::user()->isAdmin()) {
        return redirect()->route('dashboard')
                        ->with('error', 'You do not have permission to access this page.');
    } */
    
    $salary->update(['is_paid' => true]);
    
    return redirect()->route('payroll.show', $salary)
                    ->with('success', 'Salary has been marked as paid successfully.');
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
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            // Skip weekends (Saturday and Sunday)
            if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                $days++;
            }
        }
        
        return $days;
    }
}