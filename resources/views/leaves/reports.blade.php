@extends('layouts.admin')

@section('title', 'Leave Reports')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Leave Reports</h1>
        <div>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm" id="generateReport">
                <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
            </a>
            <a href="{{ route('leaves.requests') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Leave Requests
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Options</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('leaves.reports') }}" method="GET" id="reportsFilterForm">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="user_id">Employee</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">All Employees</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="leave_type_id">Leave Type</label>
                        <select class="form-control" id="leave_type_id" name="leave_type_id">
                            <option value="">All Types</option>
                            @foreach($leaveTypes ?? [] as $type)
                                <option value="{{ $type->id }}" {{ $leaveTypeId == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Cancelled" {{ $status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Date Range</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="start_date" value="{{ $startDate ?? now()->startOfYear()->format('Y-m-d') }}">
                            <div class="input-group-append input-group-prepend">
                                <span class="input-group-text">to</span>
                            </div>
                            <input type="date" class="form-control" name="end_date" value="{{ $endDate ?? now()->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('leaves.reports') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Leaves</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaves->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved Leaves</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Leaves</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Days (Approved)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_days'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Report Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Leave Report</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Export Options:</div>
                    <a class="dropdown-item" href="#" id="exportPDF">
                        <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-gray-400"></i>
                        Export as PDF
                    </a>
                    <a class="dropdown-item" href="#" id="exportExcel">
                        <i class="fas fa-file-excel fa-sm fa-fw mr-2 text-gray-400"></i>
                        Export as Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="reportsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Applied On</th>
                            <th>Approved By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td>{{ $leave->user->first_name }} {{ $leave->user->last_name }}</td>
                                <td>{{ $leave->leaveType->name }}</td>
                                <td>{{ date('M d, Y', strtotime($leave->start_date)) }}</td>
                                <td>{{ date('M d, Y', strtotime($leave->end_date)) }}</td>
                                <td>{{ $leave->total_days }}</td>
                                <td>
                                    @if($leave->status == 'Approved')
                                        <span class="badge badge-success">{{ $leave->status }}</span>
                                    @elseif($leave->status == 'Pending')
                                        <span class="badge badge-warning">{{ $leave->status }}</span>
                                    @elseif($leave->status == 'Rejected')
                                        <span class="badge badge-danger">{{ $leave->status }}</span>
                                    @elseif($leave->status == 'Cancelled')
                                        <span class="badge badge-secondary">{{ $leave->status }}</span>
                                    @else
                                        <span class="badge badge-primary">{{ $leave->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $leave->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($leave->approvedBy)
                                        {{ $leave->approvedBy->first_name }} {{ $leave->approvedBy->last_name }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No leave records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Leave Type Distribution Chart -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Leave Distribution by Type</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="leaveTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Leave Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="leaveStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // DataTable with export options
        var table = $('#reportsTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        
        // Hide default buttons
        $('.dt-buttons').hide();
        
        // Connect custom buttons to DataTables buttons
        $('#exportPDF').on('click', function() {
            table.button('.buttons-pdf').trigger();
        });
        
        $('#exportExcel').on('click', function() {
            table.button('.buttons-excel').trigger();
        });
        
        $('#generateReport').on('click', function() {
            table.button('.buttons-pdf').trigger();
        });
        
        // Leave Type Distribution Chart
        @if(isset($leaves) && $leaves->count() > 0)
        // Process data for charts
        var leaveTypes = {};
        var leaveStatuses = {
            'Pending': 0,
            'Approved': 0,
            'Rejected': 0,
            'Cancelled': 0
        };
        
        @foreach($leaves as $leave)
            // For Leave Type chart
            if ('{{ $leave->leaveType->name }}' in leaveTypes) {
                leaveTypes['{{ $leave->leaveType->name }}']++;
            } else {
                leaveTypes['{{ $leave->leaveType->name }}'] = 1;
            }
            
            // For Status chart
            leaveStatuses['{{ $leave->status }}']++;
        @endforeach
        
        // Leave Type Chart
        var typeLabels = Object.keys(leaveTypes);
        var typeData = Object.values(leaveTypes);
        var typeColors = generateRandomColors(typeLabels.length);
        
        var typeCtx = document.getElementById('leaveTypeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'pie',
            data: {
                labels: typeLabels,
                datasets: [{
                    data: typeData,
                    backgroundColor: typeColors,
                    hoverBackgroundColor: typeColors,
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true,
                    position: 'bottom'
                },
                cutoutPercentage: 0,
            },
        });
        
        // Leave Status Chart
        var statusLabels = Object.keys(leaveStatuses);
        var statusData = Object.values(leaveStatuses);
        var statusColors = ['#f6c23e', '#1cc88a', '#e74a3b', '#858796'];
        
        var statusCtx = document.getElementById('leaveStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: statusColors,
                    hoverBackgroundColor: statusColors,
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true,
                    position: 'bottom'
                },
                cutoutPercentage: 80,
            },
        });
        @endif
        
        // Helper function to generate random colors
        function generateRandomColors(count) {
            var colors = [];
            for (var i = 0; i < count; i++) {
                var color = '#' + Math.floor(Math.random()*16777215).toString(16);
                colors.push(color);
            }
            return colors;
        }
    });
</script>
@endpush