@extends('layouts.admin')

@section('title', 'Event Details')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Event Details</h1>
        <div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('events.edit', $event) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            @endif
            <a href="{{ route('events.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <div class="row">
        <!-- Event Info Card -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Event Information</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Event Actions:</div>
                            <a class="dropdown-item" href="{{ route('events.edit', $event) }}">
                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                                Edit Event
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#deleteEventModal">
                                <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                                Delete Event
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h2 class="text-primary">{{ $event->title }}</h2>
                        <div class="d-flex align-items-center text-muted mb-3">
                            <i class="fas fa-calendar-day mr-2"></i> 
                            <span>{{ $event->event_date->format('l, F d, Y') }}</span>
                            
                            @if($event->event_time)
                                <span class="mx-2">|</span>
                                <i class="fas fa-clock mr-2"></i>
                                <span>{{ date('h:i A', strtotime($event->event_time)) }}</span>
                            @endif
                            
                            @if($event->location)
                                <span class="mx-2">|</span>
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>{{ $event->location }}</span>
                            @endif
                        </div>
                        
                        @if($event->organizer)
                            <div class="d-flex align-items-center mb-4">
                                <div class="mr-2">
                                    <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}" width="32" height="32">
                                </div>
                                <div>
                                    <p class="mb-0">Organized by: <a href="{{ route('employees.show', $event->organizer) }}">{{ $event->organizer->first_name }} {{ $event->organizer->last_name }}</a></p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold">Description</h5>
                                @if($event->description)
                                    <p class="card-text">{!! nl2br(e($event->description)) !!}</p>
                                @else
                                    <p class="card-text text-muted">No description provided</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Created At
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $event->created_at->format('M d, Y H:i') }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar-plus fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Last Updated
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $event->updated_at->format('M d, Y H:i') }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-edit fa-2x text-gray-300"></i>
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
        
        <!-- Event Actions Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary btn-icon-split btn-lg mb-3">
                            <span class="icon text-white-50">
                                <i class="fas fa-calendar-plus"></i>
                            </span>
                            <span class="text">Add to Calendar</span>
                        </a>
                        
                        <a href="#" class="btn btn-info btn-icon-split btn-lg mb-3">
                            <span class="icon text-white-50">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <span class="text">Send Invitation</span>
                        </a>
                        
                        <a href="#" class="btn btn-success btn-icon-split btn-lg">
                            <span class="icon text-white-50">
                                <i class="fas fa-share-alt"></i>
                            </span>
                            <span class="text">Share Event</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Event Modal -->
    <div class="modal fade" id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteEventModalLabel">Delete Event</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the event <strong>{{ $event->title }}</strong>? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('events.destroy', $event) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection