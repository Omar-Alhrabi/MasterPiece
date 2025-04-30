@extends('layouts.admin')

@section('title', 'Project Details')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Project Details</h1>
        <div>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add Task
            </a>
            <a href="{{ route('projects.edit', $project) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm ml-2">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            @endif
            <a href="{{ route('projects.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <div class="row">
        <!-- Left Column - Project Info -->
        <div class="col-xl-4 col-lg-5">
            <!-- Project Info Card -->
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Project Information</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="display-4">
                            <i class="fas fa-project-diagram text-primary"></i>
                        </div>
                        <h4 class="font-weight-bold mt-2">{{ $project->name }}</h4>
                        
                        @if($project->status)
                            @if($project->status == 'Completed')
                                <span class="badge badge-success">{{ $project->status }}</span>
                            @elseif($project->status == 'In Progress')
                                <span class="badge badge-info">{{ $project->status }}</span>
                            @elseif($project->status == 'Pending')
                                <span class="badge badge-warning">{{ $project->status }}</span>
                            @elseif($project->status == 'On Hold')
                                <span class="badge badge-secondary">{{ $project->status }}</span>
                            @elseif($project->status == 'Cancelled')
                                <span class="badge badge-danger">{{ $project->status }}</span>
                            @else
                                <span class="badge badge-primary">{{ $project->status }}</span>
                            @endif
                        @endif
                    </div>
                    
                    <div class="border-left-primary pl-3">
                        @if($project->client)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Client</p>
                            <p class="mb-0">
                                <a href="{{ route('clients.show', $project->client) }}">
                                    {{ $project->client->name }}
                                </a>
                            </p>
                        </div>
                        @endif
                        
                        @if($project->manager)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Project Manager</p>
                            <p class="mb-0">
                                <a href="{{ route('employees.show', $project->manager) }}">
                                    {{ $project->manager->first_name }} {{ $project->manager->last_name }}
                                </a>
                            </p>
                        </div>
                        @endif
                        
                        @if($project->start_date)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Start Date</p>
                            <p class="mb-0">{{ date('M d, Y', strtotime($project->start_date)) }}</p>
                        </div>
                        @endif
                        
                        @if($project->end_date)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">End Date</p>
                            <p class="mb-0">{{ date('M d, Y', strtotime($project->end_date)) }}</p>
                        </div>
                        @endif
                        
                        @if($project->budget)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Budget</p>
                            <p class="mb-0">${{ number_format($project->budget, 2) }}</p>
                        </div>
                        @endif
                        
                        @if($project->description)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Description</p>
                            <p class="mb-0">{{ $project->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Task Stats Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Task Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 text-center mb-3">
                            <div class="circle-indicator bg-warning mb-2">
                                <span>{{ $taskStats['pending'] }}</span>
                            </div>
                            <p class="small">Pending</p>
                        </div>
                        <div class="col-md-6 text-center mb-3">
                            <div class="circle-indicator bg-info mb-2">
                                <span>{{ $taskStats['in_progress'] }}</span>
                            </div>
                            <p class="small">In Progress</p>
                        </div>
                        <div class="col-md-6 text-center mb-3">
                            <div class="circle-indicator bg-secondary mb-2">
                                <span>{{ $taskStats['review'] }}</span>
                            </div>
                            <p class="small">Review</p>
                        </div>
                        <div class="col-md-6 text-center mb-3">
                            <div class="circle-indicator bg-success mb-2">
                                <span>{{ $taskStats['completed'] }}</span>
                            </div>
                            <p class="small">Completed</p>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    @php
                        $totalTasks = array_sum($taskStats);
                        $completedPercentage = $totalTasks > 0 ? round(($taskStats['completed'] / $totalTasks) * 100) : 0;
                    @endphp
                    
                    <div class="mt-3">
                        <h4 class="small font-weight-bold">Project Completion <span class="float-right">{{ $completedPercentage }}%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completedPercentage }}%"
                                aria-valuenow="{{ $completedPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Team and Tasks -->
        <div class="col-xl-8 col-lg-7">
            <!-- Team Members Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Team Members</h6>
                    @if(Auth::user()->isAdmin())
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addTeamMemberModal">
                        <i class="fas fa-user-plus fa-sm"></i> Add Member
                    </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($project->users as $user)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img class="img-profile rounded-circle mb-2" src="{{ asset('https://images.icon-icons.com/1378/PNG/512/avatardefault_92824.png') }}" alt="Profile" style="width: 80px; height: 80px;">
                                        <h5 class="card-title mb-0">
                                            <a href="{{ route('employees.show', $user) }}">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-2">
                                            {{ $user->jobPosition ? $user->jobPosition->title : 'No Position Assigned' }}
                                        </p>
                                        <p class="text-primary small mb-1">
                                            <strong>Role:</strong> {{ $user->pivot->role ?? 'Team Member' }}
                                        </p>
                                        @if($user->pivot->assigned_date)
                                            <p class="text-muted small mb-0">
                                                Joined: {{ date('M d, Y', strtotime($user->pivot->assigned_date)) }}
                                            </p>
                                        @endif
                                        
                                        <!-- Remove team member button -->
                                        <form action="{{ route('projects.remove-member', [$project->id, $user->id]) }}" method="POST" class="mt-2">
                                            @csrf
                                            @if(Auth::user()->isAdmin())
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to remove this member from the project?');">
                                                <i class="fas fa-user-minus"></i> Remove
                                            </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center">
                                <p class="text-muted">No team members assigned to this project yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Tasks Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tasks</h6>
                    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus fa-sm"></i> Add Task
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($project->tasks as $task)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tasks.show', $task) }}" class="font-weight-bold text-primary">
                                                {{ $task->name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($task->assignedUser)
                                                <a href="{{ route('employees.show', $task->assignedUser) }}">
                                                    {{ $task->assignedUser->first_name }} {{ $task->assignedUser->last_name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->status == 'Completed')
                                                <span class="badge badge-success">{{ $task->status }}</span>
                                            @elseif($task->status == 'In Progress')
                                                <span class="badge badge-info">{{ $task->status }}</span>
                                            @elseif($task->status == 'Pending')
                                                <span class="badge badge-warning">{{ $task->status }}</span>
                                            @elseif($task->status == 'Review')
                                                <span class="badge badge-secondary">{{ $task->status }}</span>
                                            @else
                                                <span class="badge badge-primary">{{ $task->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->priority == 'Low')
                                                <span class="badge badge-success">{{ $task->priority }}</span>
                                            @elseif($task->priority == 'Medium')
                                                <span class="badge badge-info">{{ $task->priority }}</span>
                                            @elseif($task->priority == 'High')
                                                <span class="badge badge-warning">{{ $task->priority }}</span>
                                            @elseif($task->priority == 'Urgent')
                                                <span class="badge badge-danger">{{ $task->priority }}</span>
                                            @else
                                                <span class="badge badge-primary">{{ $task->priority }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $task->due_date ? date('M d, Y', strtotime($task->due_date)) : 'No deadline' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(Auth::user()->isAdmin())
                                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-info" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        data-toggle="modal" 
                                                        data-target="#deleteTaskModal-{{ $task->id }}"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                            
                                            <!-- Delete Task Modal -->
                                            <div class="modal fade" id="deleteTaskModal-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteTaskModalLabel-{{ $task->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteTaskModalLabel-{{ $task->id }}">Delete Task</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete task <strong>{{ $task->name }}</strong>? This action cannot be undone.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="back_to_project" value="1">
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No tasks found for this project</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Team Member Modal -->
    <div class="modal fade" id="addTeamMemberModal" tabindex="-1" role="dialog" aria-labelledby="addTeamMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTeamMemberModalLabel">Add Team Member</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('projects.add-member', $project) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user_id">Select Employee</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">Select Employee</option>
                                @foreach($availableEmployees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->first_name }} {{ $employee->last_name }} - {{ $employee->jobPosition ? $employee->jobPosition->title : 'No Position' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Role in Project</label>
                            <input type="text" class="form-control" id="role" name="role" placeholder="e.g. Developer, Designer, Tester" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .circle-indicator {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
    }
</style>
@endpush