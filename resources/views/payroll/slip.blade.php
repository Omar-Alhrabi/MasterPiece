@extends('layouts.admin')

@section('title', 'Pay Slip')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pay Slip</h1>
        <div>
            <a href="{{ route('payroll.show', $salary) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-eye fa-sm text-white-50"></i> View Details
            </a>
            <button onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-print fa-sm text-white-50"></i> Print
            </button>
        </div>
    </div>

    <!-- Pay Slip Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Salary Slip - {{ $monthNames[$salary->month] }} {{ $salary->year }}</h6>
            <div>
                <span class="badge {{ $salary->is_paid ? 'badge-success' : 'badge-warning' }}">
                    {{ $salary->is_paid ? 'PAID' : 'UNPAID' }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Company Information</h5>
                    <div class="border-left-primary pl-3">
                        <p class="mb-1"><strong>HR Management System</strong></p>
                        <p class="mb-1">123 Company Street</p>
                        <p class="mb-1">Business City, State 12345</p>
                        <p class="mb-1">Phone: (123) 456-7890</p>
                        <p class="mb-1">Email: hr@company.com</p>
                    </div>
                </div>
                <div class="col-md-6 text-md-right">
                    <h5 class="font-weight-bold">Employee Information</h5>
                    <div class="border-right-primary pr-3">
                        <p class="mb-1"><strong>{{ $salary->user->first_name }} {{ $salary->user->last_name }}</strong></p>
                        <p class="mb-1">Employee ID: {{ $salary->user->id }}</p>
                        <p class="mb-1">{{ $salary->user->jobPosition ? $salary->user->jobPosition->title : 'No Position' }}</p>
                        <p class="mb-1">{{ $salary->user->department ? $salary->user->department->name : 'No Department' }}</p>
                        <p class="mb-1">Email: {{ $salary->user->email }}</p>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 40%;">Salary Period:</th>
                                    <td>{{ $monthNames[$salary->month] }} {{ $salary->year }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Date:</th>
                                    <td>{{ $salary->payment_date->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td>{{ ucfirst(str_replace('_', ' ', $salary->payment_method)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <h5 class="font-weight-bold mb-3">Earnings & Deductions</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50%;">Description</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($salary->type == 'basic')
                                    <tr>
                                        <td>Basic Salary</td>
                                        <td class="text-right">${{ number_format($salary->amount, 2) }}</td>
                                    </tr>
                                @elseif($salary->type == 'bonus')
                                    <tr>
                                        <td>Bonus - {{ $salary->description }}</td>
                                        <td class="text-right">${{ number_format($salary->amount, 2) }}</td>
                                    </tr>
                                @elseif($salary->type == 'allowance')
                                    <tr>
                                        <td>Allowance - {{ $salary->description }}</td>
                                        <td class="text-right">${{ number_format($salary->amount, 2) }}</td>
                                    </tr>
                                @elseif($salary->type == 'overtime')
                                    <tr>
                                        <td>Overtime - {{ $salary->description }}</td>
                                        <td class="text-right">${{ number_format($salary->amount, 2) }}</td>
                                    </tr>
                                @elseif($salary->type == 'deduction')
                                    <tr>
                                        <td>Deduction - {{ $salary->description }}</td>
                                        <td class="text-right">-${{ number_format(abs($salary->amount), 2) }}</td>
                                    </tr>
                                @endif
                                
                                <tr class="bg-light font-weight-bold">
                                    <td>Net Pay</td>
                                    <td class="text-right">${{ number_format($salary->amount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="border p-3 bg-light">
                        <h6 class="font-weight-bold">Notes:</h6>
                        <p class="mb-0">{{ $salary->description }}</p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="border-top pt-2">
                        <p class="mb-0">Employee Signature</p>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="border-top pt-2">
                        <p class="mb-0">Authorized Signature</p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-md-12 text-center">
                    <p class="small text-muted mb-0">This is a computer-generated document. No signature is required.</p>
                    <p class="small text-muted mb-0">Generated on: {{ now()->format('F d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .btn, .d-sm-flex {
            display: none !important;
        }
    }
</style>
@endpush