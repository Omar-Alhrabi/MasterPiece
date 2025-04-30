@extends('layouts.admin')

@section('title', 'Calculate Salaries')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Calculate Salaries</h1>
        <a href="{{ route('payroll.history') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-list fa-sm text-white-50"></i> Payment History
        </a>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Calculate Salary Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Salary Calculation</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('payroll.process') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">Select Employee <span class="text-danger">*</span></label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Select Employee</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }} - {{ $user->jobPosition ? $user->jobPosition->title : 'No Position' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Current Base Salary</label>
                            <input type="text" class="form-control" id="current_salary" value="" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="month">Month <span class="text-danger">*</span></label>
                            <select class="form-control @error('month') is-invalid @enderror" id="month" name="month" required>
                                <option value="">Select Month</option>
                                @foreach($months as $key => $month)
                                    <option value="{{ $key }}" {{ old('month', date('n')) == $key ? 'selected' : '' }}>
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                            @error('month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="year">Year <span class="text-danger">*</span></label>
                            <select class="form-control @error('year') is-invalid @enderror" id="year" name="year" required>
                                <option value="">Select Year</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ old('year', date('Y')) == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="card bg-light mt-4 mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Attendance Summary</h6>
                    </div>
                    <div class="card-body" id="attendance-summary">
                        <div class="text-center py-3">
                            <p class="text-muted">Select an employee and month to view attendance summary.</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">Calculate & Process Salary</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Update salary when employee changes
        $('#user_id').change(function() {
            var userId = $(this).val();
            if (userId) {
                // Get employee salary information
                $.ajax({
                    url: "{{ route('api.employees.salary') }}",
                    type: 'GET',
                    data: {user_id: userId},
                    success: function(data) {
                        $('#current_salary').val('$' + parseFloat(data.salary).toFixed(2));
                    },
                    error: function() {
                        $('#current_salary').val('Not available');
                    }
                });
                
                // Check and update attendance summary
                updateAttendanceSummary();
            } else {
                $('#current_salary').val('');
                $('#attendance-summary').html('<div class="text-center py-3"><p class="text-muted">Select an employee and month to view attendance summary.</p></div>');
            }
        });
        
        // Update attendance summary when month or year changes
        $('#month, #year').change(function() {
            updateAttendanceSummary();
        });
        
        function updateAttendanceSummary() {
            var userId = $('#user_id').val();
            var month = $('#month').val();
            var year = $('#year').val();
            
            if (userId && month && year) {
                // Get attendance summary for the selected employee and period
                $.ajax({
                    url: "{{ route('api.attendance.summary') }}",
                    type: 'GET',
                    data: {user_id: userId, month: month, year: year},
                    success: function(data) {
                        var html = '<div class="row">';
                        html += '<div class="col-md-3 text-center"><div class="h4 mb-0 text-primary">' + data.working_days + '</div><div class="small text-muted">Working Days</div></div>';
                        html += '<div class="col-md-3 text-center"><div class="h4 mb-0 text-success">' + data.present_days + '</div><div class="small text-muted">Present Days</div></div>';
                        html += '<div class="col-md-3 text-center"><div class="h4 mb-0 text-warning">' + data.half_days + '</div><div class="small text-muted">Half Days</div></div>';
                        html += '<div class="col-md-3 text-center"><div class="h4 mb-0 text-danger">' + data.absent_days + '</div><div class="small text-muted">Absent Days</div></div>';
                        html += '</div>';
                        
                        html += '<div class="row mt-4">';
                        html += '<div class="col-md-3 text-center"><div class="h4 mb-0 text-info">' + data.paid_leaves + '</div><div class="small text-muted">Paid Leaves</div></div>';
                        html += '<div class="col-md-3 text-center"><div class="h4 mb-0 text-secondary">' + data.unpaid_leaves + '</div><div class="small text-muted">Unpaid Leaves</div></div>';
                        html += '<div class="col-md-3 text-center"><div class="h4 mb-0 text-primary">' + data.overtime_hours + '</div><div class="small text-muted">Overtime Hours</div></div>';
                        html += '<div class="col-md-3 text-center"><div class="h4 mb-0 text-success">$' + parseFloat(data.estimated_salary).toFixed(2) + '</div><div class="small text-muted">Estimated Salary</div></div>';
                        html += '</div>';
                        
                        $('#attendance-summary').html(html);
                    },
                    error: function() {
                        $('#attendance-summary').html('<div class="text-center py-3"><p class="text-danger">Could not load attendance data. Please try again.</p></div>');
                    }
                });
            } else {
                $('#attendance-summary').html('<div class="text-center py-3"><p class="text-muted">Select an employee and month to view attendance summary.</p></div>');
            }
        }
    });
</script>
@endpush