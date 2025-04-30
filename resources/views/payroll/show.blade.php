@extends('layouts.admin')

@section('title', 'Salary Details')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Salary Record Details</h1>
        <div>
            <a href="{{ route('payroll.edit', $salary) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            <a href="{{ route('payroll.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <div class="row">
        <!-- Salary Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Salary Information</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th style="width: 25%;">Salary ID:</th>
                                    <td>{{ $salary->id }}</td>
                                </tr>
                                <tr>
                                    <th>Employee:</th>
                                    <td>
                                        <a href="{{ route('employees.show', $salary->user) }}" class="font-weight-bold text-primary">
                                            {{ $salary->user->first_name }} {{ $salary->user->last_name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td>${{ number_format($salary->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>
                                        @if($salary->type == 'basic')
                                            <span class="badge badge-primary">Basic Salary</span>
                                        @elseif($salary->type == 'bonus')
                                            <span class="badge badge-success">Bonus</span>
                                        @elseif($salary->type == 'allowance')
                                            <span class="badge badge-info">Allowance</span>
                                        @elseif($salary->type == 'deduction')
                                            <span class="badge badge-danger">Deduction</span>
                                        @elseif($salary->type == 'overtime')
                                            <span class="badge badge-warning">Overtime</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $salary->description }}</td>
                                </tr>
                                <tr>
                                    <th>For Period:</th>
                                    <td>
                                        @php
                                            $monthNames = [
                                                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                            ];
                                        @endphp
                                        {{ $monthNames[$salary->month] }} {{ $salary->year }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Date:</th>
                                    <td>{{ $salary->payment_date->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td>
                                        @if($salary->payment_method == 'bank_transfer')
                                            <span class="badge badge-info">Bank Transfer</span>
                                        @elseif($salary->payment_method == 'cash')
                                            <span class="badge badge-success">Cash</span>
                                        @elseif($salary->payment_method == 'cheque')
                                            <span class="badge badge-primary">Cheque</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>
                                        @if($salary->is_paid)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-warning">Pending Payment</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $salary->created_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $salary->updated_at->format('F d, Y h:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-4 text-center">
                        @if(!$salary->is_paid)
                            <form action="{{ route('payroll.mark-as-paid', $salary) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check mr-1"></i> Mark as Paid
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('payroll.generate-slip', $salary) }}" class="btn btn-info ml-2">
                            <i class="fas fa-file-pdf mr-1"></i> Generate Pay Slip
                        </a>
                        
                        <button type="button" class="btn btn-danger ml-2" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Employee Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Employee Information</h6>
                </div>
                <div class="card-body text-center">
                    <img class="img-profile rounded-circle mb-3" src="{{ asset('https://images.icon-icons.com/1378/PNG/512/avatardefault_92824.png') }}" alt="Profile Image" style="width: 100px; height: 100px;">
                    <h5 class="font-weight-bold">{{ $salary->user->first_name }} {{ $salary->user->last_name }}</h5>
                    <p class="text-muted">
                        {{ $salary->user->jobPosition ? $salary->user->jobPosition->title : 'No Position Assigned' }}
                    </p>
                    
                    <div class="text-left mt-4">
                        <p class="mb-2">
                            <i class="fas fa-envelope mr-2 text-primary"></i> {{ $salary->user->email }}
                        </p>
                        
                        @if($salary->user->phone_number)
                        <p class="mb-2">
                            <i class="fas fa-phone mr-2 text-primary"></i> {{ $salary->user->phone_number }}
                        </p>
                        @endif
                        
                        @if($salary->user->department)
                        <p class="mb-2">
                            <i class="fas fa-building mr-2 text-primary"></i> {{ $salary->user->department->name }}
                        </p>
                        @endif
                        
                        @if($salary->user->employment_status)
                        <p class="mb-2">
                            <i class="fas fa-user-tie mr-2 text-primary"></i> {{ ucfirst($salary->user->employment_status) }}
                        </p>
                        @endif
                        
                        @if($salary->user->hire_date)
                        <p class="mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-primary"></i> Hired: {{ $salary->user->hire_date->format('M d, Y') }}
                        </p>
                        @endif
                        
                        @if($salary->user->salary)
                        <p class="mb-2">
                            <i class="fas fa-money-bill-wave mr-2 text-primary"></i> Base Salary: ${{ number_format($salary->user->salary, 2) }}
                        </p>
                        @endif
                    </div>
                    
                    <a href="{{ route('employees.show', $salary->user) }}" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-user mr-1"></i> View Full Profile
                    </a>
                </div>
            </div>
            
            <!-- Recent Payments -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Payments</h6>
                </div>
                <div class="card-body">
                    @php
                        $recentPayments = \App\Models\Salary::where('user_id', $salary->user_id)
                                                    ->where('id', '!=', $salary->id)
                                                    ->orderBy('payment_date', 'desc')
                                                    ->limit(5)
                                                    ->get();
                    @endphp
                    
                    @if($recentPayments->count() > 0)
                        <div class="list-group">
                            @foreach($recentPayments as $payment)
                                <a href="{{ route('payroll.show', $payment) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $monthNames[$payment->month] }} {{ $payment->year }}</h6>
                                        <small>${{ number_format($payment->amount, 2) }}</small>
                                    </div>
                                    <p class="mb-1">{{ ucfirst($payment->type) }}</p>
                                    <small>{{ $payment->payment_date->format('M d, Y') }} - 
                                        @if($payment->is_paid)
                                            <span class="text-success">Paid</span>
                                        @else
                                            <span class="text-warning">Pending</span>
                                        @endif
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted">No recent payment records found.</p>
                    @endif
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('payroll.history', ['user_id' => $salary->user_id]) }}" class="btn btn-sm btn-primary">
                            View All Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Salary Record</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this salary record for {{ $salary->user->first_name }} {{ $salary->user->last_name }}? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('payroll.destroy', $salary) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection