<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the departments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Department::with('manager')
                        ->withCount('users');
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
        }
        
        // Apply manager filter
        if ($request->has('manager_id') && !empty($request->manager_id)) {
            $query->where('manager_id', $request->manager_id);
        }
        
        // Apply employees filter
        if ($request->has('employees')) {
            if ($request->employees == 'with_employees') {
                $query->has('users');
            } elseif ($request->employees == 'without_employees') {
                $query->doesntHave('users');
            }
        }
        
        $departments = $query->orderBy('id')
                             ->paginate(10);
        
        // Get all managers for filter dropdown
        $managers = User::where('role', '!=', 'client')
                        ->orderBy('first_name')
                        ->get();
        
        return view('departments.index', compact('departments', 'managers'));
    }

    /**
     * Show the form for creating a new department.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        $managers = User::where('role', '!=', 'client')
                        ->orderBy('first_name')
                        ->get();
        
        return view('departments.create', compact('managers'));
    }

    /**
     * Store a newly created department in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:departments',
            'manager_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ]);
        
        Department::create($validated);
        
        return redirect()->route('departments.index')
                        ->with('success', 'Department created successfully');
    }

    /**
     * Display the specified department.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        $department->load(['manager', 'users', 'jobPositions']);
        
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        $managers = User::where('role', '!=', 'client')
                        ->orderBy('first_name')
                        ->get();
        
        return view('departments.edit', compact('department', 'managers'));
    }

    /**
     * Update the specified department in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:departments,name,' . $department->id,
            'manager_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ]);
        
        $department->update($validated);
        
        return redirect()->route('departments.index')
                        ->with('success', 'Department updated successfully');
    }

    /**
     * Remove the specified department from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
      if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        // Check if department has users
        if ($department->users()->count() > 0) {
            return redirect()->route('departments.index')
                            ->with('error', 'Cannot delete department with employees. Please reassign employees first.');
        }
        
        $department->delete();
        
        return redirect()->route('departments.index')
                        ->with('success', 'Department deleted successfully');
    }
}