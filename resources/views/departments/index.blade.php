@extends('layouts.admin')

@section('title', 'Departments')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Departments</h1>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('departments.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Department
        </a>
        @endif
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Department Search & Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('departments.index') }}" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="search">Search</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Department Name..." value="{{ request('search') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="manager_id">Manager</label>
                        <select class="form-control" id="manager_id" name="manager_id">
                            <option value="">All Managers</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ request('manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->first_name }} {{ $manager->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="employees">Employees</label>
                        <select class="form-control" id="employees" name="employees">
                            <option value="">All Departments</option>
                            <option value="with_employees" {{ request('employees') == 'with_employees' ? 'selected' : '' }}>With Employees</option>
                            <option value="without_employees" {{ request('employees') == 'without_employees' ? 'selected' : '' }}>Without Employees</option>
                        </select>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Departments List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Departments</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Manager</th>
                            <th>Employees</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <td>{{ $department->id }}</td>
                                <td>
                                    <a href="{{ route('departments.show', $department) }}" class="font-weight-bold text-primary">
                                        {{ $department->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($department->manager)
                                        <a href="{{ route('employees.show', $department->manager) }}">
                                            {{ $department->manager->first_name }} {{ $department->manager->last_name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not Assigned</span>
                                    @endif
                                </td>
                                <td>{{ $department->users_count }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('departments.show', $department) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                        
                                        <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal-{{ $department->id }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal-{{ $department->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $department->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel-{{ $department->id }}">Delete Department</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the department <strong>{{ $department->name }}</strong>? This action cannot be undone.
                                            @if($department->users_count > 0)
                                                <div class="alert alert-warning mt-3">
                                                    <i class="fas fa-exclamation-triangle"></i> This department has {{ $department->users_count }} employees. You must reassign them before deleting.
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="{{ route('departments.destroy', $department) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" {{ $department->users_count > 0 ? 'disabled' : '' }}>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No departments found</td>
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
                        @if ($departments->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $departments->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        <!-- Pagination Elements -->
                        @for ($i = 1; $i <= $departments->lastPage(); $i++)
                            <li class="page-item {{ ($departments->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $departments->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        <!-- Next Page Link -->
                        @if ($departments->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $departments->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Next">
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