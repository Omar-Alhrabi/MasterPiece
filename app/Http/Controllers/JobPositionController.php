<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use App\Models\Department;
use Illuminate\Http\Request;

class JobPositionController extends Controller
{
    /**
     * Display a listing of the job positions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobPositions = JobPosition::with('department')
                                  ->withCount('users')
                                  ->orderBy('title')
                                  ->paginate(10);
        
        return view('job_positions.index', compact('jobPositions'));
    }

    /**
     * Show the form for creating a new job position.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        
        return view('job_positions.create', compact('departments'));
    }

    /**
     * Store a newly created job position in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'department_id' => 'nullable|exists:departments,id',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|gte:min_salary',
            'description' => 'nullable|string',
        ]);
        
        JobPosition::create($validated);
        
        return redirect()->route('job-positions.index')
                        ->with('success', 'Job position created successfully');
    }

    /**
     * Display the specified job position.
     *
     * @param  \App\Models\JobPosition  $jobPosition
     * @return \Illuminate\Http\Response
     */
    public function show(JobPosition $jobPosition)
    {
        $jobPosition->load(['department', 'users']);
        
        return view('job_positions.show', compact('jobPosition'));
    }

    /**
     * Show the form for editing the specified job position.
     *
     * @param  \App\Models\JobPosition  $jobPosition
     * @return \Illuminate\Http\Response
     */
    public function edit(JobPosition $jobPosition)
    {
        $departments = Department::orderBy('name')->get();
        
        return view('job_positions.edit', compact('jobPosition', 'departments'));
    }

    /**
     * Update the specified job position in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobPosition  $jobPosition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobPosition $jobPosition)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'department_id' => 'nullable|exists:departments,id',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|gte:min_salary',
            'description' => 'nullable|string',
        ]);
        
        $jobPosition->update($validated);
        
        return redirect()->route('job-positions.index')
                        ->with('success', 'Job position updated successfully');
    }

    /**
     * Remove the specified job position from storage.
     *
     * @param  \App\Models\JobPosition  $jobPosition
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobPosition $jobPosition)
    {
        // Check if job position has users
        if ($jobPosition->users()->count() > 0) {
            return redirect()->route('job-positions.index')
                            ->with('error', 'Cannot delete job position with employees. Please reassign employees first.');
        }
        
        $jobPosition->delete();
        
        return redirect()->route('job-positions.index')
                        ->with('success', 'Job position deleted successfully');
    }
    
    /**
     * Get job positions by department ID for AJAX request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByDepartment(Request $request)
    {
        $departmentId = $request->input('department_id');
        $positions = JobPosition::where('department_id', $departmentId)
                    ->orderBy('title')
                    ->get();
        
        return response()->json($positions);
    }
}