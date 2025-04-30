@extends('layouts.admin')

@section('title', 'Client Details')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Client Details</h1>
        <div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('clients.edit', $client) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            @endif
            <a href="{{ route('clients.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <div class="row">
        <!-- Client Info Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Client Information</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="display-4 mb-3">
                            <i class="fas fa-building text-primary"></i>
                        </div>
                        <h4 class="font-weight-bold">{{ $client->name }}</h4>
                        @if($client->company_name)
                            <p class="text-muted">{{ $client->company_name }}</p>
                        @endif
                    </div>
                    
                    <div class="border-left-primary pl-3">
                        @if($client->email)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Email</p>
                            <p class="mb-0">{{ $client->email }}</p>
                        </div>
                        @endif
                        
                        @if($client->phone)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Phone</p>
                            <p class="mb-0">{{ $client->phone }}</p>
                        </div>
                        @endif
                        
                        @if($client->contact_person)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Contact Person</p>
                            <p class="mb-0">{{ $client->contact_person }}</p>
                        </div>
                        @endif
                        
                        @if($client->address)
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Address</p>
                            <p class="mb-0">{{ $client->address }}</p>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Created At</p>
                            <p class="mb-0">{{ $client->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Client Projects -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Client Projects</h6>
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus fa-sm"></i> Add Project
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Manager</th>
                                    <th>Dates</th>
                                    @if(Auth::user()->isAdmin())
                                    <th>Budget</th>
                                    @endif
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($client->projects as $project)
                                    <tr>
                                        <td>
                                            <a href="{{ route('projects.show', $project) }}" class="font-weight-bold text-primary">
                                                {{ $project->name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($project->status == 'Completed')
                                                <span class="badge badge-success">{{ $project->status }}</span>
                                            @elseif($project->status == 'In Progress')
                                                <span class="badge badge-info">{{ $project->status }}</span>
                                            @elseif($project->status == 'Pending')
                                                <span class="badge badge-warning">{{ $project->status }}</span>
                                            @elseif($project->status == 'On Hold')
                                                <span class="badge badge-secondary">{{ $project->status }}</span>
                                            @elseif($project->status == 'Cancelled')
                                                <span class="badge badge-danger">{{ $project->status }}</span>
                                            @else
                                                <span class="badge badge-primary">{{ $project->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->manager)
                                                <a href="{{ route('employees.show', $project->manager) }}">
                                                    {{ $project->manager->first_name }} {{ $project->manager->last_name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->start_date)
                                                {{ $project->start_date->format('M d, Y') }}
                                            @endif
                                            @if($project->start_date && $project->end_date)
                                                -
                                            @endif
                                            @if($project->end_date)
                                                {{ $project->end_date->format('M d, Y') }}
                                            @endif
                                            
                                            @if(!$project->start_date && !$project->end_date)
                                                <span class="text-muted">No dates set</span>
                                            @endif
                                        </td>
                                        @if(Auth::user()->isAdmin())
                                        <td>
                                            @if($project->budget)
                                                ${{ number_format($project->budget, 2) }}
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>
                                        @endif
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(Auth::user()->isAdmin())
                                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-info" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No projects found for this client</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Projects</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $client->projects->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
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
                                                Active Projects</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $client->projects->where('status', 'In Progress')->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Completed Projects</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $client->projects->where('status', 'Completed')->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection