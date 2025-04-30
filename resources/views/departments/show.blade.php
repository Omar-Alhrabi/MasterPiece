@extends('layouts.admin')

@section('title', $department->name)

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $department->name }}</h1>
        <div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Department
            </a>
        @endif
            <a href="{{ route('departments.index') }}" class="btn btn-sm btn-secondary shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Department Details -->
    <div class="row">
        <!-- Department Info Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Department Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-primary text-uppercase mb-1">Name:</label>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $department->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-primary text-uppercase mb-1">Manager:</label>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @if($department->manager)
                                <a href="{{ route('employees.show', $department->manager) }}" class="text-primary">
                                    {{ $department->manager->first_name }} {{ $department->manager->last_name }}
                                </a>
                            @else
                                <span class="text-muted">Not Assigned</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-primary text-uppercase mb-1">Created:</label>
                        <div class="text-gray-800">{{ $department->created_at->format('M d, Y') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-primary text-uppercase mb-1">Last Updated:</label>
                        <div class="text-gray-800">{{ $department->updated_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Department Stats Card -->
        <div class="col-xl-8 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Department Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Employees</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $department->users->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Job Positions</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $department->jobPositions->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Department ID</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $department->id }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-id-card fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($department->description)
                        <div class="mt-2">
                            <label class="text-xs font-weight-bold text-primary text-uppercase mb-1">Description:</label>
                            <div class="p-3 bg-white rounded">
                                {{ $department->description }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Department Employees -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Employees ({{ $department->users->count() }})</h6>
        </div>
        <div class="card-body">
            @if($department->users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($department->users as $employee)
                                <tr>
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
                                    <td>{{ $employee->jobPosition ? $employee->jobPosition->title : 'Not Assigned' }}</td>
                                    <td>{{ $employee->email }}</td>
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
                                    <td>
                                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                    <p class="mb-0">No employees assigned to this department yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Department Job Positions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Job Positions ({{ $department->jobPositions->count() }})</h6>
        </div>
        <div class="card-body">
            @if($department->jobPositions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Employees Count</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($department->jobPositions as $position)
                                <tr>
                                    <td>{{ $position->title }}</td>
                                    <td>{{ $position->users_count ?? 0 }}</td>
                                    <td>{{ Str::limit($position->description, 50) }}</td>
                                    <td>
                                        <a href="{{ route('job-positions.show', $position) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-briefcase fa-3x text-gray-300 mb-3"></i>
                    <p class="mb-0">No job positions created for this department yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection