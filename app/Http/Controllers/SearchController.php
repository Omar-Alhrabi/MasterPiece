<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search across the application.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->back()->with('error', 'Please enter a search term');
        }
        
        // Search in employees/users
        $employees = User::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone_number', 'like', "%{$query}%")
            ->take(5)
            ->get();
            
        // Search in projects
        $projects = Project::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(5)
            ->get();
            
        // Search in clients
        $clients = Client::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('company_name', 'like', "%{$query}%")
            ->orWhere('contact_person', 'like', "%{$query}%")
            ->take(5)
            ->get();
            
        // Search in tasks
        $tasks = Task::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(5)
            ->get();
            
        return view('search.results', compact('query', 'employees', 'projects', 'clients', 'tasks'));
    }

    /**
     * Handle AJAX search requests.
     */
    public function ajaxSearch(Request $request)
    {
        // Your existing ajaxSearch code...
        $query = $request->input('query');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'employees' => [],
                'projects' => [],
                'clients' => [],
                'tasks' => []
            ]);
        }
        
        // Search in employees/users
        $employees = User::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->take(3)
            ->get(['id', 'first_name', 'last_name', 'email'])
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'url' => route('employees.show', $user->id)
                ];
            });
            
        // Search in projects
        $projects = Project::where('name', 'like', "%{$query}%")
            ->take(3)
            ->get(['id', 'name', 'status'])
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'status' => $project->status,
                    'url' => route('projects.show', $project->id)
                ];
            });
            
        // Search in clients
        $clients = Client::where('name', 'like', "%{$query}%")
            ->orWhere('company_name', 'like', "%{$query}%")
            ->take(3)
            ->get(['id', 'name', 'company_name'])
            ->map(function($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'company' => $client->company_name,
                    'url' => route('clients.show', $client->id)
                ];
            });
            
        // Search in tasks
        $tasks = Task::where('name', 'like', "%{$query}%")
            ->take(3)
            ->get(['id', 'name', 'status'])
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'status' => $task->status,
                    'url' => route('tasks.show', $task->id)
                ];
            });
            
        return response()->json([
            'employees' => $employees,
            'projects' => $projects,
            'clients' => $clients,
            'tasks' => $tasks
        ]);
    }
}