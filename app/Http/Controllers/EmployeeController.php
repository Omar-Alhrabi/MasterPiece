<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Build the employee query with all necessary relationships
        $query = User::with(['department', 'jobPosition', 'manager'])
                    ->where('role', 'user');
        
        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        // Apply department filter if provided
        if ($request->has('department_id') && !empty($request->department_id)) {
            $query->where('department_id', $request->department_id);
        }
        
        // Apply job position filter if provided
        if ($request->has('job_position_id') && !empty($request->job_position_id)) {
            $query->where('job_position_id', $request->job_position_id);
        }
        
        // Apply employment status filter if provided
        if ($request->has('employment_status') && !empty($request->employment_status)) {
            $query->where('employment_status', $request->employment_status);
        }
        
        // Get paginated results
        $employees = $query->orderBy('id')->paginate(10);
        
        // Get all departments and job positions for filter dropdowns
        $departments = Department::orderBy('name')->get();
        $jobPositions = JobPosition::orderBy('title')->get();
        
        // Return view with data
        return view('employees.index', compact('employees', 'departments', 'jobPositions'));
    }

    /**
     * Show the form for creating a new employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        $departments = Department::orderBy('name')->get();
        $jobPositions = JobPosition::orderBy('title')->get();
        $managers = User::where('role', '!=', 'client')
                        ->orderBy('first_name')
                        ->get();
        
        return view('employees.create', compact('departments', 'jobPositions', 'managers'));
    }

    /**
     * Store a newly created employee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        Log::info('Employee creation request data:', $request->all());
        
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'phone_number' => 'nullable|string|max:20',
            'job_position_id' => 'nullable|exists:job_positions,id',
            'department_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:users,id',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'employment_status' => 'nullable|in:full-time,part-time,contract,intern,terminated',
        ]);
        
        Log::info('Employee validated data:', $validated);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'user';
        
        $user = User::create($validated);
        
        Log::info('Created employee:', ['id' => $user->id, 'data' => $user->toArray()]);
        
        return redirect()->route('employees.index')
                        ->with('success', 'Employee created successfully');
    }


    /**
     * Display the specified employee.
     *
     * @param  \App\Models\User  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(User $employee)
    {
        $employee->load(['department', 'jobPosition', 'manager', 'subordinates', 'projects', 'assignedTasks']);
        
        // Get attendance statistics
        $attendance = [
            'present' => $employee->attendance()->where('status', 'Present')->count(),
            'absent' => $employee->attendance()->where('status', 'Absent')->count(),
            'late' => $employee->attendance()->where('status', 'Late')->count(),
        ];
        
        // Get leave statistics
        $leaves = [
            'approved' => $employee->leaves()->where('status', 'Approved')->count(),
            'pending' => $employee->leaves()->where('status', 'Pending')->count(),
            'rejected' => $employee->leaves()->where('status', 'Rejected')->count(),
        ];
        
        return view('employees.show', compact('employee', 'attendance', 'leaves'));
    }

    /**
     * Show the form for editing the specified employee.
     *
     * @param  \App\Models\User  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(User $employee)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        $departments = Department::orderBy('name')->get();
        $jobPositions = JobPosition::orderBy('title')->get();
        $managers = User::where('role', '!=', 'client')
                        ->where('id', '!=', $employee->id)
                        ->orderBy('first_name')
                        ->get();
        
        return view('employees.edit', compact('employee', 'departments', 'jobPositions', 'managers'));
    }

    /**
     * Update the specified employee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $employee)
    {  
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        
        Log::info('Employee update request data:', $request->all());
        
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'phone_number' => 'nullable|string|max:20',
            'job_position_id' => 'nullable|exists:job_positions,id',
            'department_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:users,id',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'employment_status' => 'nullable|in:full-time,part-time,contract,intern,terminated',
            'termination_date' => 'nullable|date',
        ]);
        
        Log::info('Employee validated update data:', $validated);
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }
        
        $result = $employee->update($validated);
        
        Log::info('Updated employee result:', [
            'id' => $employee->id, 
            'result' => $result, 
            'updated_data' => $employee->fresh()->toArray()
        ]);
        
        return redirect()->route('employees.index')
                        ->with('success', 'Employee updated successfully');
    }

    /**
     * Remove the specified employee from storage.
     *
     * @param  \App\Models\User  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $employee)
    {  
        if (!Auth::user()->isAdmin()) {
        return redirect()->route('dashboard')
                        ->with('error', 'You do not have permission to access this page.');
    } 
        $employee->delete();
        
        return redirect()->route('employees.index')
                        ->with('success', 'Employee deleted successfully');
    }
    public function getSalaryInfo(Request $request)
        {
            $userId = $request->input('user_id');
            $user = User::findOrFail($userId);
            
            return response()->json([
                'salary' => $user->salary ?? 0
            ]);
        }
    /**
     * Display the organization chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function organization()
    {
        // Get CEO/Top management
        $topManagement = User::whereNull('manager_id')
                            ->where('role', '!=', 'client')
                            ->with('subordinates')
                            ->get();
        
        // Get departments with managers
        $departments = Department::with('manager')->get();
        
        return view('employees.organization', compact('topManagement', 'departments'));
    }
}