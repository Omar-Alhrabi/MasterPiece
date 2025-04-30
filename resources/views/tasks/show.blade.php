@extends('layouts.admin')

@section('title', 'Task Details')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Task Details</h1>
        <div>
            <a href="{{ route('tasks.edit', $task) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            @if($task->project)
                <a href="{{ route('projects.show', $task->project) }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm ml-2">
                    <i class="fas fa-project-diagram fa-sm text-white-50"></i> View Project
                </a>
            @endif
            <a href="{{ route('tasks.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <div class="row">
        <!-- Left Column - Task Info -->
        <div class="col-xl-8 col-lg-7">
            <!-- Task Info Card -->
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Task Information</h6>
                    <div>
                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteTaskModal">
                            <i class="fas fa-trash fa-sm"></i> Delete Task
                        </button>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="font-weight-bold">{{ $task->name }}</h4>
                        <div class="mb-2">
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
                            
                            @if($task->priority == 'Low')
                                <span class="badge badge-success">{{ $task->priority }} Priority</span>
                            @elseif($task->priority == 'Medium')
                                <span class="badge badge-info">{{ $task->priority }} Priority</span>
                            @elseif($task->priority == 'High')
                                <span class="badge badge-warning">{{ $task->priority }} Priority</span>
                            @elseif($task->priority == 'Urgent')
                                <span class="badge badge-danger">{{ $task->priority }} Priority</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Project:</h6>
                                @if($task->project)
                                    <p>
                                        <a href="{{ route('projects.show', $task->project) }}">
                                            {{ $task->project->name }}
                                        </a>
                                    </p>
                                @else
                                    <p class="text-muted">Not assigned to any project</p>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Assigned To:</h6>
                                @if($task->assignedUser)
                                    <p>
                                        <a href="{{ route('employees.show', $task->assignedUser) }}">
                                            {{ $task->assignedUser->first_name }} {{ $task->assignedUser->last_name }}
                                        </a>
                                    </p>
                                @else
                                    <p class="text-muted">Not assigned to any employee</p>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Created By:</h6>
                                @if($task->createdBy)
                                    <p>
                                        <a href="{{ route('employees.show', $task->createdBy) }}">
                                            {{ $task->createdBy->first_name }} {{ $task->createdBy->last_name }}
                                        </a>
                                    </p>
                                @else
                                    <p class="text-muted">Unknown</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Start Date:</h6>
                                <p>{{ $task->start_date ? date('M d, Y', strtotime($task->start_date)) : 'Not specified' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Due Date:</h6>
                                <p>{{ $task->due_date ? date('M d, Y', strtotime($task->due_date)) : 'No deadline' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Completed Date:</h6>
                                <p>{{ $task->completed_date ? date('M d, Y', strtotime($task->completed_date)) : 'Not completed yet' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Description:</h6>
                        <p>{{ $task->description ?? 'No description provided' }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small">Created: {{ $task->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p class="text-muted small">Last Updated: {{ $task->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Quick Actions -->
        <div class="col-xl-4 col-lg-5">
            <!-- Status Update Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Only include necessary fields for status update -->
                        <input type="hidden" name="name" value="{{ $task->name }}">
                        <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                        <input type="hidden" name="description" value="{{ $task->description }}">
                        <input type="hidden" name="priority" value="{{ $task->priority }}">
                        <input type="hidden" name="start_date" value="{{ $task->start_date ? $task->start_date->format('Y-m-d') : '' }}">
                        <input type="hidden" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                        
                        <div class="form-group">
                            <label for="status">Change Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Review" {{ $task->status == 'Review' ? 'selected' : '' }}>Review</option>
                                <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="assigned_to">Reassign To</label>
                            <select class="form-control" id="assigned_to" name="assigned_to">
                                <option value="">-- Select Employee --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">Update Task</button>
                    </form>
                </div>
            </div>
            
            <!-- Other Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Links</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit Full Task Details
                    </a>
                    
                    @if($task->project)
                        <a href="{{ route('projects.show', $task->project) }}" class="btn btn-secondary btn-block mb-2">
                            <i class="fas fa-project-diagram"></i> Go to Project
                        </a>
                    @endif
                    
                    @if($task->assignedUser)
                        <a href="{{ route('employees.show', $task->assignedUser) }}" class="btn btn-secondary btn-block mb-2">
                            <i class="fas fa-user"></i> View Assigned Employee
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Task Modal -->
    <div class="modal fade" id="deleteTaskModal" tabindex="-1" role="dialog" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTaskModalLabel">Delete Task</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the task <strong>{{ $task->name }}</strong>? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection