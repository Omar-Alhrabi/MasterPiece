<!-- User Dashboard - Better Task and Leave UI -->
<div class="row">
    <!-- My Task Summary - Instead of Task Completion Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">My Task Summary</h6>
                <a href="{{ route('tasks.index', ['assigned_to' => Auth::id()]) }}" class="btn btn-sm btn-primary">
                    View All Tasks
                </a>
            </div>
            <div class="card-body">
                <!-- Task Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-left-success h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Completed Tasks</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedTaskCount }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-primary h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            In Progress</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ App\Models\Task::where('assigned_to', Auth::id())->where('status', 'In Progress')->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-spinner fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-warning h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Tasks</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ App\Models\Task::where('assigned_to', Auth::id())->where('status', 'Pending')->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Deadlines -->
                <h6 class="font-weight-bold text-primary mb-3">Upcoming Deadlines</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $upcomingTasks = App\Models\Task::where('assigned_to', Auth::id())
                                    ->where('status', '!=', 'Completed')
                                    ->where('due_date', '>=', \Carbon\Carbon::now())
                                    ->orderBy('due_date', 'asc')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($upcomingTasks as $task)
                                @php
                                    $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($task->due_date), false);
                                    $statusClass = $task->status == 'Pending' ? 'warning' : ($task->status == 'In Progress' ? 'primary' : 'info');
                                    $priorityClass = $task->priority == 'Low' ? 'success' : ($task->priority == 'Medium' ? 'info' : 'danger');
                                @endphp
                                <tr>
                                    <td><a href="{{ route('tasks.show', $task->id) }}">{{ $task->name }}</a></td>
                                    <td>
                                        {{ $task->due_date ? date('d/m/Y', strtotime($task->due_date)) : 'N/A' }}
                                        @if($daysLeft < 3 && $daysLeft >= 0)
                                            <span class="badge badge-danger ml-2">{{ $daysLeft == 0 ? 'Today' : $daysLeft . ' days left' }}</span>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-{{ $statusClass }}">{{ $task->status }}</span></td>
                                    <td><span class="badge badge-{{ $priorityClass }}">{{ $task->priority }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No upcoming deadlines</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Summary - Instead of Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Leave Summary</h6>
                <a href="{{ route('leaves.requests') }}" class="btn btn-sm btn-primary">
                    Request Leave
                </a>
            </div>
            <div class="card-body">
                <!-- Leave Balance Progress -->
                <h4 class="small font-weight-bold">Leave Balance <span class="float-right">{{ $leaveAllowance - $leavesTaken }} / {{ $leaveAllowance }} days</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" 
                         style="width: {{ (($leaveAllowance - $leavesTaken) / $leaveAllowance) * 100 }}%"
                         aria-valuenow="{{ $leaveAllowance - $leavesTaken }}" aria-valuemin="0" aria-valuemax="{{ $leaveAllowance }}"></div>
                </div>
                
                <!-- Leave Types -->
                <div class="mt-4">
                    <h6 class="font-weight-bold text-primary mb-3">Leave By Type</h6>
                    @foreach($leaveTypes as $index => $leaveType)
                        @php
                            $colorClasses = ['primary', 'success', 'info', 'warning', 'danger', 'dark'];
                            $colorClass = $colorClasses[$index % count($colorClasses)];
                            $percentage = $leaveType->days_allowed > 0 ? min(100, ($leaveType->days_taken / $leaveType->days_allowed) * 100) : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-{{ $colorClass }}">{{ $leaveType->name }}</span>
                                <span>{{ $leaveType->days_taken }} / {{ $leaveType->days_allowed }} days</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-{{ $colorClass }}" role="progressbar" 
                                     style="width: {{ $percentage }}%"
                                     aria-valuenow="{{ $leaveType->days_taken }}" aria-valuemin="0" aria-valuemax="{{ $leaveType->days_allowed }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Recent Leave Applications -->
                <div class="mt-4">
                    <h6 class="font-weight-bold text-primary mb-3">Recent Applications</h6>
                    @php
                        $recentLeaves = App\Models\Leave::where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();
                    @endphp
                    @forelse($recentLeaves as $leave)
                        <div class="leave-item border-left-{{ $leave->status == 'Approved' ? 'success' : ($leave->status == 'Pending' ? 'warning' : 'danger') }} pl-3 py-2 mb-2">
                            <div class="small text-gray-600">{{ date('d M, Y', strtotime($leave->start_date)) }} - {{ date('d M, Y', strtotime($leave->end_date)) }}</div>
                            <div class="font-weight-bold">{{ App\Models\LeaveType::find($leave->leave_type_id)->name ?? 'Unknown' }}</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small">{{ $leave->total_days }} days</span>
                                <span class="badge badge-{{ $leave->status == 'Approved' ? 'success' : ($leave->status == 'Pending' ? 'warning' : 'danger') }}">
                                    {{ $leave->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <p>No recent leave applications</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- My Tasks and Projects -->
<div class="row">
    <!-- My Tasks -->
    <div class="col-lg-6 mb-4">
        <!-- Todo Card for User -->
        <div class="card shadow mb-4">
            <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                <h6 class="m-0 font-weight-bold text-primary">My Tasks</h6>
                <div class="d-flex align-items-center">
                    <a class="btn btn-primary btn-icon btn-sm rounded-circle d-flex align-items-center justify-content-center p-0 mb-2"
                        href="#" data-toggle="modal" data-target="#add_todo">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($myTasks as $index => $task)
                    <div class="d-flex align-items-center todo-item border p-2 mb-2 rounded">
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
    
    <!-- My Projects Progress -->
    <div class="col-lg-6 mb-4">
        <!-- Project Progress Card for User -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">My Projects Progress</h6>
            </div>
            <div class="card-body">
                @forelse($myProjects as $index => $project)
                    @php
                        // Calculate progress - this is just an example
                        $completedTasks = $project->tasks->where('status', 'Completed')->count();
                        $totalTasks = $project->tasks->count();
                        $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                        $progress = round($progress);
                    @endphp
                    <h4 class="small font-weight-bold">
                        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
                        <span class="float-right">{{ $progress }}%</span>
                    </h4>
                    <div class="progress mb-4">
                        <div class="progress-bar" id="projectProgress{{ $index }}" role="progressbar" 
                             style="width: {{ $progress }}%"
                             aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <p>You are not assigned to any projects</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>