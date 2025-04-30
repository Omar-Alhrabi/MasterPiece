@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Account Settings</h1>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Settings Menu</h6>
                </div>
                <div class="card-body p-0">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-notifications-tab" data-toggle="pill" href="#v-pills-notifications" role="tab" aria-controls="v-pills-notifications" aria-selected="true">
                            <i class="fas fa-bell fa-fw mr-2"></i> Notifications
                        </a>
                        <a class="nav-link" id="v-pills-appearance-tab" data-toggle="pill" href="#v-pills-appearance" role="tab" aria-controls="v-pills-appearance" aria-selected="false">
                            <i class="fas fa-palette fa-fw mr-2"></i> Appearance
                        </a>
                        <a class="nav-link" id="v-pills-privacy-tab" data-toggle="pill" href="#v-pills-privacy" role="tab" aria-controls="v-pills-privacy" aria-selected="false">
                            <i class="fas fa-shield-alt fa-fw mr-2"></i> Privacy
                        </a>
                        <a class="nav-link" id="v-pills-account-tab" data-toggle="pill" href="#v-pills-account" role="tab" aria-controls="v-pills-account" aria-selected="false">
                            <i class="fas fa-user-cog fa-fw mr-2"></i> Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-notifications" role="tabpanel" aria-labelledby="v-pills-notifications-tab">
                            <h5 class="mb-4">Notification Settings</h5>
                            <form>
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="emailNotifications" checked>
                                    <label class="custom-control-label" for="emailNotifications">Email Notifications</label>
                                    <small class="form-text text-muted">Receive email notifications for important updates.</small>
                                </div>
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="taskAssignments" checked>
                                    <label class="custom-control-label" for="taskAssignments">Task Assignments</label>
                                    <small class="form-text text-muted">Receive notifications when you are assigned to a task.</small>
                                </div>
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="projectUpdates" checked>
                                    <label class="custom-control-label" for="projectUpdates">Project Updates</label>
                                    <small class="form-text text-muted">Receive notifications about project updates.</small>
                                </div>
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="leaveRequests" checked>
                                    <label class="custom-control-label" for="leaveRequests">Leave Requests</label>
                                    <small class="form-text text-muted">Receive notifications about leave request status updates.</small>
                                </div>
                                <button type="button" class="btn btn-primary mt-3">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-appearance" role="tabpanel" aria-labelledby="v-pills-appearance-tab">
                            <h5 class="mb-4">Appearance Settings</h5>
                            <form>
                                <div class="form-group">
                                    <label for="themeSelect">Theme</label>
                                    <select class="form-control" id="themeSelect">
                                        <option value="light" selected>Light</option>
                                        <option value="dark">Dark</option>
                                        <option value="system">Use System Setting</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fontSizeSelect">Font Size</label>
                                    <select class="form-control" id="fontSizeSelect">
                                        <option value="small">Small</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="large">Large</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary mt-3">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-privacy" role="tabpanel" aria-labelledby="v-pills-privacy-tab">
                            <h5 class="mb-4">Privacy Settings</h5>
                            <form>
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="showEmail" checked>
                                    <label class="custom-control-label" for="showEmail">Show Email to Other Users</label>
                                    <small class="form-text text-muted">Allow other users to see your email address.</small>
                                </div>
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="showPhone" checked>
                                    <label class="custom-control-label" for="showPhone">Show Phone Number to Other Users</label>
                                    <small class="form-text text-muted">Allow other users to see your phone number.</small>
                                </div>
                                <button type="button" class="btn btn-primary mt-3">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-account" role="tabpanel" aria-labelledby="v-pills-account-tab">
                            <h5 class="mb-4">Account Settings</h5>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> These actions are permanent and cannot be undone.
                            </div>
                            <div class="mt-4">
                                <h6>Deactivate Account</h6>
                                <p>Temporarily disable your account. You can reactivate it later.</p>
                                <button type="button" class="btn btn-warning">
                                    <i class="fas fa-user-slash"></i> Deactivate Account
                                </button>
                            </div>
                            <hr class="my-4">
                            <div class="mt-4">
                                <h6>Delete Account</h6>
                                <p>Permanently delete your account and all associated data.</p>
                                <button type="button" class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i> Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection