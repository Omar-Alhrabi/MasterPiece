@extends('layouts.admin')

@section('title', 'Record Attendance')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Record Attendance</h1>
        <a href="{{ route('attendance.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-list fa-sm text-white-50"></i> View Attendance
        </a>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Current Date and Time -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h4 class="font-weight-bold text-primary">{{ date('l, F d, Y', strtotime($today)) }}</h4>
                    <div id="clock" class="display-4 mt-2 text-gray-800"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Record Card -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Today's Attendance</h6>
                </div>
                <div class="card-body">
                    @if(!$attendance)
                        <!-- Check In Form -->
                        <div class="text-center p-4">
                            <div class="mb-4">
                                <i class="fas fa-user-clock fa-5x text-primary"></i>
                            </div>
                            <h5>You haven't checked in today.</h5>
                            <p class="text-muted">Please check in to start your work day.</p>
                            <form action="{{ route('attendance.check-in') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="note">Note (Optional)</label>
                                    <textarea class="form-control" id="note" name="note" rows="3" placeholder="Add any notes regarding your attendance today..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Check In
                                </button>
                            </form>
                        </div>
                    @elseif($attendance && !$attendance->check_out)
                        <!-- Check Out Form -->
                        <div class="text-center p-4">
                            <div class="mb-4">
                                <i class="fas fa-user-clock fa-5x text-success"></i>
                            </div>
                            <h5>You checked in at {{ date('h:i A', strtotime($attendance->check_in)) }}</h5>
                            <p class="text-muted">Your current status: 
                                @if($attendance->status == 'Present')
                                    <span class="badge badge-success">Present</span>
                                @elseif($attendance->status == 'Late')
                                    <span class="badge badge-warning">Late</span>
                                @else
                                    <span class="badge badge-info">{{ $attendance->status }}</span>
                                @endif
                            </p>
                            
                            <!-- Work duration calculation -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Work Duration</h5>
                                    <div id="duration" class="display-4 text-primary">
                                        <!-- Will be updated by JavaScript -->
                                        00:00:00
                                    </div>
                                </div>
                            </div>
                            
                            <form action="{{ route('attendance.check-out') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="note">Note (Optional)</label>
                                    <textarea class="form-control" id="note" name="note" rows="3" placeholder="Add any notes regarding your work today...">{{ $attendance->note }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btn-lg px-5">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Check Out
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Already Checked Out -->
                        <div class="text-center p-4">
                            <div class="mb-4">
                                <i class="fas fa-clipboard-check fa-5x text-success"></i>
                            </div>
                            <h5>Your attendance has been recorded for today</h5>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Check In</h5>
                                            <p class="card-text font-weight-bold">{{ date('h:i A', strtotime($attendance->check_in)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Check Out</h5>
                                            <p class="card-text font-weight-bold">{{ date('h:i A', strtotime($attendance->check_out)) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Status</h5>
                                    <p class="card-text">
                                        @if($attendance->status == 'Present')
                                            <span class="badge badge-success">Present</span>
                                        @elseif($attendance->status == 'Late')
                                            <span class="badge badge-warning">Late</span>
                                        @elseif($attendance->status == 'Half-day')
                                            <span class="badge badge-info">Half-day</span>
                                        @elseif($attendance->status == 'Absent')
                                            <span class="badge badge-danger">Absent</span>
                                        @else
                                            <span class="badge badge-primary">{{ $attendance->status }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            @if($attendance->note)
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Note</h5>
                                    <p class="card-text">{{ $attendance->note }}</p>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Work duration calculation -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Work Duration</h5>
                                    <div class="display-4 text-primary">
                                        @php
                                            $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                                            $checkOut = \Carbon\Carbon::parse($attendance->check_out);
                                            $duration = $checkOut->diff($checkIn);
                                            echo sprintf('%02d:%02d:%02d', $duration->h, $duration->i, $duration->s);
                                        @endphp
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Function to update the clock
    function updateClock() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        
        hours = hours % 12;
        hours = hours ? hours : 12; // Hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        
        var timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
        document.getElementById('clock').textContent = timeString;
        
        // Update duration if checked in but not checked out
        @if($attendance && !$attendance->check_out)
            // Calculate duration
            var checkInTime = new Date('{{ $attendance->check_in }}').getTime();
            var currentTime = now.getTime();
            var diff = currentTime - checkInTime;
            
            var durationHours = Math.floor(diff / (1000 * 60 * 60));
            diff -= durationHours * (1000 * 60 * 60);
            var durationMinutes = Math.floor(diff / (1000 * 60));
            diff -= durationMinutes * (1000 * 60);
            var durationSeconds = Math.floor(diff / 1000);
            
            // Format duration
            durationHours = durationHours < 10 ? '0' + durationHours : durationHours;
            durationMinutes = durationMinutes < 10 ? '0' + durationMinutes : durationMinutes;
            durationSeconds = durationSeconds < 10 ? '0' + durationSeconds : durationSeconds;
            
            var durationString = durationHours + ':' + durationMinutes + ':' + durationSeconds;
            document.getElementById('duration').textContent = durationString;
        @endif
    }
    
    // Update the clock immediately and every second
    updateClock();
    setInterval(updateClock, 1000);
</script>
@endpush