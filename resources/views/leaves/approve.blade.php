@extends('layouts.admin')

@section('title', 'Approve Leave Requests')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Approve Leave Requests</h1>
        <a href="{{ route('leaves.requests') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Leave Requests
        </a>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Leave Requests List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pending Leave Requests</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="leavesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Period</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td>{{ $leave->id }}</td>
                                <td>
                                    <a href="{{ route('employees.show', $leave->user) }}">
                                        {{ $leave->user->first_name }} {{ $leave->user->last_name }}
                                    </a>
                                </td>
                                <td>{{ $leave->leaveType->name }}</td>
                                <td>
                                    {{ date('M d, Y', strtotime($leave->start_date)) }}
                                    @if($leave->start_date != $leave->end_date)
                                    - {{ date('M d, Y', strtotime($leave->end_date)) }}
                                    @endif
                                </td>
                                <td>{{ $leave->total_days }}</td>
                                <td>{{ $leave->reason }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Approve Modal -->
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
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No pending leave requests found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#leavesTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false,
        });
    });
</script>
@endpush