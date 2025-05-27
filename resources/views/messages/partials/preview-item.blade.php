<a class="dropdown-item d-flex align-items-center" href="{{ route('messages.show', $conversation) }}">
    <div class="dropdown-list-image mr-3">
        @if($conversation->type == 'private' && isset($otherUser))
        <div class="avatar-circle" style="width: 40px; height: 40px;">
            <span class="initials">{{ substr($otherUser->first_name, 0, 1) . substr($otherUser->last_name, 0, 1) }}</span>
        </div>
        @else
        <div class="avatar-circle bg-success" style="width: 40px; height: 40px;">
            <span class="initials"><i class="fas fa-users"></i></span>
        </div>
        @endif
        @if(isset($conversation->unread_count) && $conversation->unread_count > 0)
        <div class="status-indicator bg-success"></div>
        @endif
    </div>
    <div class="font-weight-bold">
        <div class="text-truncate">{{ $messageText }}</div>
        <div class="small text-gray-500">{{ $conversationName }} · {{ $time }}</div>
    </div>
</a>