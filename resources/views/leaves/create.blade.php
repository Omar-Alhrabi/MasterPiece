@extends('layouts.admin')

@section('title', 'Create Leave Request')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Leave Request</h1>
        <a href="{{ route('leaves.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Leaves
        </a>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Create Leave Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Leave Request Form</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('leaves.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="leave_type_id">Leave Type <span class="text-danger">*</span></label>
                    <select class="form-control @error('leave_type_id') is-invalid @enderror" id="leave_type_id" name="leave_type_id" required>
                        <option value="">Select Leave Type</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} ({{ $type->is_paid ? 'Paid' : 'Unpaid' }})
                            </option>
                        @endforeach
                    </select>
                    @error('leave_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="start_date">Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end_date">End Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="reason">Reason <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('reason') is-invalid @enderror" 
                              id="reason" name="reason" rows="3" required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Leave Balance</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Allowed</th>
                                        <th>Used</th>
                                        <th>Available</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveTypes as $type)
                                        @php
                                            $usedLeaves = App\Models\Leave::where('user_id', Auth::id())
                                                ->where('leave_type_id', $type->id)
                                                ->where('status', 'Approved')
                                                ->whereYear('start_date', date('Y'))
                                                ->sum('total_days');
                                            
                                            $available = $type->days_allowed - $usedLeaves;
                                        @endphp
                                        <tr>
                                            <td>{{ $type->name }}</td>
                                            <td>{{ $type->days_allowed > 0 ? $type->days_allowed : 'Unlimited' }}</td>
                                            <td>{{ $usedLeaves }}</td>
                                            <td>{{ $type->days_allowed > 0 ? $available : 'Unlimited' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-5">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Calculate days between start date and end date
        function calculateDays() {
            var startDate = new Date($('#start_date').val());
            var endDate = new Date($('#end_date').val());
            
            if (startDate && endDate && startDate <= endDate) {
                var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 to include both start and end dates
                
                $('#total_days').text(diffDays);
            } else {
                $('#total_days').text('0');
            }
        }
        
        $('#start_date, #end_date').change(function() {
            calculateDays();
        });
        
        calculateDays();
    });
</script>
@endpush