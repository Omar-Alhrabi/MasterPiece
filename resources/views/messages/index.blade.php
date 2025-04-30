@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Messages</h1>
    <div>
        <a href="{{ route('messages.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-envelope fa-sm text-white-50"></i> New Message
        </a>
        <a href="{{ route('messages.create-group') }}" class="btn btn-sm btn-success shadow-sm ml-2">
            <i class="fas fa-users fa-sm text-white-50"></i> New Group
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Your Conversations</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($conversations as $conversation)
                        @php
                            $otherUser = null;
                            $conversationName = '';
                            
                            if($conversation->type == 'private') {
                                $otherUser = $conversation->users->first();
                                $conversationName = $otherUser->first_name . ' ' . $otherUser->last_name;
                            } else {
                                $conversationName = $conversation->name;
                            }
                        @endphp
                        
                        <a href="{{ route('messages.show', $conversation) }}" class="list-group-item list-group-item-action p-3 {{ request()->route('conversation') && request()->route('conversation')->id == $conversation->id ? 'active' : '' }}">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    @if($conversation->type == 'private')
                                        <div class="avatar-circle">
                                            <span class="initials">{{ substr($otherUser->first_name, 0, 1) . substr($otherUser->last_name, 0, 1) }}</span>
                                        </div>
                                    @else
                                        <div class="avatar-circle bg-success">
                                            <span class="initials"><i class="fas fa-users"></i></span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-0">{{ $conversationName }}</h6>
                                        @if(isset($conversation->unread_count) && $conversation->unread_count > 0)
                                            <span class="badge badge-danger badge-pill">{{ $conversation->unread_count }}</span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-0">
                                        @if($conversation->messages->count() > 0)
                                            {{ \Illuminate\Support\Str::limit($conversation->messages->last()->message, 30) }}
                                            <span class="text-muted smaller ml-2">
                                                {{ $conversation->messages->last()->created_at->diffForHumans() }}
                                            </span>
                                        @else
                                            No messages yet
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-gray-300 mb-3"></i>
                            <p>No conversations found.</p>
                            <a href="{{ route('messages.create') }}" class="btn btn-sm btn-primary">
                                Start a new conversation
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Select a conversation</h6>
            </div>
            <div class="card-body text-center py-5">
                <i class="fas fa-envelope fa-4x text-gray-300 mb-3"></i>
                <p>Select a conversation from the list or start a new one.</p>
                <div class="mt-3">
                    <a href="{{ route('messages.create') }}" class="btn btn-primary">
                        <i class="fas fa-envelope fa-sm mr-1"></i> New Message
                    </a>
                    <a href="{{ route('messages.create-group') }}" class="btn btn-success ml-2">
                        <i class="fas fa-users fa-sm mr-1"></i> New Group
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: #4e73df;
        text-align: center;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .initials {
        font-size: 18px;
        line-height: 1;
        position: relative;
        color: #fff;
    }
    
    .list-group-item.active {
        background-color: #f8f9fc;
        border-color: #e3e6f0;
        color: #444;
    }
</style>
@endpush