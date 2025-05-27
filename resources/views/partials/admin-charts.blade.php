<!-- Admin Dashboard Charts - Fixed and Optimized -->
<div class="row">
    <!-- Area Chart - Earnings -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                        <div class="dropdown-header">View Options:</div>
                        <a class="dropdown-item" href="#">Monthly</a>
                        <a class="dropdown-item" href="#">Quarterly</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">View Full Report</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="earningsAreaChart"></canvas>
                </div>
                <!-- Hidden earnings data for chart -->
                <div id="earnings-data" style="display: none;"
                    data-earnings="{{ json_encode($earningsData ?? [0, 10000, 5000, 15000, 10000, 20000, 15000, 25000, 20000, 30000, 25000, 40000]) }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart - Project Status -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Project Status</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                        <div class="dropdown-header">View Options:</div>
                        <a class="dropdown-item" href="#">All Projects</a>
                        <a class="dropdown-item" href="#">Active Projects</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">View Details</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="projectStatusPieChart"></canvas>
                </div>

                <!-- Project status data for chart -->
                <div id="project-stats-data" style="display:none;"
                    data-pending="{{ $projectStats['pending'] ?? 0 }}"
                    data-in-progress="{{ $projectStats['in_progress'] ?? 0 }}"
                    data-completed="{{ $projectStats['completed'] ?? 0 }}"
                    data-on-hold="{{ $projectStats['on_hold'] ?? 0 }}"
                    data-cancelled="{{ $projectStats['cancelled'] ?? 0 }}">
                </div>

                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-warning"></i> Pending
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> In Progress
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Completed
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> On Hold
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-danger"></i> Cancelled
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row Charts -->
<div class="row">
    <!-- Bar Chart - Staff by Department -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Staff by Department</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                        <div class="dropdown-header">View Options:</div>
                        <a class="dropdown-item" href="#">All Departments</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Manage Departments</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-bar">
                    <canvas id="departmentBarChart"></canvas>
                </div>

                <!-- Department data for chart -->
                <div id="department-stats-data" style="display:none;"
                    data-names='{{ json_encode($departments->pluck("name") ?? ["HR", "IT", "Marketing", "Sales", "Finance"]) }}'
                    data-counts='{{ json_encode($departments->pluck("users_count") ?? [4, 8, 6, 12, 5]) }}'>
                </div>
            </div>
        </div>
    </div>

    <!-- Line Chart - Monthly Task Completion -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Task Completion</h6>
            </div>
            <div class="card-body">
                <div class="chart-line">
                    <canvas id="taskCompletionLineChart"></canvas>
                </div>

                <!-- Task data for chart -->
                @php
                // Generate task completion data for the past 6 months if not provided
                $taskData = isset($taskCompletionData) ? $taskCompletionData : [15, 22, 19, 27, 30, 25];
                @endphp
                <div id="task-data" style="display:none;"
                    data-tasks='{{ json_encode($taskData) }}'>
                </div>
            </div>
        </div>
    </div>
</div>