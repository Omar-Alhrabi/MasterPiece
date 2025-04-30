@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        @if(Auth::user()->isAdmin())
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a>
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
                                                        $priorityClass = 'danger';
                                                        $priorityText = 'High';
                                                    } elseif($daysLeft < 30) {
                                                        $priorityClass = 'danger';
                                                        $priorityText = 'High';
                                                    } elseif($daysLeft < 90) {
                                                        $priorityClass = 'info';
                                                        $priorityText = 'Medium';
                                                    } else {
                                                        $priorityClass = 'success';
                                                        $priorityText = 'Low';
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


<script>
document.addEventListener('DOMContentLoaded', function() {
    
    var earningsChart = document.getElementById('earningsAreaChart');
    if (earningsChart) {
        var ctx = earningsChart.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Earnings",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: {!! json_encode($earningsData ?? [0, 10000, 5000, 15000, 10000, 20000, 15000, 25000, 20000, 30000, 25000, 40000]) !!},
                }],
            },
            options: {
                
            }
        });
    }
       // 1. Project Status Chart
       var projectStatusCtx = document.getElementById('projectStatusPieChart');
    if (projectStatusCtx) {
        new Chart(projectStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ["Pending", "In Progress", "Completed", "On Hold", "Cancelled"],
                datasets: [{
                    data: [
                        {{ $projectStats['pending'] ?? 3 }},
                        {{ $projectStats['in_progress'] ?? 5 }},
                        {{ $projectStats['completed'] ?? 8 }},
                        {{ $projectStats['on_hold'] ?? 2 }},
                        {{ $projectStats['cancelled'] ?? 1 }}
                    ],
                    backgroundColor: ['#f6c23e', '#4e73df', '#1cc88a', '#36b9cc', '#e74a3b'],
                    hoverBackgroundColor: ['#dda20a', '#2e59d9', '#17a673', '#2c9faf', '#be2617'],
                }],
            },
            options: {
                cutoutPercentage: 80,
                legend: { display: false }
            }
        });
    }

    // 3. Task Completion Chart
    var taskCtx = document.getElementById('taskCompletionLineChart');
    if (taskCtx) {
        // Generate last 6 months labels
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const now = new Date();
        const currentMonth = now.getMonth();
        
        let taskLabels = [];
        for (let i = 5; i >= 0; i--) {
            const monthIndex = (currentMonth - i + 12) % 12;
            taskLabels.push(months[monthIndex]);
        }
        
        // Random data for demo
        let taskData = [15, 22, 19, 27, 30, 25];
        
        new Chart(taskCtx, {
            type: 'line',
            data: {
                labels: taskLabels,
                datasets: [{
                    label: "Tasks Completed",
                    lineTension: 0.3,
                    backgroundColor: "rgba(28, 200, 138, 0.05)",
                    borderColor: "rgba(28, 200, 138, 1)",
                    pointRadius: 3,
                    data: taskData,
                }],
            },
            options: {
                scales: {
                    xAxes: [{
                        gridLines: { display: false }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: { display: false }
            }
        });
    }
});
</script>
@endpush