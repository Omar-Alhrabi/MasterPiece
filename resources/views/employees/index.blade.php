@extends('layouts.admin')

@section('title', 'Employees')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employees</h1>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('employees.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Employee
        </a>
        @endif
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Employee Search & Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('employees.index') }}" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="search">Search</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Name, Email, Phone..." value="{{ request('search') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="department_id">Department</label>
                        <select class="form-control" id="department_id" name="department_id">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="job_position_id">Job Position</label>
                        <select class="form-control" id="job_position_id" name="job_position_id">
                            <option value="">All Positions</option>
                            @foreach($jobPositions as $position)
                                <option value="{{ $position->id }}" {{ request('job_position_id') == $position->id ? 'selected' : '' }}>
                                    {{ $position->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="employment_status">Status</label>
                        <select class="form-control" id="employment_status" name="employment_status">
                            <option value="">All Statuses</option>
                            <option value="full-time" {{ request('employment_status') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ request('employment_status') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ request('employment_status') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="intern" {{ request('employment_status') == 'intern' ? 'selected' : '' }}>Intern</option>
                            <option value="terminated" {{ request('employment_status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Employees List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Employees</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Position</th>
                            @if(Auth::user()->isAdmin())
                            <th>Status</th>
                            @endif
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar mr-2">
                                            <img src="{{ asset('https://images.icon-icons.com/1378/PNG/512/avatardefault_92824.png') }}" alt="Profile" class="rounded-circle" width="40">
                                        </div>
                                        <div>
                                            <a href="{{ route('employees.show', $employee) }}" class="font-weight-bold text-primary">
                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->department ? $employee->department->name : 'Not Assigned' }}</td>
                                <td>{{ $employee->jobPosition ? $employee->jobPosition->title : 'Not Assigned' }}</td>
                                @if(Auth::user()->isAdmin())
                                <td>
                                    @if($employee->employment_status == 'full-time')
                                        <span class="badge badge-success">Full Time</span>
                                    @elseif($employee->employment_status == 'part-time')
                                        <span class="badge badge-info">Part Time</span>
                                    @elseif($employee->employment_status == 'contract')
                                        <span class="badge badge-warning">Contract</span>
                                    @elseif($employee->employment_status == 'intern')
                                        <span class="badge badge-secondary">Intern</span>
                                    @elseif($employee->employment_status == 'terminated')
                                        <span class="badge badge-danger">Terminated</span>
                                    @else
                                        <span class="badge badge-primary">{{ $employee->employment_status }}</span>
                                    @endif
                                </td>
                                @endif
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                         
                                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal-{{ $employee->id }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal-{{ $employee->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $employee->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel-{{ $employee->id }}">Delete Employee</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>? This action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No employees found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination with custom styling -->
            <div class="d-flex justify-content-end mt-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <!-- Previous Page Link -->
                        @if ($employees->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $employees->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        <!-- Pagination Elements -->
                        @for ($i = 1; $i <= $employees->lastPage(); $i++)
                            <li class="page-item {{ ($employees->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $employees->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        <!-- Next Page Link -->
                        @if ($employees->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $employees->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Next">
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
        // Remove DataTables initialization to avoid conflict with Laravel pagination
        // This allows Laravel's pagination to work properly with more than 10 employees
    });
</script>
@endpush