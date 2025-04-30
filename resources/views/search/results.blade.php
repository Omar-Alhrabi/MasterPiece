@extends('layouts.admin')

@section('title', 'Search Results')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Search Results for "{{ $query }}"</h1>
    </div>

    <!-- Employees/Users Results -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Employees</h6>
            @if($employees->count() > 0)
                <a href="{{ route('employees.index', ['search' => $query]) }}" class="btn btn-sm btn-primary">View All</a>
            @endif
        </div>
        <div class="card-body">
            @if($employees->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->jobPosition->title ?? 'N/A' }}</td>
                                <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">No employees found matching "{{ $query }}"</p>
            @endif
        </div>
    </div>

    <!-- Projects Results -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
            @if($projects->count() > 0)
                <a href="{{ route('projects.index', ['search' => $query]) }}" class="btn btn-sm btn-primary">View All</a>
            @endif
        </div>
        <div class="card-body">
            @if($projects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Client</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->client->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $project->status == 'Completed' ? 'success' : ($project->status == 'In Progress' ? 'info' : ($project->status == 'On Hold' ? 'warning' : 'secondary')) }}">
                                        {{ $project->status }}
                                    </span>
                                </td>
                                <td>{{ $project->start_date ?? 'N/A' }}</td>
                                <td>{{ $project->end_date ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">No projects found matching "{{ $query }}"</p>
            @endif
        </div>
    </div>

    <!-- Clients Results -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Clients</h6>
            @if($clients->count() > 0)
                <a href="{{ route('clients.index', ['search' => $query]) }}" class="btn btn-sm btn-primary">View All</a>
            @endif
        </div>
        <div class="card-body">
            @if($clients->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->company_name }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>
                                    <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">No clients found matching "{{ $query }}"</p>
            @endif
        </div>
    </div>

    <!-- Tasks Results -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tasks</h6>
            @if($tasks->count() > 0)
                <a href="{{ route('tasks.index', ['search' => $query]) }}" class="btn btn-sm btn-primary">View All</a>
            @endif
        </div>
        <div class="card-body">
            @if($tasks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Project</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->project->name ?? 'N/A' }}</td>
                                <td>{{ $task->assignedUser->first_name ?? '' }} {{ $task->assignedUser->last_name ?? 'Unassigned' }}</td>
                                <td>
                                    <span class="badge badge-{{ $task->status == 'Completed' ? 'success' : ($task->status == 'In Progress' ? 'info' : ($task->status == 'Review' ? 'warning' : 'secondary')) }}">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $task->priority == 'High' ? 'danger' : ($task->priority == 'Medium' ? 'warning' : 'success') }}">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td>{{ $task->due_date ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">No tasks found matching "{{ $query }}"</p>
            @endif
        </div>
    </div>
</div>
@endsection