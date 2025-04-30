@extends('layouts.admin')

@section('title', 'Leave Requests')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Leave Requests</h1>
        <a href="{{ route('leaves.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> New Leave Request
        </a>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Leave Search & Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('leaves.index') }}" method="GET">
                <div class="form-row">
                    @if(Auth::user()->isAdmin())
                    <div class="form-group col-md-3">
                        <label for="user_id">Employee</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">All Employees</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group col-md-3">
                        <label for="leave_type_id">Leave Type</label>
                        <select class="form-control" id="leave_type_id" name="leave_type_id">
                            <option value="">All Types</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="date_range">Date Range</label>
                        <input type="text" class="form-control daterange" id="date_range" name="date_range" 
                               value="{{ request('date_range', now()->startOfMonth()->format('m/d/Y') . ' - ' . now()->endOfMonth()->format('m/d/Y')) }}">
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Leave Requests List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Leave Requests</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="leavesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            @if(Auth::user()->isAdmin())
                            <th>Employee</th>
                            @endif
                            <th>Leave Type</th>
                            <th>Period</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td>{{ $leave->id }}</td>
                                @if(Auth::user()->isAdmin())
                                <td>
                                    <a href="{{ route('employees.show', $leave->user) }}">
                                        {{ $leave->user->first_name }} {{ $leave->user->last_name }}
                                    </a>
                                </td>
                                @endif
                                <td>{{ $leave->leaveType->name }}</td>
                                <td>
                                    {{ date('M d, Y', strtotime($leave->start_date)) }}
                                    @if($leave->start_date != $leave->end_date)
                                    - {{ date('M d, Y', strtotime($leave->end_date)) }}
                                    @endif
                                </td>
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
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($leave->status == 'Pending' && $leave->user_id == Auth::id())
                                        <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal-{{ $leave->id }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                        
                                        @if(Auth::user()->isAdmin() && $leave->status == 'Pending')
                                        <button type="button" class="btn btn-sm btn-success" 
                                                data-toggle="modal" 
                                                data-target="#approveModal-{{ $leave->id }}"
                                                title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-toggle="modal" 
                                                data-target="#rejectModal-{{ $leave->id }}"
                                                title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Delete Modal -->
                            @if($leave->status == 'Pending' && $leave->user_id == Auth::id())
                            <div class="modal fade" id="deleteModal-{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $leave->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel-{{ $leave->id }}">Delete Leave Request</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this leave request? This action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="{{ route('leaves.destroy', $leave) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Approve Modal -->
                            @if(Auth::user()->isAdmin() || (Auth::user()->id == $leave->user->manager_id && $leave->status == 'Pending'))
                            <div class="modal fade" id="approveModal-{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel-{{ $leave->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveModalLabel-{{ $leave->id }}">Approve Leave Request</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to approve this leave request?
                                            <ul class="mt-3">
                                                <li><strong>Employee:</strong> {{ $leave->user->first_name }} {{ $leave->user->last_name }}</li>
                                                <li><strong>Leave Type:</strong> {{ $leave->leaveType->name }}</li>
                                                <li><strong>Period:</strong> {{ date('M d, Y', strtotime($leave->start_date)) }} - {{ date('M d, Y', strtotime($leave->end_date)) }}</li>
                                                <li><strong>Total Days:</strong> {{ $leave->total_days }}</li>
                                                <li><strong>Reason:</strong> {{ $leave->reason }}</li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="{{ route('leaves.approve-action', $leave) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal-{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel-{{ $leave->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel-{{ $leave->id }}">Reject Leave Request</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to reject this leave request?
                                            <ul class="mt-3">
                                                <li><strong>Employee:</strong> {{ $leave->user->first_name }} {{ $leave->user->last_name }}</li>
                                                <li><strong>Leave Type:</strong> {{ $leave->leaveType->name }}</li>
                                                <li><strong>Period:</strong> {{ date('M d, Y', strtotime($leave->start_date)) }} - {{ date('M d, Y', strtotime($leave->end_date)) }}</li>
                                                <li><strong>Total Days:</strong> {{ $leave->total_days }}</li>
                                                <li><strong>Reason:</strong> {{ $leave->reason }}</li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="{{ route('leaves.reject', $leave) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->isAdmin() ? '7' : '6' }}" class="text-center">No leave requests found</td>
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
            @if ($leaves->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $leaves->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @endif

            <!-- Pagination Elements -->
            @for ($i = 1; $i <= $leaves->lastPage(); $i++)
                <li class="page-item {{ ($leaves->currentPage() == $i) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $leaves->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <!-- Next Page Link -->
            @if ($leaves->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $leaves->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Next">
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

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('#leavesTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false,
        });
        
        $('.daterange').daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'MM/DD/YYYY'
            }
        });
        
        $('.daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
        
        $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
@endpush