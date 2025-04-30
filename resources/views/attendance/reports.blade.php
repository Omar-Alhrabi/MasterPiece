@extends('layouts.admin')

@section('title', 'Attendance Reports')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Attendance Reports</h1>
        <div>
            <a href="{{ route('attendance.record') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-clock fa-sm text-white-50"></i> Record Attendance
            </a>
            <a href="{{ route('attendance.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm ml-2">
                <i class="fas fa-list fa-sm text-white-50"></i> View Attendance
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Options</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('attendance.reports') }}" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="user_id">Employee</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">All Employees</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Working Days</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $workingDays }}</div>
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
                                Present Days</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['present'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                Late Days</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['late'] }}</div>
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
                                Absent/Half Days</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['absent'] + $stats['half_day'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Progress -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Attendance Rate</h6>
        </div>
        <div class="card-body">
            <h4 class="small font-weight-bold">Overall Attendance <span class="float-right">{{ number_format($attendanceRate, 1) }}%</span></h4>
            <div class="progress mb-4">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $attendanceRate }}%"
                    aria-valuenow="{{ $attendanceRate }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            
            <div class="mt-4 row">
                <div class="col-md-3 text-center">
                    <div class="mb-1"><i class="fas fa-circle text-success"></i> Present</div>
                    <h4>{{ $stats['present'] }}</h4>
                    <p class="small">{{ number_format($stats['present'] / max(1, $workingDays) * 100, 1) }}%</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-1"><i class="fas fa-circle text-warning"></i> Late</div>
                    <h4>{{ $stats['late'] }}</h4>
                    <p class="small">{{ number_format($stats['late'] / max(1, $workingDays) * 100, 1) }}%</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-1"><i class="fas fa-circle text-info"></i> Half Day</div>
                    <h4>{{ $stats['half_day'] }}</h4>
                    <p class="small">{{ number_format($stats['half_day'] / max(1, $workingDays) * 100, 1) }}%</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-1"><i class="fas fa-circle text-danger"></i> Absent</div>
                    <h4>{{ $stats['absent'] }}</h4>
                    <p class="small">{{ number_format($stats['absent'] / max(1, $workingDays) * 100, 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Attendance Records</h6>
            <div>
                <a href="#" class="btn btn-sm btn-info" onclick="exportToCsv()">
                    <i class="fas fa-download fa-sm"></i> Export
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="attendance-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Working Hours</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ date('M d, Y', strtotime($attendance->date)) }}</td>
                                <td>
                                    @if($attendance->user)
                                        <a href="{{ route('employees.show', $attendance->user_id) }}">
                                            {{ $attendance->user->first_name }} {{ $attendance->user->last_name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Unknown Employee</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->check_in)
                                        {{ date('h:i A', strtotime($attendance->check_in)) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->check_out)
                                        {{ date('h:i A', strtotime($attendance->check_out)) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->status == 'Present')
                                        <span class="badge badge-success">Present</span>
                                    @elseif($attendance->status == 'Late')
                                        <span class="badge badge-warning">Late</span>
                                    @elseif($attendance->status == 'Half-day')
                                        <span class="badge badge-info">Half-day</span>
                                    @elseif($attendance->status == 'Absent')
                                        <span class="badge badge-danger">Absent</span>
                                    @else
                                        <span class="badge badge-primary">{{ $attendance->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->check_in && $attendance->check_out)
                                        @php
                                            $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                                            $checkOut = \Carbon\Carbon::parse($attendance->check_out);
                                            $hours = $checkOut->diffInHours($checkIn);
                                            $minutes = $checkOut->diffInMinutes($checkIn) % 60;
                                            echo sprintf('%d hrs %d mins', $hours, $minutes);
                                        @endphp
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $attendance->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No attendance records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#attendance-table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 10
        });
    });
    
    function exportToCsv() {
        var table = document.getElementById("attendance-table");
        var rows = [];
        
        // Get header row
        var headerRow = [];
        for (var i = 0; i < table.rows[0].cells.length; i++) {
            headerRow.push(table.rows[0].cells[i].innerText);
        }
        rows.push(headerRow.join(","));
        
        // Get data rows
        for (var i = 1; i < table.rows.length; i++) {
            var row = [];
            for (var j = 0; j < table.rows[i].cells.length; j++) {
                row.push('"' + table.rows[i].cells[j].innerText.replace(/"/g, '""') + '"');
            }
            rows.push(row.join(","));
        }
        
        // Create CSV content
        var csvContent = rows.join("\n");
        
        // Create download link
        var encodedUri = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "attendance_report_{{ date('Y-m-d') }}.csv");
        document.body.appendChild(link);
        
        // Download CSV file
        link.click();
    }
</script>
@endpush