@extends('layouts.admin')

@section('title', 'Send Notifications')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Send Notifications</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Create New Notification</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.notifications.send') }}">
                @csrf
                
                <div class="form-group">
                    <label for="title">Notification Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="link">Link (Optional)</label>
                    <input type="text" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link') }}" placeholder="e.g. /projects/5">
                    @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">If provided, the notification will be clickable and redirect to this URL.</small>
                </div>
                
                <div class="form-group">
                    <label>Recipients</label>
                    <div class="form-check">
                        <input class="form-check-input recipient-option" type="radio" name="recipients" id="recipients_all" value="all" {{ old('recipients', 'all') == 'all' ? 'checked' : '' }}>
                        <label class="form-check-label" for="recipients_all">
                            All Employees
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input recipient-option" type="radio" name="recipients" id="recipients_department" value="department" {{ old('recipients') == 'department' ? 'checked' : '' }}>
                        <label class="form-check-label" for="recipients_department">
                            Specific Department
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input recipient-option" type="radio" name="recipients" id="recipients_selected" value="selected" {{ old('recipients') == 'selected' ? 'checked' : '' }}>
                        <label class="form-check-label" for="recipients_selected">
                            Selected Employees
                        </label>
                    </div>
                </div>
                
                <div class="form-group recipient-department d-none">
                    <label for="department_id">Department</label>
                    <select class="form-control @error('department_id') is-invalid @enderror" id="department_id" name="department_id">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group recipient-selected d-none">
                    <label for="user_ids">Select Employees</label>
                    <select class="form-control select2 @error('user_ids') is-invalid @enderror" id="user_ids" name="user_ids[]" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (is_array(old('user_ids')) && in_array($user->id, old('user_ids'))) ? 'selected' : '' }}>
                                {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_ids')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Notification
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--multiple {
        min-height: 38px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'Select employees',
        width: '100%'
    });
    
    // Toggle recipient options
    $('.recipient-option').change(function() {
        let value = $(this).val();
        
        // Hide all recipient-specific divs
        $('.recipient-department, .recipient-selected').addClass('d-none');
        
        // Show the selected one
        if (value === 'department') {
            $('.recipient-department').removeClass('d-none');
        } else if (value === 'selected') {
            $('.recipient-selected').removeClass('d-none');
        }
    });
    
    // Trigger change on page load to reflect the current selection
    $('.recipient-option:checked').trigger('change');
});
</script>
@endpush