@extends('layouts.admin')

@section('title', 'Payroll Management')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payroll Management</h1>
        <div>
            <a href="{{ route('payroll.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add New Salary Record
            </a>
            <a href="{{ route('payroll.calculate') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm ml-2">
                <i class="fas fa-calculator fa-sm text-white-50"></i> Calculate Salaries
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Salary Records Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Salary Records</h6>
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
                            <th>Description</th>
                            <th>Payment Date</th>
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
                                <td>{{ $salary->description }}</td>
                                <td>{{ $salary->payment_date->format('M d, Y') }}</td>
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
                                                    Are you sure you want to delete this salary record for {{ $salary->user->first_name }} {{ $salary->user->last_name }}? This action cannot be undone.
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
                                <td colspan="8" class="text-center">No salary records found</td>
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
            @if ($salaries->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $salaries->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @endif

            <!-- Pagination Elements -->
            @for ($i = 1; $i <= $salaries->lastPage(); $i++)
                <li class="page-item {{ ($salaries->currentPage() == $i) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $salaries->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <!-- Next Page Link -->
            @if ($salaries->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $salaries->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Next">
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
        $('#salaryTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false,
        });
    });
</script>
@endpush