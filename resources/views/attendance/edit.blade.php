@extends('layouts.admin')

@section('title', 'Edit Attendance Record')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Attendance Record</h1>
        <a href="{{ route('attendance.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Attendance
        </a>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Edit Attendance Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Attendance for {{ date('M d, Y', strtotime($attendance->date)) }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('attendance.update', $attendance) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-row">
                    @if(Auth::user()->isAdmin())
                    <div class="form-group col-md-6">
                        <label for="user_id">Employee</label>
                        <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $attendance->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @else
                        <input type="hidden" name="user_id" value="{{ $attendance->user_id }}">
                    @endif
                    
                    <div class="form-group col-md-6">
                        <label for="date">Date</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ $attendance->date }}">
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="check_in">Check In Time</label>
                        <input type="time" class="form-control @error('check_in') is-invalid @enderror" id="check_in" name="check_in" 
                            value="{{ $attendance->check_in ? date('H:i', strtotime($attendance->check_in)) : '' }}">
                        @error('check_in')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="check_out">Check Out Time</label>
                        <input type="time" class="form-control @error('check_out') is-invalid @enderror" id="check_out" name="check_out"
                            value="{{ $attendance->check_out ? date('H:i', strtotime($attendance->check_out)) : '' }}">
                        @error('check_out')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="Present" {{ $attendance->status == 'Present' ? 'selected' : '' }}>Present</option>
                            <option value="Late" {{ $attendance->status == 'Late' ? 'selected' : '' }}>Late</option>
                            <option value="Half-day" {{ $attendance->status == 'Half-day' ? 'selected' : '' }}>Half-day</option>
                            <option value="Absent" {{ $attendance->status == 'Absent' ? 'selected' : '' }}>Absent</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="breaks">Breaks</label>
                        <textarea class="form-control" id="breaks" name="breaks" rows="1" placeholder="Break times (optional)">{{ $attendance->breaks ?? '' }}</textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="note">Notes</label>
                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3">{{ $attendance->note }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary">Update Attendance</button>
                </div>
            </form>
        </div>
    </div>
@endsection