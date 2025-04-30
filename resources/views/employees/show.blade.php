@extends('layouts.admin')

@section('title', 'Employee Details')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employee Details</h1>
        <div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('employees.edit', $employee) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            @endif
            <a href="{{ route('employees.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <div class="row">
        <!-- Left Column - Employee Profile -->
        <div class="col-xl-4 col-lg-5">
            <!-- Profile Card -->
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img class="img-profile rounded-circle mb-3" src="{{ asset('https://images.icon-icons.com/1378/PNG/512/avatardefault_92824.png') }}" alt="Profile Image" style="width: 150px; height: 150px;">
                        <h4 class="font-weight-bold">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                        <p class="text-muted">
                            {{ $employee->jobPosition ? $employee->jobPosition->title : 'No Position Assigned' }}
                        </p>
                        
                        @if($employee->employment_status)
                            @if(Auth::user()->isAdmin())
                            @if($employee->employment_status == 'full-time')
                                <span class="badge badge-success">Full Time</span>
                            @elseif($employee->employment_status == 'part-time')
                                <span class="badge badge-info">Part Time</span>
                            @elseif($employee->employment_status == 'contract')
                                <span class="badge badge-warning">Contract</span>
                            @elseif($employee->employment_status == 'intern')
                                <span class="badge badge-secondary">Intern</span>
                            @elseif($employee->employment_status == 'terminated')
                                <span class="badge badge-danger">Terminated</span>
                            @else
                            
                                <span class="badge badge-primary">{{ $employee->employment_status }}</span>
                            @endif
                            @endif
                        @endif
                    </div>
                    
                    <div class="border-left-primary pl-3">
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Email</p>
                            <p class="mb-0">{{ $employee->email }}</p>
                        </div>
                        
                        @if($employee->phone_number)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Phone</p>
                            <p class="mb-0">{{ $employee->phone_number }}</p>
                        </div>
                        @endif
                        
                        @if($employee->date_of_birth)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Date of Birth</p>
                            <p class="mb-0">{{ date('M d, Y', strtotime($employee->date_of_birth)) }}</p>
                        </div>
                        @endif
                        
                        @if($employee->gender)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Gender</p>
                            <p class="mb-0">{{ ucfirst($employee->gender) }}</p>
                        </div>
                        @endif
                        
                        @if($employee->department)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Department</p>
                            <p class="mb-0">{{ $employee->department->name }}</p>
                        </div>
                        @endif
                        
                        @if($employee->manager)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Manager</p>
                            <p class="mb-0">
                                <a href="{{ route('employees.show', $employee->manager) }}">
                                    {{ $employee->manager->first_name }} {{ $employee->manager->last_name }}
                                </a>
                            </p>
                        </div>
                        @endif
                        
                        @if($employee->hire_date)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Hire Date</p>
                            <p class="mb-0">{{ date('M d, Y', strtotime($employee->hire_date)) }}</p>
                        </div>
                        @endif
                        
                        @if(Auth::user()->isAdmin() && $employee->salary)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Salary</p>
                            <p class="mb-0">${{ number_format($employee->salary, 2) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Attendance Stats Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Attendance Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="circle-indicator bg-success mb-2">
                                <span>{{ $attendance['present'] }}</span>
                            </div>
                            <p class="small">Present</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="circle-indicator bg-warning mb-2">
                                <span>{{ $attendance['late'] }}</span>
                            </div>
                            <p class="small">Late</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="circle-indicator bg-danger mb-2">
                                <span>{{ $attendance['absent'] }}</span>
                            </div>
                            <p class="small">Absent</p>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('attendance.index', ['user_id' => $employee->id]) }}" class="btn btn-sm btn-primary">
                            View Attendance Records
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Leave Stats Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Leave Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="circle-indicator bg-success mb-2">
                                <span>{{ $leaves['approved'] }}</span>
                            </div>
                            <p class="small">Approved</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="circle-indicator bg-warning mb-2">
                                <span>{{ $leaves['pending'] }}</span>
                            </div>
                            <p class="small">Pending</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="circle-indicator bg-danger mb-2">
                                <span>{{ $leaves['rejected'] }}</span>
                            </div>
                            <p class="small">Rejected</p>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('leaves.index', ['user_id' => $employee->id]) }}" class="btn btn-sm btn-primary">
                            View Leave Records
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Projects and Tasks -->
        <div class="col-xl-8 col-lg-7">
            <!-- Projects Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Assigned Projects</h6>
                    @if($employee->projects->count() > 5)
                    <a href="{{ route('projects.index', ['user_id' => $employee->id]) }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Deadline</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->projects->take(5) as $project)
                                    <tr>
                                        <td>
                                            <a href="{{ route('projects.show', $project) }}" class="font-weight-bold text-primary">
                                                {{ $project->name }}
                                            </a>
                                        </td>
                                        <td>{{ $project->pivot->role ?? 'Team Member' }}</td>
                                        <td>
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
                                        </td>
                                        <td>{{ $project->end_date ? date('M d, Y', strtotime($project->end_date)) : 'No deadline' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No projects assigned</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Tasks Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Assigned Tasks</h6>
                    @if($employee->assignedTasks->count() > 5)
                    <a href="{{ route('tasks.index', ['assigned_to' => $employee->id]) }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Project</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->assignedTasks->take(5) as $task)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tasks.show', $task) }}" class="font-weight-bold text-primary">
                                                {{ $task->name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($task->project)
                                                <a href="{{ route('projects.show', $task->project) }}">
                                                    {{ $task->project->name }}
                                                </a>
                                            @else
                                                No project
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No tasks assigned</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Team Card (if employee is a manager) -->
            @if($employee->subordinates->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Team Members</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($employee->subordinates as $subordinate)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img class="img-profile rounded-circle mb-2" src="{{ asset('https://images.icon-icons.com/1378/PNG/512/avatardefault_92824.png') }}" alt="Profile" style="width: 80px; height: 80px;">
                                        <h5 class="card-title mb-0">
                                            <a href="{{ route('employees.show', $subordinate) }}">
                                                {{ $subordinate->first_name }} {{ $subordinate->last_name }}
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-2">
                                            {{ $subordinate->jobPosition ? $subordinate->jobPosition->title : 'No Position Assigned' }}
                                        </p>
                                        @if(Auth::user()->isAdmin())
                                        @if($subordinate->employment_status)
                                            @if($subordinate->employment_status == 'full-time')
                                                <span class="badge badge-success">Full Time</span>
                                            @elseif($subordinate->employment_status == 'part-time')
                                                <span class="badge badge-info">Part Time</span>
                                            @elseif($subordinate->employment_status == 'contract')
                                                <span class="badge badge-warning">Contract</span>
                                            @elseif($subordinate->employment_status == 'intern')
                                                <span class="badge badge-secondary">Intern</span>
                                            @elseif($subordinate->employment_status == 'terminated')
                                                <span class="badge badge-danger">Terminated</span>
                                            @else
                                                <span class="badge badge-primary">{{ $subordinate->employment_status }}</span>
                                            @endif
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
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