@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Projects</h1>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('projects.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Project
        </a>
        @endif
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Project Search & Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('projects.index') }}" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="search">Search</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Project name..." value="{{ request('search') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="client_id">Client</label>
                        <select class="form-control" id="client_id" name="client_id">
                            <option value="">All Clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="manager_id">Project Manager</label>
                        <select class="form-control" id="manager_id" name="manager_id">
                            <option value="">All Managers</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ request('manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->first_name }} {{ $manager->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Projects List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Projects</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="projectsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Client</th>
                            <th>Manager</th>
                            <th>Status</th>
                            <th>Deadline</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>{{ $project->id }}</td>
                                <td>
                                    <a href="{{ route('projects.show', $project) }}" class="font-weight-bold text-primary">
                                        {{ $project->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($project->client)
                                        <a href="{{ route('clients.show', $project->client) }}">
                                            {{ $project->client->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not Assigned</span>
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
                                <td>{{ $project->end_date ? date('M d, Y', strtotime($project->end_date)) : 'No deadline' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal-{{ $project->id }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal-{{ $project->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $project->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel-{{ $project->id }}">Delete Project</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the project <strong>{{ $project->name }}</strong>? This action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="{{ route('projects.destroy', $project) }}" method="POST">
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
                                <td colspan="7" class="text-center">No projects found</td>
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
            @if ($projects->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $projects->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @endif

            <!-- Pagination Elements -->
            @for ($i = 1; $i <= $projects->lastPage(); $i++)
                <li class="page-item {{ ($projects->currentPage() == $i) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $projects->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <!-- Next Page Link -->
            @if ($projects->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $projects->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Next">
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
        $('#projectsTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false,
        });
    });
</script>
@endpush