<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report - {{ $monthName }} {{ $year }}</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 20px;
            }

            .page-break {
                page-break-before: always;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }

        .report-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .report-section {
            margin-bottom: 40px;
        }

        .data-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            padding: 10px;
            text-align: left;
        }

        .summary-box {
            background-color: #f8f9fc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .chart-container {
            height: 300px;
            margin-bottom: 20px;
        }

        /* New styles for the header */
        .report-brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #4e73df;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 60px;
            height: 60px;
            background-color: #4e73df;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 24px;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-weight: 800;
            font-size: 22px;
            color: #4e73df;
            margin: 0;
        }

        .brand-slogan {
            font-size: 14px;
            color: #858796;
            margin: 0;
        }

        .report-date {
            background-color: #f8f9fc;
            padding: 8px 15px;
            border-radius: 5px;
            color: #5a5c69;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="no-print mb-4 btn ">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- New branded header -->
        <div class="report-brand">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="brand-text">
                    <h1 class="brand-name ">My Company</h1>
                    <p class="brand-slogan">Performance Management Solution</p>
                </div>
            </div>
            <div class="report-date">
                <i class="fas fa-calendar-alt mr-2"></i> Generated on {{ date('F d, Y') }}
            </div>
        </div>

        <div class="report-header">
            <h1 class="h2 mb-2 text-gray-800">Monthly Performance Report</h1>
            <h2 class="h4 mb-0 text-gray-600">{{ $monthName }} {{ $year }}</h2>

            <div class="no-print mt-3">
                <form action="{{ route('report.generate') }}" method="GET" class="form-inline justify-content-center">
                    <div class="form-group mr-2">
                        <select class="form-control" name="month">
                            <option value="1" {{ $selectedMonth == 1 ? 'selected' : '' }}>January</option>
                            <option value="2" {{ $selectedMonth == 2 ? 'selected' : '' }}>February</option>
                            <option value="3" {{ $selectedMonth == 3 ? 'selected' : '' }}>March</option>
                            <option value="4" {{ $selectedMonth == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ $selectedMonth == 5 ? 'selected' : '' }}>May</option>
                            <option value="6" {{ $selectedMonth == 6 ? 'selected' : '' }}>June</option>
                            <option value="7" {{ $selectedMonth == 7 ? 'selected' : '' }}>July</option>
                            <option value="8" {{ $selectedMonth == 8 ? 'selected' : '' }}>August</option>
                            <option value="9" {{ $selectedMonth == 9 ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $selectedMonth == 10 ? 'selected' : '' }}>October</option>
                            <option value="11" {{ $selectedMonth == 11 ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $selectedMonth == 12 ? 'selected' : '' }}>December</option>
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <select class="form-control" name="year">
                            @php
                            $currentYear = date('Y');
                            $startYear = $currentYear - 5;
                            @endphp
                            @for($yr = $currentYear; $yr >= $startYear; $yr--)
                            <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-sync"></i> Change Period
                    </button>
                </form>
            </div>
        </div>
        <!-- Executive Summary -->
        <div class="report-section">
            <h3 class="h3 mb-3 text-gray-800">Executive Summary</h3>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="summary-box">
                        <h4 class="h5 mb-2">Client Overview</h4>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1">Total Clients:</p>
                                <h5 class="text-primary mb-3">{{ $totalClients }}</h5>

                                <p class="mb-1">New Clients This Month:</p>
                                <h5 class="text-success mb-0">{{ $newClientsCount }}</h5>
                            </div>
                            <div class="col-6 text-right">
                                <i class="fas fa-handshake fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="summary-box">
                        <h4 class="h5 mb-2">Project Overview</h4>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1">Total Projects:</p>
                                <h5 class="text-primary mb-3">{{ $totalProjects }}</h5>

                                <p class="mb-1">New Projects This Month:</p>
                                <h5 class="text-info mb-0">{{ $newProjectsCount }}</h5>
                            </div>
                            <div class="col-6 text-right">
                                <i class="fas fa-project-diagram fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="summary-box">
                        <h4 class="h5 mb-2">Project Completion</h4>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1">Completed Projects:</p>
                                <h5 class="text-success mb-3">{{ $completedProjectsCount }}</h5>

                                <p class="mb-1">Completion Rate:</p>
                                <h5 class="text-warning mb-0">
                                    {{ $newProjectsCount > 0 ? round(($completedProjectsCount / $newProjectsCount) * 100, 1) : 0 }}%
                                </h5>
                            </div>
                            <div class="col-6 text-right">
                                <i class="fas fa-check-circle fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="summary-box">
                        <h4 class="h5 mb-2">Financial Overview</h4>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1">Monthly Earnings:</p>
                                <h5 class="text-success mb-3">${{ number_format($monthlyEarnings, 2) }}</h5>

                                <p class="mb-1">Avg. Project Value:</p>
                                <h5 class="text-info mb-0">
                                    ${{ $completedProjectsCount > 0 ? number_format($monthlyEarnings / $completedProjectsCount, 2) : 0 }}
                                </h5>
                            </div>
                            <div class="col-6 text-right">
                                <i class="fas fa-dollar-sign fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Overview -->
        <div class="report-section">
            <h3 class="h3 mb-3 text-gray-800">Earnings Overview</h3>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Earnings</th>
                                    <th>Projects Completed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                $totalEarnings = 0;
                                @endphp

                                @foreach($earningsData as $month => $earnings)
                                @php
                                $completedCount = App\Models\Project::where('status', 'Completed')
                                ->whereYear('updated_at', $year)
                                ->whereMonth('updated_at', $month)
                                ->count();
                                $totalEarnings += $earnings;
                                @endphp
                                <tr>
                                    <td>{{ $months[$month-1] }}</td>
                                    <td>${{ number_format($earnings, 2) }}</td>
                                    <td>{{ $completedCount }}</td>
                                </tr>
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td>Total</td>
                                    <td>${{ number_format($totalEarnings, 2) }}</td>
                                    <td>{{ App\Models\Project::where('status', 'Completed')->whereYear('updated_at', $year)->count() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Status -->
        <div class="report-section">
            <div class="page-break"></div>
            <h3 class="h3 mb-3 text-gray-800">Project Status Overview</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Project Status Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="projectStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Project Status Details</h6>
                        </div>
                        <div class="card-body">
                            <table class="data-table table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Count</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totalStatusCount = array_sum($projectStats);
                                    @endphp

                                    <tr>
                                        <td>Pending</td>
                                        <td>{{ $projectStats['pending'] }}</td>
                                        <td>{{ $totalStatusCount > 0 ? round(($projectStats['pending'] / $totalStatusCount) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    <tr>
                                        <td>In Progress</td>
                                        <td>{{ $projectStats['in_progress'] }}</td>
                                        <td>{{ $totalStatusCount > 0 ? round(($projectStats['in_progress'] / $totalStatusCount) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    <tr>
                                        <td>Completed</td>
                                        <td>{{ $projectStats['completed'] }}</td>
                                        <td>{{ $totalStatusCount > 0 ? round(($projectStats['completed'] / $totalStatusCount) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    <tr>
                                        <td>On Hold</td>
                                        <td>{{ $projectStats['on_hold'] }}</td>
                                        <td>{{ $totalStatusCount > 0 ? round(($projectStats['on_hold'] / $totalStatusCount) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    <tr>
                                        <td>Cancelled</td>
                                        <td>{{ $projectStats['cancelled'] }}</td>
                                        <td>{{ $totalStatusCount > 0 ? round(($projectStats['cancelled'] / $totalStatusCount) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>Total</td>
                                        <td>{{ $totalStatusCount }}</td>
                                        <td>100%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Projects This Month -->
        <div class="report-section">
            <div class="page-break"></div>
            <h3 class="h3 mb-3 text-gray-800">New Projects ({{ $monthName }} {{ $year }})</h3>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Client</th>
                                    <th>Budget</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                <tr>
                                    <td>PRO-{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $project->client ? $project->client->name : 'N/A' }}</td>
                                    <td>${{ number_format($project->budget ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $project->status == 'Completed' ? 'success' : ($project->status == 'In Progress' ? 'info' : ($project->status == 'On Hold' ? 'warning' : ($project->status == 'Cancelled' ? 'danger' : 'secondary'))) }}">
                                            {{ $project->status }}
                                        </span>
                                    </td>
                                    <td>{{ $project->start_date ? date('d/m/Y', strtotime($project->start_date)) : 'N/A' }}</td>
                                    <td>{{ $project->end_date ? date('d/m/Y', strtotime($project->end_date)) : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No new projects this month</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Clients This Month -->
        <div class="report-section">
            <h3 class="h3 mb-3 text-gray-800">New Clients ({{ $monthName }} {{ $year }})</h3>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Date Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($newClients as $client)
                                <tr>
                                    <td>CLT-{{ str_pad($client->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->company_name ?? 'N/A' }}</td>
                                    <td>{{ $client->email ?? 'N/A' }}</td>
                                    <td>{{ $client->phone ?? 'N/A' }}</td>
                                    <td>{{ date('d/m/Y', strtotime($client->created_at)) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No new clients this month</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Data -->
        <div class="report-section">
            <div class="page-break"></div>
            <h3 class="h3 mb-3 text-gray-800">Performance Metrics</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Top Clients by Project Count</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="data-table table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Company</th>
                                            <th>Projects</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topClients as $client)
                                        <tr>
                                            <td>{{ $client->name }}</td>
                                            <td>{{ $client->company_name ?? 'N/A' }}</td>
                                            <td>{{ $client->projects_count }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No client data available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Top Employees by Completed Tasks</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="data-table table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Department</th>
                                            <th>Tasks Completed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topEmployees as $employee)
                                        <tr>
                                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                            <td>{{ $employee->department ? $employee->department->name : 'N/A' }}</td>
                                            <td>{{ $employee->completed_tasks }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No employee data available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer with brand -->
        <div class="text-center mt-4 mb-5">
            <div class="text-muted">
                <small>
                    <p class="mb-1">My Company | Performance Management Report</p>
                    <p>© {{ date('Y') }} Your Company. All rights reserved.</p>
                </small>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Project Status chart
            var statusCtx = document.getElementById('projectStatusChart').getContext('2d');
            var statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Completed', 'On Hold', 'Cancelled'],
                    datasets: [{
                        data: [{
                                {
                                    $projectStats['pending']
                                }
                            },
                            {
                                {
                                    $projectStats['in_progress']
                                }
                            },
                            {
                                {
                                    $projectStats['completed']
                                }
                            },
                            {
                                {
                                    $projectStats['on_hold']
                                }
                            },
                            {
                                {
                                    $projectStats['cancelled']
                                }
                            }
                        ],
                        backgroundColor: ['#f6c23e', '#4e73df', '#1cc88a', '#36b9cc', '#e74a3b'],
                        hoverBackgroundColor: ['#dda20a', '#2e59d9', '#17a673', '#2c9faf', '#be2617'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var total = dataset.data.reduce(function(previousValue, currentValue) {
                                    return previousValue + currentValue;
                                });
                                var currentValue = dataset.data[tooltipItem.index];
                                var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                                return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'right'
                    },
                    cutoutPercentage: 70,
                }
            });
        });
    </script>
</body>

</html>