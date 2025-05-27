<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index(Request $request)
    {
        // Build the query for events with organizer
        $query = Event::with('organizer');
        
        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        // Apply date filter if provided
        if ($request->has('date_range') && !empty($request->date_range)) {
            [$startDate, $endDate] = explode(' - ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->format('Y-m-d');
            
            $query->whereBetween('event_date', [$startDate, $endDate]);
        }
        
        // Get paginated results
        $events = $query->orderBy('event_date', 'desc')->paginate(10);
        
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    { 
         if (!Auth::user()->isAdmin()) {
        return redirect()->route('dashboard')
                        ->with('error', 'You do not have permission to access this page.');
    } 
        $users = User::orderBy('first_name')->get();
        
        return view('events.create', compact('users'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        } 
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'organizer_id' => 'nullable|exists:users,id',
        ]);
        
        // Set current user as organizer if not specified
        if (empty($validated['organizer_id'])) {
            $validated['organizer_id'] = Auth::id();
        }
        
        Event::create($validated);
        
        return redirect()->route('events.index')
                        ->with('success', 'Event created successfully');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $event->load('organizer');
        
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }
        $users = User::orderBy('first_name')->get();
        
        return view('events.edit', compact('event', 'users'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'organizer_id' => 'nullable|exists:users,id',
        ]);
        
        $event->update($validated);
        
        return redirect()->route('events.index')
                        ->with('success', 'Event updated successfully');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                            ->with('error', 'You do not have permission to access this page.');
        }
        
        $event->delete();
        
        return redirect()->route('events.index')
                        ->with('success', 'Event deleted successfully');
    }
    
    /**
     * Display calendar view of events
     */
    public function calendar()
    {
        $events = Event::with('organizer')->get();
        
        // Format events for calendar
        $calendarEvents = [];
        foreach ($events as $event) {
            $calendarEvents[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->event_date->format('Y-m-d') . 
                          ($event->event_time ? 'T' . date('H:i:s', strtotime($event->event_time)) : ''),
                'url' => route('events.show', $event),
                'description' => $event->description,
                'location' => $event->location,
                'organizer' => $event->organizer ? $event->organizer->first_name . ' ' . $event->organizer->last_name : 'Unknown'
            ];
        }
        
        return view('events.calendar', compact('calendarEvents'));
    }
}