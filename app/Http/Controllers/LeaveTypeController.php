<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the leave types.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Only accessible to admins
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        $leaveTypes = LeaveType::withCount('leaves')
                             ->orderBy('name')
                             ->paginate(10);
        
        return view('leave_types.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new leave type.
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
        
        return view('leave_types.create');
    }

    /**
     * Store a newly created leave type in storage.
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
            'name' => 'required|string|max:100|unique:leave_types',
            'description' => 'nullable|string',
            'days_allowed' => 'required|integer|min:0',
            'is_paid' => 'required|boolean',
        ]);
        
        LeaveType::create($validated);
        
        return redirect()->route('leave-types.index')
                        ->with('success', 'Leave type created successfully.');
    }

    /**
     * Display the specified leave type.
     *
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveType $leaveType)
    {
        // Only accessible to admins
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        $leaveType->load('leaves');
        
        return view('leave_types.show', compact('leaveType'));
    }

    /**
     * Show the form for editing the specified leave type.
     *
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaveType $leaveType)
    {
        // Only accessible to admins
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        return view('leave_types.edit', compact('leaveType'));
    }

    /**
     * Update the specified leave type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        // Only accessible to admins
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:leave_types,name,' . $leaveType->id,
            'description' => 'nullable|string',
            'days_allowed' => 'required|integer|min:0',
            'is_paid' => 'required|boolean',
        ]);
        
        $leaveType->update($validated);
        
        return redirect()->route('leave-types.index')
                        ->with('success', 'Leave type updated successfully.');
    }

    /**
     * Remove the specified leave type from storage.
     *
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveType $leaveType)
    {
        // Only accessible to admins
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        // Check if leave type has leaves
        if ($leaveType->leaves()->exists()) {
            return redirect()->route('leave-types.index')
                            ->with('error', 'Cannot delete leave type that has associated leave requests.');
        }
        
        $leaveType->delete();
        
        return redirect()->route('leave-types.index')
                        ->with('success', 'Leave type deleted successfully.');
    }
}