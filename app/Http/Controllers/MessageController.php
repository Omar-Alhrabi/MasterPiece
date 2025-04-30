<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class MessageController extends Controller
{
    /**
     * Display the main messages page
     */
    public function index()
    {
        $user = Auth::user();
        $conversations = $user->conversations()
            ->with(['users' => function($query) use ($user) {
                $query->where('users.id', '!=', $user->id);
            }])
            ->withCount(['messages as unread_count' => function ($query) use ($user) {
                $query->where('sender_id', '!=', $user->id)
                      ->where('is_read', false);
            }])
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('messages.index', compact('conversations'));
    }
    
    /**
     * Display a specific conversation
     */
    public function show(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Check if user is part of the conversation
        if (!$conversation->users->contains($user->id)) {
            return redirect()->route('messages.index')
                ->with('error', 'You are not authorized to access this conversation.');
        }
        
        // Get the conversation messages
        $messages = $conversation->messages()->with('sender')->get();
        
        // Mark unread messages as read
        $this->markConversationAsRead($conversation, $user->id);
        
        // Get conversation participants
        $participants = $conversation->users()->where('users.id', '!=', $user->id)->get();
        
        return view('messages.show', compact('conversation', 'messages', 'participants'));
    }
    
    /**
     * Create a new conversation
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('messages.create', compact('users'));
    }
    
    /**
     * Store a new conversation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);
        
        $senderId = Auth::id();
        $receiverId = $validated['receiver_id'];
        
        // Check if a private conversation exists between users
        $conversation = $this->findOrCreatePrivateConversation($senderId, $receiverId);
        
        // Create a new message
        $message = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $validated['message'],
        ]);
        
        // Attach the message to the conversation
        $conversation->messages()->attach($message->id);
        
        // Update conversation's last updated time
        $conversation->touch();
        
        return redirect()->route('messages.show', $conversation)
            ->with('success', 'Message sent successfully.');
    }
    
    /**
     * Send a reply in an existing conversation
     */
    public function reply(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        
        $senderId = Auth::id();
        
        // Check if user is part of the conversation
        if (!$conversation->users->contains($senderId)) {
            return redirect()->route('messages.index')
                ->with('error', 'You are not authorized to send messages in this conversation.');
        }
        
        // Determine the receiver in case of private conversation
        $receiverId = $senderId;
        if ($conversation->type === 'private') {
            $receiverId = $conversation->users()
                ->where('users.id', '!=', $senderId)
                ->first()->id;
        }
        
        // Create a new message
        $message = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $validated['message'],
        ]);
        
        // Attach the message to the conversation
        $conversation->messages()->attach($message->id);
        
        // Update conversation's last updated time
        $conversation->touch();
        
        return redirect()->route('messages.show', $conversation)
            ->with('success', 'Message sent successfully.');
    }
    
    /**
     * Create a group conversation
     */
    public function createGroup()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('messages.create-group', compact('users'));
    }
    
    /**
     * Store a new group conversation
     */
    public function storeGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'message' => 'required|string',
        ]);
        
        // Create a new conversation
        $conversation = Conversation::create([
            'name' => $validated['name'],
            'type' => 'group',
        ]);
        
        // Add users to the conversation
        $userIds = $validated['users'];
        $userIds[] = Auth::id(); // Add the sender as well
        $conversation->users()->attach($userIds);
        
        // Create a new message
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => Auth::id(), // In group conversations, use same user as receiver
            'message' => $validated['message'],
        ]);
        
        // Attach the message to the conversation
        $conversation->messages()->attach($message->id);
        
        return redirect()->route('messages.show', $conversation)
            ->with('success', 'Group conversation created successfully.');
    }
    
    /**
     * Find or create a private conversation between two users
     */
    private function findOrCreatePrivateConversation($userId1, $userId2)
    {
        // Look for existing private conversation
        $conversation = Conversation::whereHas('users', function ($query) use ($userId1) {
                $query->where('users.id', $userId1);
            })
            ->whereHas('users', function ($query) use ($userId2) {
                $query->where('users.id', $userId2);
            })
            ->where('type', 'private')
            ->first();
        
        // If not found, create new conversation
        if (!$conversation) {
            $conversation = Conversation::create(['type' => 'private']);
            $conversation->users()->attach([$userId1, $userId2]);
        }
        
        return $conversation;
    }
    
    /**
     * Mark messages in the conversation as read
     */
    private function markConversationAsRead(Conversation $conversation, $userId)
    {
        Message::whereHas('conversations', function ($query) use ($conversation) {
                $query->where('conversations.id', $conversation->id);
            })
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);
    }
    
    /**
     * Get the number of unread messages for the current user
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadMessagesCount();
        return response()->json(['count' => $count]);
    }
    
    /**
     * Delete a message
     */
    public function destroyMessage(Message $message)
    {
        // Check if the user is the sender
        if ($message->sender_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'You are not authorized to delete this message.');
        }
        
        $message->delete();
        
        return redirect()->back()
            ->with('success', 'Message deleted successfully.');
    }

    public function getRecentMessages()
    {
    $user = Auth::user();
    $recentConversations = $user->conversations()
        ->with(['users' => function($query) use ($user) {
            $query->where('users.id', '!=', $user->id);
        }])
        ->withCount(['messages as unread_count' => function ($query) use ($user) {
            $query->where('sender_id', '!=', $user->id)
                  ->where('is_read', false);
        }])
        ->orderBy('updated_at', 'desc')
        ->take(5)
        ->get();
        
    $html = '';
    
    foreach ($recentConversations as $conversation) {
        $otherUser = null;
        $conversationName = '';
        
        if ($conversation->type == 'private') {
            $otherUser = $conversation->users->first();
            $conversationName = $otherUser ? $otherUser->first_name . ' ' . $otherUser->last_name : 'Unknown User';
        } else {
            $conversationName = $conversation->name;
        }
        
        $latestMessage = $conversation->messages()->orderBy('created_at', 'desc')->first();
        $messageText = $latestMessage ? Str::limit($latestMessage->message, 30) : 'No messages yet';
        $time = $latestMessage ? $latestMessage->created_at->diffForHumans() : '';
        
        $html .= view('messages.partials.preview-item', compact('conversation', 'conversationName', 'otherUser', 'messageText', 'time'))->render();
    }
    
    if (empty($html)) {
        $html = '<div class="dropdown-item text-center">No new messages</div>';
    }
    
    return response()->json([
        'html' => $html,
        'count' => $user->unreadMessagesCount()
    ]);
}

