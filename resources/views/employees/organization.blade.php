@extends('layouts.admin')

@section('title', 'Organization Chart')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Organization Chart</h1>
        <a href="{{ route('employees.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Employees
        </a>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Organization Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Company Structure</h6>
        </div>
        <div class="card-body">
            <!-- Top Management Section -->
            <div class="text-center mb-5">
                <h5 class="font-weight-bold mb-4">Top Management</h5>
                <div class="row justify-content-center">
                    @forelse($topManagement as $manager)
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body text-center">
                                    <img class="img-profile rounded-circle mb-3" src="{{ asset('https://images.icon-icons.com/1378/PNG/512/avatardefault_92824.png') }}" alt="Profile" width="80" height="80">
                                    <h5 class="mb-1">
                                        <a href="{{ route('employees.show', $manager) }}" class="text-primary">
                                            {{ $manager->first_name }} {{ $manager->last_name }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-1">
                                        {{ $manager->jobPosition ? $manager->jobPosition->title : 'CEO / Top Management' }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $manager->subordinates->count() }} direct {{ Str::plural('report', $manager->subordinates->count()) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                No top management defined. Set employees with no manager as top management.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Departments Section -->
            <h5 class="font-weight-bold mb-4 text-center">Departments</h5>
            <div class="row">
                @forelse($departments as $department)
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">{{ $department->name }}</h6>
                                <span class="badge badge-primary">{{ $department->users->count() }} {{ Str::plural('member', $department->users->count()) }}</span>
                            </div>
                            <div class="card-body">
                                <!-- Department Manager -->
                                @if($department->manager)
                                    <div class="text-center mb-4">
                                        <div class="dept-manager-card p-3 rounded mb-3 bg-light">
                                            <img class="img-profile rounded-circle mb-2" src="{{ asset('https://images.icon-icons.com/1378/PNG/512/avatardefault_92824.png') }}" alt="Profile" width="60" height="60">
                                            <h6 class="mb-0">
                                                <a href="{{ route('employees.show', $department->manager) }}" class="text-primary">
                                                    {{ $department->manager->first_name }} {{ $department->manager->last_name }}
                                                </a>
                                            </h6>
                                            <p class="small text-muted mb-0">Department Manager</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Department Members -->
                                <div class="row">
                                    @php
                                        $members = $department->users->where('id', '!=', optional($department->manager)->id)->take(8);
                                    @endphp
                                    
                                    @forelse($members as $member)
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <img class="img-profile rounded-circle" src="{{ asset('https://images.icon-icons.com/1378/PNG/512/avatardefault_92824.png') }}" alt="Profile" width="40" height="40">
                                                </div>
                                                <div>
                                                    <a href="{{ route('employees.show', $member) }}" class="text-primary">
                                                        {{ $member->first_name }} {{ $member->last_name }}
                                                    </a>
                                                    <p class="small text-muted mb-0">
                                                        {{ $member->jobPosition ? $member->jobPosition->title : 'Team Member' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted text-center">No team members in this department.</p>
                                        </div>
                                    @endforelse
                                    
                                    @if($department->users->where('id', '!=', optional($department->manager)->id)->count() > 8)
                                        <div class="col-12 text-center mt-2">
                                            <a href="{{ route('employees.index', ['department_id' => $department->id]) }}" class="btn btn-sm btn-outline-primary">
                                                View all members ({{ $department->users->where('id', '!=', optional($department->manager)->id)->count() }})
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            No departments have been created yet. Create departments to organize your employees.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .dept-manager-card {
        border: 1px dashed #4e73df;
    }
</style>
@endpush