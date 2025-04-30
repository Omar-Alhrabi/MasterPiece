@extends('layouts.admin')

@section('title', 'Conversation')

@section('content')


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <a href="{{ route('messages.index') }}" class="btn btn-circle btn-sm btn-light mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        @if($conversation->type == 'private')
            {{ $participants->first()->first_name . ' ' . $participants->first()->last_name }}
        @else
            {{ $conversation->name }}
        @endif
    </h1>
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            @if($conversation->type == 'group')
                <a class="dropdown-item" href="#">View Group Info</a>
                <a class="dropdown-item" href="#">Add Members</a>
                <div class="dropdown-divider"></div>
            @endif
            <a class="dropdown-item text-danger" href="#">Delete Conversation</a>
        </div>
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
                    @foreach(Auth::user()->conversations as $c)
                        @php
                            $otherUser = null;
                            $conversationName = '';
                            
                            if($c->type == 'private') {
                                $otherUser = $c->users->where('id', '!=', Auth::id())->first();
                                $conversationName = $otherUser ? $otherUser->first_name . ' ' . $otherUser->last_name : 'Unknown User';
                            } else {
                                $conversationName = $c->name;
                            }
                            
                            $unreadCount = $c->unreadCount(Auth::id());
                        @endphp
                        
                        <a href="{{ route('messages.show', $c) }}" class="list-group-item list-group-item-action p-3 {{ $conversation->id == $c->id ? 'active' : '' }}">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    @if($c->type == 'private')
                                        <div class="avatar-circle">
                                            @if($otherUser)
                                                <span class="initials">{{ substr($otherUser->first_name, 0, 1) . substr($otherUser->last_name, 0, 1) }}</span>
                                            @else
                                                <span class="initials">?</span>
                                            @endif
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
                                        @if($unreadCount > 0)
                                            <span class="badge badge-danger badge-pill">{{ $unreadCount }}</span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-0">
                                        @if($c->messages->count() > 0)
                                            {{ \Illuminate\Support\Str::limit($c->messages->last()->message, 30) }}
                                            <span class="text-muted smaller ml-2">
                                                {{ $c->messages->last()->created_at->diffForHumans() }}
                                            </span>
                                        @else
                                            No messages yet
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    @if($conversation->type == 'group')
                        <i class="fas fa-users mr-1"></i> {{ $conversation->name }}
                        <span class="text-muted ml-2 small">{{ $participants->count() + 1 }} members</span>
                    @else
                        {{ $participants->first()->first_name . ' ' . $participants->first()->last_name }}
                        <span class="text-muted ml-2 small">
                            @if($participants->first()->isOnline())
                                <i class="fas fa-circle text-success mr-1 small"></i> Online
                            @else
                                <i class="fas fa-circle text-secondary mr-1 small"></i> Offline
                            @endif
                        </span>
                    @endif
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="chat-messages p-4" id="chat-messages">
                    @foreach($messages as $message)
                        <div class="message-item mb-3 {{ $message->sender_id == Auth::id() ? 'sender' : 'receiver' }}">
                            <div class="message-content {{ $message->sender_id == Auth::id() ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded">
                                @if($conversation->type == 'group' && $message->sender_id != Auth::id())
                                    <div class="message-sender mb-1 font-weight-bold small">{{ $message->sender->first_name }} {{ $message->sender->last_name }}</div>
                                @endif
                                <div class="message-text">{{ $message->message }}</div>
                                <div class="message-time text-right mt-1">
                                    <small class="{{ $message->sender_id == Auth::id() ? 'text-white-50' : 'text-muted' }}">
                                        {{ $message->created_at->format('h:i A') }}
                                        @if($message->sender_id == Auth::id())
                                            @if($message->is_read)
                                                <i class="fas fa-check-double ml-1"></i>
                                            @else
                                                <i class="fas fa-check ml-1"></i>
                                            @endif
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="chat-input border-top p-3">
                    <form action="{{ route('messages.reply', $conversation) }}" method="POST" id="messageForm">
                        @csrf
                        <div class="input-group">
                            <input type="text" class="form-control" name="message" placeholder="Type your message..." autocomplete="off" required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </form>
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
    
    .chat-messages {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .message-item.sender {
        display: flex;
        justify-content: flex-end;
    }
    
    .message-content {
        max-width: 75%;
        border-radius: 15px !important;
        display: inline-block;
    }
    
    .message-item.sender .message-content {
        border-top-right-radius: 0 !important;
    }
    
    .message-item.receiver .message-content {
        border-top-left-radius: 0 !important;
    }
    
    .message-time {
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Scroll to bottom of chat on page load
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // Auto-submit form on enter
        $('#messageForm input').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#messageForm').submit();
            }
        });
    });
</script>
@endpush