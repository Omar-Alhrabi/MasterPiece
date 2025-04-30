@extends('layouts.admin')

@section('title', 'Attendance Records')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Attendance Records</h1>
        <div>
            <a href="{{ route('attendance.record') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-clock fa-sm text-white-50"></i> Record Attendance
            </a>
            <a href="{{ route('attendance.reports') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm ml-2">
                <i class="fas fa-chart-bar fa-sm text-white-50"></i> View Reports
            </a>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('attendance.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm ml-2">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add Attendance
            </a>
            @endif
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
            <form action="{{ route('attendance.index') }}" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="user_id">Employee</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="{{ Auth::id() }}" {{ $userId == Auth::id() ? 'selected' : '' }}>
                                My Attendance
                            </option>
                            @if(Auth::user()->isAdmin())
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $userId == $user->id && $userId != Auth::id() ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            @endif
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
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Records Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Attendance Records</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Working Hours</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ date('M d, Y', strtotime($attendance->date)) }}</td>
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
                                <td>
                                    <div class="btn-group">
                                        @if(Auth::user()->isAdmin() || Auth::id() == $attendance->user_id)
                                            <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        @if(Auth::user()->isAdmin())
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-toggle="modal" 
                                                    data-target="#deleteModal-{{ $attendance->id }}"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    @if(Auth::user()->isAdmin())
                                        <div class="modal fade" id="deleteModal-{{ $attendance->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $attendance->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel-{{ $attendance->id }}">Delete Attendance Record</h5>
                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete the attendance record for <strong>{{ date('M d, Y', strtotime($attendance->date)) }}</strong>? This action cannot be undone.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('attendance.destroy', $attendance) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No attendance records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <!-- Previous Page Link -->
            @if ($attendances->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $attendances->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @endif

            <!-- Pagination Elements -->
            @for ($i = 1; $i <= $attendances->lastPage(); $i++)
                <li class="page-item {{ ($attendances->currentPage() == $i) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $attendances->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <!-- Next Page Link -->
            @if ($attendances->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $attendances->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
</div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": false,
            "searching": true,
            "ordering": true,
            "info": false,
        });
    });
</script>
@endpush