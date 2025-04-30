@extends('layouts.admin')

@section('title', 'Create Salary Record')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Salary Record</h1>
        <a href="{{ route('payroll.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Create Salary Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Salary Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('payroll.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">Employee <span class="text-danger">*</span></label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Select Employee</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }} - {{ $user->jobPosition ? $user->jobPosition->title : 'No Position' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="amount">Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                    id="amount" name="amount" value="{{ old('amount') }}" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="type">Salary Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="basic" {{ old('type') == 'basic' ? 'selected' : '' }}>Basic Salary</option>
                                <option value="bonus" {{ old('type') == 'bonus' ? 'selected' : '' }}>Bonus</option>
                                <option value="allowance" {{ old('type') == 'allowance' ? 'selected' : '' }}>Allowance</option>
                                <option value="deduction" {{ old('type') == 'deduction' ? 'selected' : '' }}>Deduction</option>
                                <option value="overtime" {{ old('type') == 'overtime' ? 'selected' : '' }}>Overtime</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="month">Month <span class="text-danger">*</span></label>
                                <select class="form-control @error('month') is-invalid @enderror" id="month" name="month" required>
                                    <option value="">Select Month</option>
                                    @php
                                        $months = [
                                            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                        ];
                                    @endphp
                                    @foreach($months as $key => $month)
                                        <option value="{{ $key }}" {{ old('month', date('n')) == $key ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="year">Year <span class="text-danger">*</span></label>
                                <select class="form-control @error('year') is-invalid @enderror" id="year" name="year" required>
                                    <option value="">Select Year</option>
                                    @foreach(range(date('Y') - 2, date('Y') + 1) as $year)
                                        <option value="{{ $year }}" {{ old('year', date('Y')) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="is_paid">Payment Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('is_paid') is-invalid @enderror" id="is_paid" name="is_paid" required>
                                <option value="1" {{ old('is_paid') == '1' ? 'selected' : '' }}>Paid</option>
                                <option value="0" {{ old('is_paid', '0') == '0' ? 'selected' : '' }}>Unpaid</option>
                            </select>
                            @error('is_paid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">Create Salary Record</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Show warning when selecting 'Deduction' type
        $('#type').change(function() {
            if ($(this).val() === 'deduction') {
                $('#amount').parent().addClass('border-danger');
                $('<div class="text-danger small mt-1">For deductions, enter the amount as a positive number. The system will handle it as a negative entry.</div>').insertAfter('#amount').parent();
            } else {
                $('#amount').parent().removeClass('border-danger');
                $('#amount').parent().siblings('.text-danger').remove();
            }
        });
    });
</script>
@endpush