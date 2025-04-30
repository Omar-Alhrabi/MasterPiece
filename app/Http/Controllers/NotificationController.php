<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        try {
            // Direct database query to debug
            $dbNotifications = DB::table('notifications')
                ->where('notifiable_id', auth()->id())
                ->where('notifiable_type', 'App\\Models\\User')
                ->orderBy('created_at', 'desc')
                ->get();
                
            Log::info('DB Notifications count', ['count' => $dbNotifications->count()]);
            
            // Get notifications through Laravel's notification system
            $notifications = auth()->user()->notifications()->paginate(15);
            
            Log::info('Retrieved notifications', [
                'count' => $notifications->count(),
                'total' => $notifications->total()
            ]);
            
            return view('notifications.index', compact('notifications'));
            
        } catch (\Exception $e) {
            Log::error('Error retrieving notifications', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('notifications.index', ['notifications' => collect([])->paginate(15)])
                ->with('error', 'Error retrieving notifications: ' . $e->getMessage());
        }
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            
            // Redirect based on the notification URL if available
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
            
            return redirect()->back()->with('success', 'Notification marked as read.');
            
        } catch (\Exception $e) {
            Log::error('Error marking notification as read', [
                'notification_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Could not mark notification as read.');
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        try {
            $count = auth()->user()->unreadNotifications->count();
            Log::info('Marking all notifications as read', ['count' => $count]);
            
            auth()->user()->unreadNotifications->markAsRead();
            return redirect()->back()->with('success', 'All notifications marked as read.');
            
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Could not mark notifications as read.');
        }
    }

    /**
     * Get the latest notifications for AJAX requests.
     */
    public function getLatestNotifications()
    {
        try {
            $notifications = auth()->user()->unreadNotifications->take(5);
            $count = auth()->user()->unreadNotifications->count();
            
            Log::info('Getting latest notifications', ['count' => $count]);
            
            return response()->json([
                'count' => $count,
                'notifications' => $notifications
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting latest notifications', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'count' => 0,
                'notifications' => [],
                'error' => 'Error retrieving notifications'
            ], 500);
        }
    }
}