/**
 * Mark a message as read
 */
public function markAsRead(Message $message)
{
    // Verify user is the receiver
    if ($message->receiver_id !== Auth::id()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }
    
    $message->is_read = true;
    $message->read_at = Carbon::now();
    $message->save();
    
    return response()->json([
        'success' => true,
        'message' => 'Message marked as read'
    ]);
}

/**
 * Get users in a conversation
 */
public function getConversationUsers(Conversation $conversation)
{
    // Verify user is part of the conversation
    if (!$conversation->users->contains(Auth::id())) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }
    
    $users = $conversation->users()->get();
    
    return response()->json([
        'success' => true,
        'users' => $users
    ]);
}

/**
 * Add a user to a conversation
 */
public function addUserToConversation(Request $request, Conversation $conversation)
{
    // Verify user is part of the conversation
    if (!$conversation->users->contains(Auth::id())) {
        return redirect()->back()->with('error', 'Unauthorized');
    }
    
    // Verify conversation is a group
    if ($conversation->type !== 'group') {
        return redirect()->back()->with('error', 'Cannot add users to private conversations');
    }
    
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);
    
    // Check if user is already in the conversation
    if ($conversation->users->contains($validated['user_id'])) {
        return redirect()->back()->with('error', 'User is already in this conversation');
    }
    
    // Add user to conversation
    $conversation->users()->attach($validated['user_id']);
    
    // Add system message
    $addedUser = User::find($validated['user_id']);
    $message = Message::create([
        'sender_id' => Auth::id(),
        'receiver_id' => Auth::id(), 
        'message' => Auth::user()->first_name . ' added ' . $addedUser->first_name . ' ' . $addedUser->last_name . ' to the conversation',
    ]);
    
    $conversation->messages()->attach($message->id);
    $conversation->touch();
    
    return redirect()->back()->with('success', 'User added to conversation');
}

/**
 * Remove a user from a conversation
 */
public function removeUserFromConversation(Conversation $conversation, User $user)
{
    // Verify current user is part of the conversation
    if (!$conversation->users->contains(Auth::id())) {
        return redirect()->back()->with('error', 'Unauthorized');
    }
    
    // Verify conversation is a group
    if ($conversation->type !== 'group') {
        return redirect()->back()->with('error', 'Cannot remove users from private conversations');
    }
    
    // Cannot remove yourself
    if ($user->id === Auth::id()) {
        return redirect()->back()->with('error', 'You cannot remove yourself from the conversation');
    }
    
    // Remove user from conversation
    $conversation->users()->detach($user->id);
    
    // Add system message
    $message = Message::create([
        'sender_id' => Auth::id(),
        'receiver_id' => Auth::id(), // System message
        'message' => Auth::user()->first_name . ' removed ' . $user->first_name . ' ' . $user->last_name . ' from the conversation',
    ]);
    
    $conversation->messages()->attach($message->id);
    $conversation->touch();
    
    return redirect()->back()->with('success', 'User removed from conversation');
}

/**
 * Delete a conversation
 */
public function destroyConversation(Conversation $conversation)
{
    // Verify user is part of the conversation
    if (!$conversation->users->contains(Auth::id())) {
        return redirect()->route('messages.index')->with('error', 'Unauthorized');
    }
    
    // For private conversations, just remove the user
    if ($conversation->type === 'private') {
        $conversation->users()->detach(Auth::id());
        
        // If no users left, delete the conversation
        if ($conversation->users()->count() === 0) {
            $conversation->delete();
        }
    } else {
        // For group conversations, only delete if current user created it
        // This is a simplification - you might want more complex permission logic
        $firstMessage = $conversation->messages()->orderBy('created_at', 'asc')->first();
        
        if ($firstMessage && $firstMessage->sender_id === Auth::id()) {
            $conversation->delete();
        } else {
            // Otherwise just leave the group
            $conversation->users()->detach(Auth::id());
            
            // Add system message
            $message = Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => Auth::id(), // System message
                'message' => Auth::user()->first_name . ' ' . Auth::user()->last_name . ' left the conversation',
            ]);
            
            $conversation->messages()->attach($message->id);
            $conversation->touch();
        }
    }
    
    return redirect()->route('messages.index')->with('success', 'Conversation deleted');
}

}
