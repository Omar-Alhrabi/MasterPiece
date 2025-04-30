<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::with(['client', 'manager', 'users'])
                          ->orderBy('created_at', 'asc')
                          ->paginate(10);
        
        // Get clients and managers for filters
        $clients = Client::orderBy('name')->get();
        $managers = User::where('role', '!=', 'client')
                        ->orderBy('first_name')
                        ->get();
        
        return view('projects.index', compact('projects', 'clients', 'managers'));
    }

    /**
     * Show the form for creating a new project.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }
        $clients = Client::orderBy('name')->get();
        $managers = User::where('role', '!=', 'client')
                        ->orderBy('first_name')
                        ->get();
        
        return view('projects.create', compact('clients', 'managers'));
    }

    /**
     * Store a newly created project in storage.
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:Pending,In Progress,Completed,On Hold,Cancelled',
            'budget' => 'nullable|numeric',
            'manager_id' => 'nullable|exists:users,id',
        ]);
        
        $project = Project::create($validated);
        
        if ($request->has('team_members')) {
            $project->users()->attach($request->team_members, [
                'role' => 'Team Member',
                'assigned_date' => now(),
            ]);
        }
        
        return redirect()->route('projects.index')
                        ->with('success', 'Project created successfully');
    }

    /**
     * Display the specified project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $project->load(['client', 'manager', 'users', 'tasks']);
        
        // Get task statistics
        $taskStats = [
            'pending' => $project->tasks()->where('status', 'Pending')->count(),
            'in_progress' => $project->tasks()->where('status', 'In Progress')->count(),
            'review' => $project->tasks()->where('status', 'Review')->count(),
            'completed' => $project->tasks()->where('status', 'Completed')->count(),
        ];
        
        // Get available employees to add to the project
        $availableEmployees = User::where('role', 'user')
                                ->whereNotIn('id', $project->users->pluck('id'))
                                ->orderBy('first_name')
                                ->get();
        
        return view('projects.show', compact('project', 'taskStats', 'availableEmployees'));
    }

    /**
     * Show the form for editing the specified project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }

        $clients = Client::orderBy('name')->get();
        $managers = User::where('role', '!=', 'client')
                        ->orderBy('first_name')
                        ->get();
        $employees = User::where('role', 'user')
                        ->orderBy('first_name')
                        ->get();
        
        return view('projects.edit', compact('project', 'clients', 'managers', 'employees'));
    }

    /**
     * Update the specified project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:Pending,In Progress,Completed,On Hold,Cancelled',
            'budget' => 'nullable|numeric',
            'manager_id' => 'nullable|exists:users,id',
        ]);
        
        $project->update($validated);
        
        if ($request->has('team_members')) {
            $project->users()->sync($request->team_members);
        }
        
        return redirect()->route('projects.index')
                        ->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        // Check if project has tasks
        if ($project->tasks()->count() > 0) {
            return redirect()->route('projects.index')
                            ->with('error', 'Cannot delete project with active tasks. Please delete tasks first.');
        }
        
        // Detach all users from the project
        $project->users()->detach();
        
        $project->delete();
        
        return redirect()->route('projects.index')
                        ->with('success', 'Project deleted successfully');
    }
    
    /**
     * Add a team member to the project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function addMember(Request $request, Project $project)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|max:100',
        ]);
        
        // Check if user is already a member of the project
        if (!$project->users->contains($validated['user_id'])) {
            $project->users()->attach($validated['user_id'], [
                'role' => $validated['role'],
                'assigned_date' => now(),
            ]);
            
            return redirect()->route('projects.show', $project)
                            ->with('success', 'Team member added successfully');
        }
        
        return redirect()->route('projects.show', $project)
                        ->with('error', 'User is already a member of this project');
    }
    
    /**
     * Remove a team member from the project.
     *
     * @param  \App\Models\Project  $project
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function removeMember(Project $project, $userId)
    {
        $project->users()->detach($userId);
        
        return redirect()->route('projects.show', $project)
                        ->with('success', 'Team member removed successfully');
    }
}