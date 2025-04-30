@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-check-double fa-sm"></i> Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Notifications</h6>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <a href="{{ route('notifications.markAsRead', $notification->id) }}" 
                           class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-light' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-{{ $notification->data['icon'] ?? 'bell' }} text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">{{ $notification->data['message'] }}</h5>
                                        <p class="mb-1">
                                            @if(isset($notification->data['task_name']))
                                                Task: {{ $notification->data['task_name'] }}
                                            @elseif(isset($notification->data['project_name']))
                                                Project: {{ $notification->data['project_name'] }}
                                            @elseif(isset($notification->data['leave_type']))
                                                Leave Type: {{ $notification->data['leave_type'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-gray-300 mb-3"></i>
                    <p>No notifications available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
