<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#06c1db">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('image/favicon.png') }}" type="image/png">

    <title>{{ config('app.name', 'HR MS') }} - @yield('title')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Enterprise 360</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Sections
            </div>

            <!-- Nav Item - Employees -->
            <li class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEmployees"
                    aria-expanded="{{ request()->routeIs('employees.*') ? 'true' : 'false' }}" aria-controls="collapseEmployees">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Employees</span>
                </a>
                <div id="collapseEmployees" class="collapse {{ request()->routeIs('employees.*') ? 'show' : '' }}" aria-labelledby="headingEmployees"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Employee Management:</h6>
                        <a class="collapse-item {{ request()->routeIs('employees.index') ? 'active' : '' }}" href="{{ route('employees.index') }}">All Employees</a>
                        @if(Auth::user()->isAdmin())
                        <a class="collapse-item {{ request()->routeIs('employees.create') ? 'active' : '' }}" href="{{ route('employees.create') }}">Create New Employee</a>
                        @endif
                        <a class="collapse-item {{ request()->routeIs('employees.organization') ? 'active' : '' }}" href="{{ route('employees.organization') }}">Organization Chart</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Departments -->
            <li class="nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDepartments"
                    aria-expanded="{{ request()->routeIs('departments.*') ? 'true' : 'false' }}" aria-controls="collapseDepartments">
                    <i class="fas fa-fw fa-building"></i>
                    <span>Departments</span>
                </a>
                <div id="collapseDepartments" class="collapse {{ request()->routeIs('departments.*') ? 'show' : '' }}" aria-labelledby="headingDepartments"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Department Management:</h6>
                        <a class="collapse-item {{ request()->routeIs('departments.index') ? 'active' : '' }}" href="{{ route('departments.index') }}">All Departments</a>
                        @if(Auth::user()->isAdmin())
                        <a class="collapse-item {{ request()->routeIs('departments.create') ? 'active' : '' }}" href="{{ route('departments.create') }}">Create New Department</a>
                        @endif
                    </div>
                </div>
            </li>

            <!-- Payroll Management -->
            @if(Auth::user()->isAdmin())
            <li class="nav-item {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePayroll"
                    aria-expanded="{{ request()->routeIs('payroll.*') ? 'true' : 'false' }}" aria-controls="collapsePayroll">
                    <i class="fas fa-fw fa-money-bill"></i>
                    <span>Payroll Management</span>
                </a>
                <div id="collapsePayroll" class="collapse {{ request()->routeIs('payroll.*') ? 'show' : '' }}" aria-labelledby="headingPayroll"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Payroll Management:</h6>
                        <a class="collapse-item {{ request()->routeIs('payroll.index') ? 'active' : '' }}" href="{{ route('payroll.index') }}">
                            <i class="fas fa-list fa-sm"></i> All salaries
                        </a>
                        <a class="collapse-item {{ request()->routeIs('payroll.calculate') ? 'active' : '' }}" href="{{ route('payroll.calculate') }}">
                            <i class="fas fa-calculator fa-sm"></i>Payroll Calculation
                        </a>
                        <a class="collapse-item {{ request()->routeIs('payroll.history') ? 'active' : '' }}" href="{{ route('payroll.history') }}">
                            <i class="fas fa-history fa-sm"></i> Payments Report
                        </a>
                        <a class="collapse-item {{ request()->routeIs('payroll.reports') ? 'active' : '' }}" href="{{ route('payroll.reports') }}">
                            <i class="fas fa-chart-bar fa-sm"></i> payroll reports
                        </a>
                        <a class="collapse-item {{ request()->routeIs('payroll.create') ? 'active' : '' }}" href="{{ route('payroll.create') }}">
                            <i class="fas fa-plus fa-sm"></i> Create new salary
                        </a>
                    </div>
                </div>
            </li>
            @endif

            <!-- Client -->
            <li class="nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClients"
                    aria-expanded="{{ request()->routeIs('clients.*') ? 'true' : 'false' }}" aria-controls="collapseClients">
                    <i class="fas fa-fw fa-handshake"></i>
                    <span>Clients</span>
                </a>
                <div id="collapseClients" class="collapse {{ request()->routeIs('clients.*') ? 'show' : '' }}" aria-labelledby="headingClients"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Client Management:</h6>
                        <a class="collapse-item {{ request()->routeIs('clients.index') ? 'active' : '' }}" href="{{ route('clients.index') }}">All Clients</a>
                        @if(Auth::user()->isAdmin())
                        <a class="collapse-item {{ request()->routeIs('clients.create') ? 'active' : '' }}" href="{{ route('clients.create') }}">Add New Client</a>
                        @endif
                    </div>
                </div>
            </li>

            <!-- Project -->
            <li class="nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProjects"
                    aria-expanded="{{ request()->routeIs('projects.*') ? 'true' : 'false' }}" aria-controls="collapseProjects">
                    <i class="fas fa-fw fa-project-diagram"></i>
                    <span>Projects</span>
                </a>
                <div id="collapseProjects" class="collapse {{ request()->routeIs('projects.*') ? 'show' : '' }}" aria-labelledby="headingProjects"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Project Management:</h6>
                        <a class="collapse-item {{ request()->routeIs('projects.index') ? 'active' : '' }}" href="{{ route('projects.index') }}">All Projects</a>
                        @if(Auth::user()->isAdmin())
                        <a class="collapse-item {{ request()->routeIs('projects.create') ? 'active' : '' }}" href="{{ route('projects.create') }}">Create New Project</a>
                        @endif
                    </div>
                </div>
            </li>

            <!-- leave Management -->
            <li class="nav-item {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLeave"
                    aria-expanded="{{ request()->routeIs('leaves.*') ? 'true' : 'false' }}" aria-controls="collapseLeave">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Leave Management</span>
                </a>
                <div id="collapseLeave" class="collapse {{ request()->routeIs('leaves.*') ? 'show' : '' }}" aria-labelledby="headingLeave"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Leave Management:</h6>
                        <a class="collapse-item {{ request()->routeIs('leaves.requests') ? 'active' : '' }}" href="{{ route('leaves.requests') }}">Leave Requests</a>
                        <a class="collapse-item {{ request()->routeIs('leaves.create') ? 'active' : '' }}" href="{{ route('leaves.create') }}">Create Leave Type</a>
                        @if(Auth::user()->isAdmin())
                        <a class="collapse-item {{ request()->routeIs('leaves.reports') ? 'active' : '' }}" href="{{ route('leaves.reports') }}">Leave Reports</a>
                        <a class="collapse-item {{ request()->routeIs('leaves.approve') ? 'active' : '' }}" href="{{ route('leaves.approve') }}">Approval of Leave</a>
                        @endif
                    </div>
                </div>
            </li>

            <!-- Attendance -->
            <li class="nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAttendance"
                    aria-expanded="{{ request()->routeIs('attendance.*') ? 'true' : 'false' }}" aria-controls="collapseAttendance">
                    <i class="fas fa-fw fa-clock"></i>
                    <span>Attendance</span>
                </a>
                <div id="collapseAttendance" class="collapse {{ request()->routeIs('attendance.*') ? 'show' : '' }}" aria-labelledby="headingAttendance"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Attendance Management:</h6>
                        <a class="collapse-item {{ request()->routeIs('attendance.record') ? 'active' : '' }}" href="{{ route('attendance.record') }}">Record Attendance</a>
                        <a class="collapse-item {{ request()->routeIs('attendance.index') ? 'active' : '' }}" href="{{ route('attendance.index') }}">Attendance list</a>
                        @if(Auth::user()->isAdmin())
                        <a class="collapse-item {{ request()->routeIs('attendance.reports') ? 'active' : '' }}" href="{{ route('attendance.reports') }}">Attendance Reports</a>
                        @endif
                    </div>
                </div>
            </li>

            <!-- Tasks -->
            <li class="nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTasks"
                    aria-expanded="{{ request()->routeIs('tasks.*') ? 'true' : 'false' }}" aria-controls="collapseTasks">
                    <i class="fas fa-fw fa-tasks"></i>
                    <span>Tasks</span>
                </a>
                <div id="collapseTasks" class="collapse {{ request()->routeIs('tasks.*') ? 'show' : '' }}" aria-labelledby="headingTasks"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Task Management:</h6>
                        <a class="collapse-item {{ request()->routeIs('tasks.index') ? 'active' : '' }}" href="{{ route('tasks.index') }}">All Tasks</a>
                        <a class="collapse-item {{ request()->routeIs('tasks.create') ? 'active' : '' }}" href="{{ route('tasks.create') }}">Create New Task</a>
                    </div>
                </div>
            </li>
            <!-- Nav Item - Messages -->

            <li class="nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('messages.index') }}">
                    <i class="fas fa-envelope fa-fw"></i>
                    <span>Messages</span>
                    <span class="badge badge-danger badge-counter message-count point" id="message-unread-count" style="padding: 7px; position: absolute; top: 30px;"></span>
                </a>
            </li>
            <!-- permissions Management -->
            <!--     <li class="nav-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePermissions"
                    aria-expanded="{{ request()->routeIs('permissions.*') ? 'true' : 'false' }}" aria-controls="collapsePermissions">
                    <i class="fas fa-fw fa-lock"></i>
                    <span>Permissions</span>
                </a>
                <div id="collapsePermissions" class="collapse {{ request()->routeIs('permissions.*') ? 'show' : '' }}" aria-labelledby="headingPermissions"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Permission Management:</h6>
                        <a class="collapse-item {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">User Management</a>
                        <a class="collapse-item {{ request()->routeIs('roles.index') ? 'active' : '' }}" href="{{ route('roles.index') }}">Role Management</a>
                        <a class="collapse-item {{ request()->routeIs('permissions.index') ? 'active' : '' }}" href="{{ route('permissions.index') }}">Modify Permissions</a>
                    </div>
                </div>
            </li> -->
            @if(Auth::user()->isAdmin())
            <li class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.notifications.index') }}">
                    <i class="fas fa-fw fa-bell"></i>
                    <span>Send Notifications</span>
                </a>
            </li>
            @endif
            <!-- Nav Item - Activities -->
            <!--  <li class="nav-item {{ request()->routeIs('activities') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('activities') }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Activities</span>
                </a>
            </li> -->

            <!-- Nav Item - Events -->
            <li class="nav-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('events.index') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Events</span>


                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="text-center d-none d-md-inline">
                        <button class="rounded-circle border-0" id="sidebarToggle"><i class="fa-solid fa-list"></i></button>
                    </div>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="{{ route('search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." name="query" aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Also update the mobile search form -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                        <form class="form-inline mr-auto w-100 navbar-search" action="{{ route('search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." name="query" aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">{{ auth()->user()->unreadNotifications->count() > 0 ? (auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count()) : '' }}</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown" style="max-height: 400px; overflow-y: auto;">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                @forelse(auth()->user()->notifications->take(10) as $notification)
                                <a class="dropdown-item d-flex align-items-center {{ $notification->read_at ? '' : 'bg-light' }}" href="{{ route('notifications.markAsRead', $notification->id) }}">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-{{ $notification->read_at ? 'secondary' : 'primary' }}">
                                            <i class="fas fa-{{ $notification->data['icon'] ?? 'bell' }} text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">{{ $notification->created_at->format('F d, Y') }}</div>
                                        <span class="{{ $notification->read_at ? '' : 'font-weight-bold' }}">
                                            @if(isset($notification->data['message']))
                                            {{ $notification->data['message'] }}
                                            @elseif(isset($notification->data['title']))
                                            {{ $notification->data['title'] }}
                                            @else
                                            New notification
                                            @endif
                                        </span>
                                    </div>
                                </a>
                                @empty
                                <div class="dropdown-item text-center">
                                    <span>No new notifications</span>
                                </div>
                                @endforelse
                                <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.index') }}">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <!-- End of sidebar addition -->

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter message-count" id="topbar-message-count"></span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Messages Center
                                </h6>
                                <div id="message-preview-container">
                                    <div class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <a class="dropdown-item text-center small text-gray-500" href="{{ route('messages.index') }}">Read More Messages</a>
                            </div>
                        </li>
                        <!-- End of topbar messages replacement -->

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                <img class="img-profile rounded-circle" src="{{ asset('https://images.icon-icons.com/1378/PNG/512/avatardefault_92824.png') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    @stack('scripts')
    @push('scripts')
    <script>
        $(document).ready(function() {
            const searchInput = $('.navbar-search input[name="query"]');
            const searchDropdown = $('<div class="dropdown-menu dropdown-menu-right p-0 shadow-sm" id="search-results-dropdown" style="width: 300px; max-height: 400px; overflow-y: auto;"></div>');

            // Add the dropdown after the search form
            searchInput.closest('.navbar-search').after(searchDropdown);

            // Function to handle search
            function performSearch() {
                const query = searchInput.val().trim();

                if (query.length < 2) {
                    searchDropdown.hide();
                    return;
                }

                // Show loading indicator
                searchDropdown.html('<div class="text-center py-2"><i class="fas fa-spinner fa-spin"></i> Searching...</div>').show();

                $.ajax({
                    url: "{{ route('search.ajax') }}",
                    type: 'GET',
                    data: {
                        query: query
                    },
                    dataType: 'json',
                    success: function(data) {
                        let results = '';
                        let total = data.employees.length + data.projects.length + data.clients.length + data.tasks.length;

                        if (total === 0) {
                            results = '<div class="text-center py-3">No results found</div>';
                        } else {
                            // Employees
                            if (data.employees.length > 0) {
                                results += '<h6 class="dropdown-header">Employees</h6>';
                                data.employees.forEach(function(employee) {
                                    results += `<a class="dropdown-item" href="${employee.url}">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">${employee.name}</div>
                                            <div class="small text-gray-500">${employee.email}</div>
                                        </div>
                                    </div>
                                </a>`;
                                });
                            }

                            // Projects
                            if (data.projects.length > 0) {
                                results += '<h6 class="dropdown-header">Projects</h6>';
                                data.projects.forEach(function(project) {
                                    results += `<a class="dropdown-item" href="${project.url}">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <i class="fas fa-project-diagram text-success"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">${project.name}</div>
                                            <div class="small text-gray-500">${project.status}</div>
                                        </div>
                                    </div>
                                </a>`;
                                });
                            }

                            // Clients
                            if (data.clients.length > 0) {
                                results += '<h6 class="dropdown-header">Clients</h6>';
                                data.clients.forEach(function(client) {
                                    results += `<a class="dropdown-item" href="${client.url}">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <i class="fas fa-handshake text-info"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">${client.name}</div>
                                            <div class="small text-gray-500">${client.company}</div>
                                        </div>
                                    </div>
                                </a>`;
                                });
                            }

                            // Tasks
                            if (data.tasks.length > 0) {
                                results += '<h6 class="dropdown-header">Tasks</h6>';
                                data.tasks.forEach(function(task) {
                                    results += `<a class="dropdown-item" href="${task.url}">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <i class="fas fa-tasks text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">${task.name}</div>
                                            <div class="small text-gray-500">${task.status}</div>
                                        </div>
                                    </div>
                                </a>`;
                                });
                            }

                            results += `<div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center small text-gray-500" href="{{ route('search') }}?query=${encodeURIComponent(query)}">
                                View All Results
                            </a>`;
                        }

                        searchDropdown.html(results).show();
                    },
                    error: function() {
                        searchDropdown.html('<div class="text-center py-2">Error occurred during search</div>').show();
                    }
                });
            }

            // Keyup event with debounce
            let timer;
            searchInput.on('keyup', function() {
                clearTimeout(timer);
                timer = setTimeout(performSearch, 300);
            });

            // Clicking outside closes the dropdown
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.navbar-search').length && !$(e.target).closest('#search-results-dropdown').length) {
                    searchDropdown.hide();
                }
            });

            // Focus on search input shows dropdown if it has content
            searchInput.on('focus', function() {
                if (searchInput.val().trim().length >= 2) {
                    searchDropdown.show();
                }
            });

            // When form is submitted, don't show dropdown
            searchInput.closest('form').on('submit', function() {
                searchDropdown.hide();
            });
        });
        $(document).ready(function() {
            // Function to update notifications
            function updateNotifications() {
                $.ajax({
                    url: "{{ route('notifications.getLatest') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Update notification count
                        let count = data.count;
                        let countDisplay = count > 0 ? (count > 99 ? '99+' : count) : '';
                        $('#alertsDropdown .badge-counter').text(countDisplay);

                        // Update notification list (optional advanced feature)
                        if (data.notifications && data.notifications.length > 0) {
                            // Implement if you want to dynamically update the dropdown content
                        }
                    }
                });
            }

            // Update notifications every 60 seconds
            setInterval(updateNotifications, 60000);
        });
        // Function to update message count and preview
        $(document).ready(function() {
            // Function to update message count and preview
            function updateMessages() {
                $.ajax({
                    url: "{{ route('messages.get-unread-count') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let count = data.count;
                        let countDisplay = count > 0 ? (count > 99 ? '99+' : count) : '';
                        $('.message-count').text(countDisplay);

                        $.ajax({
                            url: "{{ route('messages.recent') }}",
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                if (response.html) {
                                    $('#message-preview-container').html(response.html);
                                } else {
                                    $('#message-preview-container').html('<div class="dropdown-item text-center">No new messages</div>');
                                }
                            },
                            error: function() {
                                $('#message-preview-container').html('<div class="dropdown-item text-center">Error loading messages</div>');
                            }
                        });
                    },
                    error: function() {
                        $('.message-count').text('');
                        $('#message-preview-container').html('<div class="dropdown-item text-center">Error loading messages</div>');
                    }
                });
            }

            updateMessages();

            setInterval(updateMessages, 30000);

            $('#messagesDropdown').on('click', function() {
                updateMessages();
            });
        });
    </script>
    @endpush
</body>

</html>