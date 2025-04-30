@extends('layouts.admin')

@section('title', 'Salary Payment History')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Salary Payment History</h1>
        <div>
            <a href="{{ route('payroll.calculate') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-calculator fa-sm text-white-50"></i> Calculate Salary
            </a>
            <a href="{{ route('payroll.reports') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-chart-bar fa-sm text-white-50"></i> Reports
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Results</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('payroll.history') }}" method="GET">
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
                        <label for="month">Month</label>
                        <select class="form-control" id="month" name="month">
                            <option value="">All Months</option>
                            @foreach($months as $key => $monthName)
                                <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>
                                    {{ $monthName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="year">Year</label>
                        <select class="form-control" id="year" name="year">
                            <option value="">All Years</option>
                            @foreach($years as $yearOption)
                                <option value="{{ $yearOption }}" {{ $year == $yearOption ? 'selected' : '' }}>
                                    {{ $yearOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="is_paid">Payment Status</label>
                        <select class="form-control" id="is_paid" name="is_paid">
                            <option value="">All Status</option>
                            <option value="1" {{ $isPaid === '1' ? 'selected' : '' }}>Paid</option>
                            <option value="0" {{ $isPaid === '0' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('payroll.history') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Salary History Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Salary Records</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="salaryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Month/Year</th>
                            <th>Payment Date</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $salary)
                            <tr>
                                <td>{{ $salary->id }}</td>
                                <td>
                                    <a href="{{ route('employees.show', $salary->user) }}" class="font-weight-bold text-primary">
                                        {{ $salary->user->first_name }} {{ $salary->user->last_name }}
                                    </a>
                                </td>
                                <td>${{ number_format($salary->amount, 2) }}</td>
                                <td>
                                    @if($salary->type == 'basic')
                                        <span class="badge badge-primary">Basic</span>
                                    @elseif($salary->type == 'bonus')
                                        <span class="badge badge-success">Bonus</span>
                                    @elseif($salary->type == 'allowance')
                                        <span class="badge badge-info">Allowance</span>
                                    @elseif($salary->type == 'deduction')
                                        <span class="badge badge-danger">Deduction</span>
                                    @elseif($salary->type == 'overtime')
                                        <span class="badge badge-warning">Overtime</span>
                                    @endif
                                </td>
                                <td>{{ $months[$salary->month] }} {{ $salary->year }}</td>
                                <td>{{ $salary->payment_date->format('M d, Y') }}</td>
                                <td>
                                    @if($salary->payment_method == 'bank_transfer')
                                        <span class="badge badge-info">Bank Transfer</span>
                                    @elseif($salary->payment_method == 'cash')
                                        <span class="badge badge-success">Cash</span>
                                    @elseif($salary->payment_method == 'cheque')
                                        <span class="badge badge-primary">Cheque</span>
                                    @endif
                                </td>
                                <td>
                                    @if($salary->is_paid)
                                        <span class="badge badge-success">Paid</span>
                                    @else
                                        <span class="badge badge-warning">Unpaid</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('payroll.show', $salary) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('payroll.edit', $salary) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $salary->id }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal-{{ $salary->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $salary->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel-{{ $salary->id }}">Delete Salary Record</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this salary record? This action cannot be undone.
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('payroll.destroy', $salary) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No salary records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $salaries->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#salaryTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false,
        });
    });
</script>
@endpush