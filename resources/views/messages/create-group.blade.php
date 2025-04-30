@extends('layouts.admin')

@section('title', 'New Group Chat')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <a href="{{ route('messages.index') }}" class="btn btn-circle btn-sm btn-light mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        New Group Chat
    </h1>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Create Group Chat</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('messages.store-group') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Group Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="users">Select Participants:</label>
                        <select class="form-control select2-multiple" id="users" name="users[]" multiple required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('users')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @error('users.*')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="message">First Message:</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        @error('message')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-0 text-right">
                        <a href="{{ route('messages.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success ml-2">
                            <i class="fas fa-users mr-1"></i> Create Group
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #4e73df;
        border: 1px solid #4e73df;
        color: white;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #f8f9fc;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-multiple').select2({
            placeholder: "Select group participants",
            width: '100%',
            closeOnSelect: false
        });
    });
</script>
@endpush