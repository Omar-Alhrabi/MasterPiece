<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\JobPositionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminNotificationController;
use App\Models\Task;
use Carbon\Carbon;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes (Laravel default)
require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard - Tasks count for AJAX updates
    Route::get('/dashboard/pending-tasks-count', function() {
        $count = Task::where('status', '!=', 'Completed')->count();
        return response()->json(['count' => $count]);
    })->name('dashboard.pending-tasks-count');
    
    // Employee management
    Route::get('/employees/organization', [EmployeeController::class, 'organization'])->name('employees.organization');
    Route::resource('employees', EmployeeController::class);
    
    // Department management
    Route::resource('departments', DepartmentController::class);
    
    // Job Position management
    Route::resource('job-positions', JobPositionController::class);
    Route::get('/api/job-positions/by-department', [JobPositionController::class, 'getByDepartment'])->name('job-positions.by-department');
    
    // Client management
    Route::resource('clients', ClientController::class);
    
    // Project management
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/add-member', [ProjectController::class, 'addMember'])->name('projects.add-member');
    Route::delete('/projects/{project}/remove-member/{user}', [ProjectController::class, 'removeMember'])->name('projects.remove-member');
    
    // Task management
    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{task}/complete', function(Task $task) {
        $task->status = 'Completed';
        $task->completed_date = Carbon::now();
        $task->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Task marked as completed'
        ]);
    })->name('tasks.complete');
    
    // Attendance management
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/record', [AttendanceController::class, 'record'])->name('record');
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
        Route::get('/reports', [AttendanceController::class, 'reports'])->name('reports');
        Route::get('/create', [AttendanceController::class, 'create'])->name('create');
        Route::post('/store', [AttendanceController::class, 'store'])->name('store');
        Route::get('/edit/{attendance}', [AttendanceController::class, 'edit'])->name('edit');
        Route::put('/update/{attendance}', [AttendanceController::class, 'update'])->name('update');
        Route::delete('/destroy/{attendance}', [AttendanceController::class, 'destroy'])->name('destroy');
        Route::get('/get-summary', [AttendanceController::class, 'getSummary'])->name('get-summary');
        
        // New Break Routes
        Route::post('/break-start', [AttendanceController::class, 'breakStart'])->name('break-start');
        Route::post('/break-end', [AttendanceController::class, 'breakEnd'])->name('break-end');
    });
    
    // Leave management
    Route::get('/leaves/requests', [LeaveController::class, 'requests'])->name('leaves.requests');
    Route::get('/leaves/reports', [LeaveController::class, 'reports'])->name('leaves.reports');
    Route::get('/leaves/approve', [LeaveController::class, 'approvalList'])->name('leaves.approve');
    Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve-action');
    Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::resource('leaves', LeaveController::class);
    
    // Leave Type management
    Route::resource('leave-types', LeaveTypeController::class);
    
    // Payroll management
    Route::get('/payroll/calculate', [PayrollController::class, 'calculate'])->name('payroll.calculate');
    Route::post('/payroll/process', [PayrollController::class, 'process'])->name('payroll.process');
    Route::get('/payroll/history', [PayrollController::class, 'history'])->name('payroll.history');
    Route::get('/payroll/reports', [PayrollController::class, 'reports'])->name('payroll.reports');
    Route::get('/payroll/create', [PayrollController::class, 'create'])->name('payroll.create');
    Route::patch('/payroll/{salary}/mark-as-paid', [PayrollController::class, 'markAsPaid'])->name('payroll.mark-as-paid');
    Route::get('/payroll/{salary}/generate-slip', [PayrollController::class, 'generateSlip'])->name('payroll.generate-slip');
    Route::get('/payroll/{salary}/edit', [PayrollController::class, 'edit'])->name('payroll.edit');

    // General routes (should be after specific routes)
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{salary}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::put('/payroll/{salary}', [PayrollController::class, 'update'])->name('payroll.update');
    Route::delete('/payroll/{salary}', [PayrollController::class, 'destroy'])->name('payroll.destroy');
    
    // API routes for payroll
    Route::get('/api/employees/salary', [EmployeeController::class, 'getSalaryInfo'])->name('api.employees.salary');
    Route::get('/api/attendance/summary', [AttendanceController::class, 'getSummary'])->name('api.attendance.summary');
    
    // User profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    
    // Dummy routes for views - you can remove these once you create the actual controllers
    Route::view('/charts', 'charts')->name('charts');
    Route::view('/activities', 'activities')->name('activities');
    Route::view('/activities/log', 'activities.log')->name('activities.log');
    
    // Dummy user, role, permission routes - you can remove or replace these
    Route::view('/users', 'users.index')->name('users.index');
    Route::view('/roles', 'roles.index')->name('roles.index');
    Route::view('/permissions', 'permissions.index')->name('permissions.index');

    // Events management
    Route::get('/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');
    Route::resource('events', EventController::class);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications/get-latest', [NotificationController::class, 'getLatestNotifications'])->name('notifications.getLatest');

    // Search Routes
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/search/ajax', [SearchController::class, 'ajaxSearch'])->name('search.ajax');

    // Admin Notifications Routes
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/notifications', [App\Http\Controllers\AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/send', [App\Http\Controllers\AdminNotificationController::class, 'send'])->name('notifications.send');
    });

    Route::get('/report/generate', [App\Http\Controllers\DashboardController::class, 'generateReport'])->name('report.generate');
    
    Route::middleware(['auth'])->group(function () {
        // Main messaging routes
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [App\Http\Controllers\MessageController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\MessageController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\MessageController::class, 'store'])->name('store');
            Route::get('/create-group', [App\Http\Controllers\MessageController::class, 'createGroup'])->name('create-group');
            Route::post('/store-group', [App\Http\Controllers\MessageController::class, 'storeGroup'])->name('store-group');
            Route::get('/get-unread-count', [App\Http\Controllers\MessageController::class, 'getUnreadCount'])->name('get-unread-count');
            Route::get('/recent-messages', [App\Http\Controllers\MessageController::class, 'getRecentMessages'])->name('recent');
            Route::get('/{conversation}', [App\Http\Controllers\MessageController::class, 'show'])->name('show');
            Route::post('/{conversation}/reply', [App\Http\Controllers\MessageController::class, 'reply'])->name('reply');
        });
        
        // Message-specific actions
        Route::prefix('message')->name('message.')->group(function () {
            Route::delete('/{message}', [App\Http\Controllers\MessageController::class, 'destroyMessage'])->name('destroy');
            Route::put('/{message}/mark-read', [App\Http\Controllers\MessageController::class, 'markAsRead'])->name('mark-read');
        });
        
        // Conversation management
        Route::prefix('conversation')->name('conversation.')->group(function () {
            Route::get('/{conversation}/users', [App\Http\Controllers\MessageController::class, 'getConversationUsers'])->name('users');
            Route::post('/{conversation}/add-user', [App\Http\Controllers\MessageController::class, 'addUserToConversation'])->name('add-user');
            Route::delete('/{conversation}/remove-user/{user}', [App\Http\Controllers\MessageController::class, 'removeUserFromConversation'])->name('remove-user');
            Route::delete('/{conversation}', [App\Http\Controllers\MessageController::class, 'destroyConversation'])->name('destroy');
        });
    });
});