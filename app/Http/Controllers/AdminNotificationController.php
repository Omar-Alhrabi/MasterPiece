<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Notifications\AdminMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AdminNotificationController extends Controller
{
    /**
     * Display the admin notifications page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('first_name')->get();
        
        return view('admin.notifications.index', compact('departments', 'users'));
    }

    /**
     * Send notification to users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string|max:255',
            'recipients' => 'required|in:all,department,selected',
            'department_id' => 'required_if:recipients,department',
            'user_ids' => 'required_if:recipients,selected|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Get recipients based on selection
        $recipients = [];
        
        switch ($validated['recipients']) {
            case 'all':
                $recipients = User::where('role', 'user')->get();
                break;
                
            case 'department':
                if (isset($validated['department_id'])) {
                    $recipients = User::where('department_id', $validated['department_id'])->get();
                }
                break;
                
            case 'selected':
                if (isset($validated['user_ids']) && is_array($validated['user_ids'])) {
                    $recipients = User::whereIn('id', $validated['user_ids'])->get();
                }
                break;
        }

        // Send notifications if recipients found
        if (count($recipients) > 0) {
            try {
                // Use direct notification instead of Notification::send
                foreach ($recipients as $recipient) {
                    // Create a new notification instance for each recipient to avoid issues
                    $notification = new AdminMessage(
                        $validated['title'], 
                        $validated['message'], 
                        $validated['link'] ?? null
                    );
                    
                    $recipient->notify($notification);
                }
                
                // Process the queue immediately
                Artisan::call('queue:work', ['--stop-when-empty' => true]);
                
                return redirect()->route('admin.notifications.index')
                    ->with('success', 'Notification sent successfully to ' . count($recipients) . ' recipients.');
            } catch (\Exception $e) {
                Log::error('Error sending notifications', [
                    'error' => $e->getMessage()
                ]);
                
                return redirect()->route('admin.notifications.index')
                    ->with('error', 'Error sending notifications: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.notifications.index')
            ->with('error', 'No recipients found for this notification.');
    }
}