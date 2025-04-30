@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
        <a href="{{ route('profile.edit') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-user-edit fa-sm text-white-50"></i> Edit Profile
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Details Card -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <img class="img-profile rounded-circle mx-auto d-block" 
                                 src="{{ asset('https://images.icon-icons.com/1378/PNG/512/avatardefault_92824.png') }}"
                                 style="width: 150px; height: 150px;">
                            <h5 class="mt-3">{{ $user->first_name }} {{ $user->last_name }}</h5>
                            <p class="badge badge-primary">{{ ucfirst($user->role) }}</p>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Full Name:</th>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $user->phone_number ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td>{{ $user->department->name ?? 'Not assigned' }}</td>
                                </tr>
                                <tr>
                                    <th>Job Position:</th>
                                    <td>{{ $user->jobPosition->title ?? 'Not assigned' }}</td>
                                </tr>
                                <tr>
                                    <th>Manager:</th>
                                    <td>{{ $user->manager ? $user->manager->first_name . ' ' . $user->manager->last_name : 'Not assigned' }}</td>
                                </tr>
                                <tr>
                                    <th>Hire Date:</th>
                                    <td>{{ $user->hire_date ? $user->hire_date->format('F d, Y') : 'Not recorded' }}</td>
                                </tr>
                                <tr>
                                    <th>Employment Status:</th>
                                    <td>
                                        @if($user->employment_status)
                                            <span class="badge badge-{{ $user->employment_status == 'full-time' ? 'success' : ($user->employment_status == 'part-time' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($user->employment_status) }}
                                            </span>
                                        @else
                                            Not specified
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Statistics Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-center mb-3">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user->tasks()->count() }}</div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tasks</div>
                        </div>
                        <div class="col-6 text-center mb-3">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user->projects()->count() }}</div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Projects</div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user->assignedTasks()->where('status', 'Completed')->count() }}</div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Completed Tasks</div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user->assignedTasks()->where('status', '!=', 'Completed')->count() }}</div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pending Tasks</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="recent-activity">
                        <div class="activity-item">
                            <i class="fas fa-tasks text-primary"></i>
                            <span>You have {{ $user->assignedTasks()->where('status', '!=', 'Completed')->count() }} pending tasks</span>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-calendar-alt text-info"></i>
                            <span>You have {{ $user->leaves()->where('status', 'Pending')->count() }} pending leave requests</span>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-bell text-warning"></i>
                            <span>You have {{ $user->unreadNotifications()->count() }} unread notifications</span>
                        </div>
                        @if($user->attendance()->whereDate('date', now()->toDateString())->exists())
                            <div class="activity-item">
                                <i class="fas fa-clock text-success"></i>
                                <span>You checked in today at {{ $user->attendance()->whereDate('date', now()->toDateString())->first()->check_in->format('h:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Projects Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Projects</h6>
                    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($user->projects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Client</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->projects()->with('client')->latest()->take(5)->get() as $project)
                                        <tr>
                                            <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a></td>
                                            <td>{{ $project->client->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $project->status == 'Completed' ? 'success' : ($project->status == 'In Progress' ? 'info' : ($project->status == 'On Hold' ? 'warning' : 'secondary')) }}">
                                                    {{ $project->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $total = $project->tasks()->count();
                                                    $completed = $project->tasks()->where('status', 'Completed')->count();
                                                    $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
                                                @endphp
                                                <div class="progress">
                                                    <div class="progress-bar bg-{{ $progress < 30 ? 'danger' : ($progress < 60 ? 'warning' : ($progress < 90 ? 'info' : 'success')) }}" 
                                                         role="progressbar" style="width: {{ $progress }}%"
                                                         aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">{{ $progress }}%</div>
                                                </div>
                                            </td>
                                            <td>{{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">You are not assigned to any projects.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.recent-activity {
    padding: 0;
}
.activity-item {
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
}
.activity-item:last-child {
    border-bottom: none;
}
.activity-item i {
    width: 30px;
    text-align: center;
    margin-right: 10px;
}
</style>
@endsection