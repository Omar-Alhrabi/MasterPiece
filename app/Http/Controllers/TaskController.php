<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskAssigned;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Task::with(['project', 'assignedUser', 'createdBy']);
        
        // Filter by project if provided
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        
        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by assigned user if provided
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        // Filter by priority if provided
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        
        $tasks = $query->orderBy('due_date', 'asc')
                      ->orderBy('priority', 'desc')
                      ->paginate(10);
        
        $projects = Project::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        return view('tasks.index', compact('tasks', 'projects', 'users'));
    }

    /**
     * Show the form for creating a new task.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        // Pre-select project if provided in query
        $selectedProject = null;
        if ($request->has('project_id')) {
            $selectedProject = Project::find($request->project_id);
        }
        
        return view('tasks.create', compact('projects', 'users', 'selectedProject'));
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'nullable|in:Pending,In Progress,Review,Completed',
            'priority' => 'nullable|in:Low,Medium,High,Urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        // Set created_by to current user
        $validated['created_by'] = Auth::id();
        
        $task = Task::create($validated);
        
        // Send notification to assigned user
        if ($task->assigned_to) {
            $assignedUser = User::find($task->assigned_to);
            $assignedUser->notify(new TaskAssigned($task));
        }
        
        if ($request->has('back_to_project')) {
            return redirect()->route('projects.show', $request->project_id)
                            ->with('success', 'Task created successfully');
        }
        
        return redirect()->route('tasks.index')
                        ->with('success', 'Task created successfully');
    }

    /**
     * Display the specified task.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $task->load(['project', 'assignedUser', 'createdBy']);
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        return view('tasks.show', compact('task', 'users'));
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $projects = Project::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'nullable|in:Pending,In Progress,Review,Completed',
            'priority' => 'nullable|in:Low,Medium,High,Urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        // Set completed_date if status is Completed
        if ($validated['status'] === 'Completed' && !$task->completed_date) {
            $validated['completed_date'] = now();
        } elseif ($validated['status'] !== 'Completed') {
            $validated['completed_date'] = null;
        }
        
        // Check if assigned_to has changed
        $oldAssignedTo = $task->assigned_to;
        
        $task->update($validated);
        
        // Send notification if assigned_to has changed
        if ($validated['assigned_to'] && $validated['assigned_to'] != $oldAssignedTo) {
            $assignedUser = User::find($validated['assigned_to']);
            $assignedUser->notify(new TaskAssigned($task));
        }
        
        if ($request->has('back_to_project')) {
            return redirect()->route('projects.show', $task->project_id)
                            ->with('success', 'Task updated successfully');
        }
        
        return redirect()->route('tasks.index')
                        ->with('success', 'Task updated successfully');
    }
    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $projectId = $task->project_id;
        
        $task->delete();
        
        if (request()->has('back_to_project')) {
            return redirect()->route('projects.show', $projectId)
                            ->with('success', 'Task deleted successfully');
        }
        
        return redirect()->route('tasks.index')
                        ->with('success', 'Task deleted successfully');
    }
}