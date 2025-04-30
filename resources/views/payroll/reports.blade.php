@extends('layouts.admin')

@section('title', 'Payroll Reports')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payroll Reports</h1>
        <div>
            <a href="{{ route('payroll.calculate') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-calculator fa-sm text-white-50"></i> Calculate Salary
            </a>
            <a href="{{ route('payroll.history') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-list fa-sm text-white-50"></i> Payment History
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Select Year</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('payroll.reports') }}" method="GET" class="form-inline justify-content-center">
                <div class="form-group mx-sm-3">
                    <label for="year" class="mr-2">Year:</label>
                    <select class="form-control" id="year" name="year">
                        @foreach($years as $yearOption)
                            <option value="{{ $yearOption }}" {{ $year == $yearOption ? 'selected' : '' }}>
                                {{ $yearOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">View Reports</button>
            </form>
        </div>
    </div>

    <!-- Monthly Salary Summary -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Monthly Salary Summary for {{ $year }}</h6>
        </div>
        <div class="card-body">
           <!--  <div class="chart-area mb-4" style="height: 300px;">
                <canvas id="monthlySalaryChart"></canvas>
            </div> -->
            
            <div class="table-responsive">
                <table class="table table-bordered" id="monthlySummaryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Salaries</th>
                            <th>Number of Employees</th>
                            <th>Average Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $monthNames = [
                                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                            ];
                            
                            $employeeCountByMonth = [];
                            foreach ($monthlyData as $data) {
                                foreach ($data['months'] as $month => $amount) {
                                    if ($amount > 0) {
                                        $employeeCountByMonth[$month] = ($employeeCountByMonth[$month] ?? 0) + 1;
                                    }
                                }
                            }
                        @endphp
                        
                        @foreach($monthNames as $monthNum => $monthName)
                            <tr>
                                <td>{{ $monthName }}</td>
                                <td>${{ number_format($monthlyTotals[$monthNum], 2) }}</td>
                                <td>{{ $employeeCountByMonth[$monthNum] ?? 0 }}</td>
                                <td>
                                    @if(isset($employeeCountByMonth[$monthNum]) && $employeeCountByMonth[$monthNum] > 0)
                                        ${{ number_format($monthlyTotals[$monthNum] / $employeeCountByMonth[$monthNum], 2) }}
                                    @else
                                        $0.00
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        
                        <tr class="font-weight-bold bg-light">
                            <td>Total</td>
                            <td>${{ number_format($totalSalaries, 2) }}</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Employee Salary Details -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Employee Salary Details for {{ $year }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="employeeSalaryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            @foreach($monthNames as $monthName)
                                <th>{{ $monthName }}</th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyData as $data)
                            <tr>
                                <td>
                                    <a href="{{ route('employees.show', $data['user']) }}" class="font-weight-bold text-primary">
                                        {{ $data['user']->first_name }} {{ $data['user']->last_name }}
                                    </a>
                                </td>
                                @foreach($monthNames as $monthNum => $monthName)
                                    <td>
                                        @if($data['months'][$monthNum] > 0)
                                            ${{ number_format($data['months'][$monthNum], 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                                <td class="font-weight-bold">${{ number_format($data['total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#employeeSalaryTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "scrollX": true,
            "pageLength": 25,
        });
        
        $('#monthlySummaryTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": false,
            "info": false,
        });
        
        // Monthly Salary Chart
        var ctx = document.getElementById("monthlySalaryChart");
        var monthlySalaryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Total Salaries",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: [
                        @foreach(range(1, 12) as $month)
                            {{ $monthlyTotals[$month] }},
                        @endforeach
                    ],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    },
                    y: {
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: "rgba(0, 0, 0, 0.1)",
                            zeroLineColor: "rgba(0, 0, 0, 0.1)",
                            drawBorder: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                return label + ': $' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush