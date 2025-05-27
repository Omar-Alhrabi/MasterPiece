@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    @if(Auth::user()->isAdmin())
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal">
        <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
    </a>

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Generate Monthly Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('report.generate') }}" method="GET">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="report_month">Month</label>
                            <select class="form-control" id="report_month" name="month" required>
                                <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>January</option>
                                <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>February</option>
                                <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>March</option>
                                <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>April</option>
                                <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>May</option>
                                <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>June</option>
                                <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>July</option>
                                <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>August</option>
                                <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>September</option>
                                <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>October</option>
                                <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>November</option>
                                <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>December</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="report_year">Year</label>
                            <select class="form-control" id="report_year" name="year" required>
                                @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 5;
                                @endphp
                                @for($year = $currentYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

@if(Auth::user()->isAdmin())
<!-- ADMIN DASHBOARD CONTENT -->
<!-- Content Row -->
<div class="row">
    <!-- Employees Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Employees</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $employeeCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clients Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Clients</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $clientCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Projects
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projectCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Tasks Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Tasks</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingTasks }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tasks fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Charts -->
@include('partials.admin-charts')

<!-- Content Row -->
<div class="row">
    <!-- Content Column -->
    <div class="col-lg-6 mb-4">
        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
            </div>
            <div class="card-body">
                @foreach($projectsWithProgress as $project => $progress)
                <h4 class="small font-weight-bold">{{ $project }} <span
                        class="float-right">{{ $progress }}%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar {{ $progress < 30 ? 'bg-danger' : ($progress < 60 ? 'bg-warning' : ($progress < 90 ? 'bg-info' : 'bg-success')) }}"
                        role="progressbar" style="width: {{ $progress }}%"
                        aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Todo Card -->
        <div class="card shadow mb-4">
            <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                <h5 class="m-0 font-weight-bold text-primary">Todo</h5>
                <div class="d-flex align-items-center">
                    <a class="btn btn-primary btn-icon btn-sm rounded-circle d-flex align-items-center justify-content-center p-0 mb-2"
                        href="#" data-toggle="modal" data-target="#add_todo">
                        <i class="fas fa-plus fs-16"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($tasks as $index => $task)
                <div class="d-flex align-items-center todo-item border p-2 br-5 mb-2">
                    <i class="fas fa-grip-lines mr-2"></i>
                    <div class="form-check">
                        <input class="form-check-input task-checkbox" id="todo{{ $index+1 }}" type="checkbox" data-task-id="{{ $task->id }}">
                        <label class="form-check-label fw-medium" for="todo{{ $index+1 }}">{{ $task->name }}</label>
                    </div>
                    <div class="ml-auto">
                        <span class="badge badge-{{ $task->priority == 'Low' ? 'success' : ($task->priority == 'Medium' ? 'info' : 'danger') }} badge-xs">
                            {{ $task->priority }}
                        </span>
                        <a href="#" class="btn btn-sm btn-link text-danger delete-task" data-task-id="{{ $task->id }}">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-3">
                    <p>No pending tasks available</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <!-- Project Table -->
        <div class="card shadow mb-4">
            <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                <h5 class="m-0 font-weight-bold text-primary">Projects</h5>
                <div class="d-flex align-items-center">
                    <div class="dropdown mb-2">
                        <a class="btn btn-white border btn-sm d-inline-flex align-items-center"
                            href="#" role="button" id="projectDropdown" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-calendar mr-1"></i>This Week
                        </a>
                        <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="projectDropdown">
                            <a class="dropdown-item rounded-1" href="#">This Month</a>
                            <a class="dropdown-item rounded-1" href="#">This Week</a>
                            <a class="dropdown-item rounded-1" href="#">Today</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table project -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Team</th>
                                <th>Deadline</th>
                                <th>Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                            <tr>
                                <td><a href="{{ route('projects.show', $project->id) }}">PRO-{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</a></td>
                                <td>
                                    <h6 class="fw-medium"><a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a></h6>
                                </td>
                                <td>
                                    <div class="avatar-list-stacked avatar-group-sm">
                                        @foreach($project->users->take(3) as $user)
                                        <span class="avatar avatar-rounded">
                                            <img class="border border-white" alt="{{ $user->first_name }}"
                                                src="https://cdn.vectorstock.com/i/1000v/68/83/teamwork-group-building-and-unity-logo-vector-21386883.jpg" />
                                        </span>
                                        @endforeach
                                        @if($project->users->count() > 3)
                                        <a class="avatar bg-primary avatar-rounded text-white fs-10 fw-medium" href="#">
                                            +{{ $project->users->count() - 3 }}
                                        </a>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $project->end_date ? date('d/m/Y', strtotime($project->end_date)) : 'N/A' }}</td>
                                <td>
                                    @php
                                    $priorityClass = '';
                                    $priorityText = 'Medium';

                                    // Example priority logic based on deadline proximity
                                    if($project->end_date) {
                                    $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($project->end_date), false);
                                    if($daysLeft < 0) {
                                        $priorityClass='danger' ;
                                        $priorityText='High' ;
                                        } elseif($daysLeft < 30) {
                                        $priorityClass='danger' ;
                                        $priorityText='High' ;
                                        } elseif($daysLeft < 90) {
                                        $priorityClass='info' ;
                                        $priorityText='Medium' ;
                                        } else {
                                        $priorityClass='success' ;
                                        $priorityText='Low' ;
                                        }
                                        }
                                        @endphp
                                        <span class="badge badge-{{ $priorityClass }} d-inline-flex align-items-center badge-xs">
                                        <i class="fas fa-circle fa-sm mr-1"></i>{{ $priorityText }}
                                        </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No projects available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<!-- EMPLOYEE DASHBOARD CONTENT -->
<div class="row">
    <!-- My Tasks Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            My Tasks</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $taskCount }}</div>
                        <div class="small text-success mt-2">
                            <i class="fas fa-check"></i> {{ $completedTaskCount }} Completed
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tasks fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Projects Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            My Projects</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projectCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Balance Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Leave Balance
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $leaveAllowance - $leavesTaken }} / {{ $leaveAllowance }}</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{ (($leaveAllowance - $leavesTaken) / $leaveAllowance) * 100 }}%"
                                        aria-valuenow="{{ $leaveAllowance - $leavesTaken }}" aria-valuemin="0"
                                        aria-valuemax="{{ $leaveAllowance }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Attendance This Month</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $attendanceStats['present'] }} Days Present</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Dashboard - Better Task Visual and Leave Summary -->
@include('partials.user-dashboard-content')

@endif

<!-- Add Todo Modal -->
<div class="modal fade" id="add_todo" tabindex="-1" role="dialog" aria-labelledby="add_todo_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_todo_label">Add New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addTaskForm" action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="task_name">Task Name</label>
                        <input type="text" class="form-control" id="task_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="project_id">Project</label>
                        <select class="form-control" id="project_id" name="project_id" required>
                            <option value="">Select Project</option>
                            @if(Auth::user()->isAdmin() && isset($projects))
                            @foreach($projects as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                            @endforeach
                            @else
                            @foreach($myProjects as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="task_description">Description</label>
                        <textarea class="form-control" id="task_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="task_priority">Priority</label>
                        <select class="form-control" id="task_priority" name="priority">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="task_status">Status</label>
                        <select class="form-control" id="task_status" name="status">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Review">Review</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="task_due_date">Due Date</label>
                        <input type="date" class="form-control" id="task_due_date" name="due_date">
                    </div>
                    @if(!Auth::user()->isAdmin())
                    <input type="hidden" name="assigned_to" value="{{ Auth::id() }}">
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush