@extends('layouts.admin')

@section('title', 'Tasks')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tasks</h1>
        <a href="{{ route('tasks.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Task
        </a>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Task Search & Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('tasks.index') }}" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="project_id">Project</label>
                        <select class="form-control" id="project_id" name="project_id">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="assigned_to">Assigned To</label>
                        <select class="form-control" id="assigned_to" name="assigned_to">
                            <option value="">All Employees</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
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
                            <option value="Review" {{ request('status') == 'Review' ? 'selected' : '' }}>Review</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="priority">Priority</label>
                        <select class="form-control" id="priority" name="priority">
                            <option value="">All Priorities</option>
                            <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Urgent" {{ request('priority') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Tasks</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tasksTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Task Name</th>
                            <th>Project</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>{{ $task->id }}</td>
                                <td>
                                    <a href="{{ route('tasks.show', $task) }}" class="font-weight-bold text-primary">
                                        {{ $task->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($task->project)
                                        <a href="{{ route('projects.show', $task->project) }}">
                                            {{ $task->project->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">No Project</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->assignedUser)
                                        <a href="{{ route('employees.show', $task->assignedUser) }}">
                                            {{ $task->assignedUser->first_name }} {{ $task->assignedUser->last_name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->status == 'Completed')
                                        <span class="badge badge-success">{{ $task->status }}</span>
                                    @elseif($task->status == 'In Progress')
                                        <span class="badge badge-info">{{ $task->status }}</span>
                                    @elseif($task->status == 'Pending')
                                        <span class="badge badge-warning">{{ $task->status }}</span>
                                    @elseif($task->status == 'Review')
                                        <span class="badge badge-secondary">{{ $task->status }}</span>
                                    @else
                                        <span class="badge badge-primary">{{ $task->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->priority == 'Low')
                                        <span class="badge badge-success">{{ $task->priority }}</span>
                                    @elseif($task->priority == 'Medium')
                                        <span class="badge badge-info">{{ $task->priority }}</span>
                                    @elseif($task->priority == 'High')
                                        <span class="badge badge-warning">{{ $task->priority }}</span>
                                    @elseif($task->priority == 'Urgent')
                                        <span class="badge badge-danger">{{ $task->priority }}</span>
                                    @else
                                        <span class="badge badge-primary">{{ $task->priority }}</span>
                                    @endif
                                </td>
                                <td>{{ $task->due_date ? date('M d, Y', strtotime($task->due_date)) : 'No deadline' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal-{{ $task->id }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $task->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel-{{ $task->id }}">Delete Task</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the task <strong>{{ $task->name }}</strong>? This action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST">
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
                                <td colspan="8" class="text-center">No tasks found</td>
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
            @if ($tasks->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $tasks->appends(request()->except('page'))->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @endif

            <!-- Pagination Elements -->
            @for ($i = 1; $i <= $tasks->lastPage(); $i++)
                <li class="page-item {{ ($tasks->currentPage() == $i) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $tasks->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <!-- Next Page Link -->
            @if ($tasks->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $tasks->appends(request()->except('page'))->nextPageUrl() }}" aria-label="Next">
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
        $('#tasksTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false,
        });
    });
</script>
@endpush