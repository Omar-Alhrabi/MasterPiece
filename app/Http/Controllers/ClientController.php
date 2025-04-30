<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::withCount('projects')->paginate(10);
        
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        return view('clients.create');
    }

    /**
     * Store a newly created client in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients',
            'phone' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:100',
        ]);
        
        Client::create($validated);
        
        return redirect()->route('clients.index')
                        ->with('success', 'Client created successfully');
    }

    /**
     * Display the specified client.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $client->load('projects');
        
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    { 
         if (!Auth::user()->isAdmin()) {
        return redirect()->route('dashboard')
                        ->with('error', 'You do not have permission to access this page.');
    } 
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:100',
        ]);
        
        $client->update($validated);
        
        return redirect()->route('clients.index')
                        ->with('success', 'Client updated successfully');
    }

    /**
     * Remove the specified client from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }
        // Check if client has projects
        if ($client->projects()->count() > 0) {
            return redirect()->route('clients.index')
                            ->with('error', 'Cannot delete client with active projects. Please reassign or delete projects first.');
        }
        
        $client->delete();
        
        return redirect()->route('clients.index')
                        ->with('success', 'Client deleted successfully');
    }
}