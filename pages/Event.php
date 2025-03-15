<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f8f9fa;
        }

        .header {
            background-color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eaeaea;
        }

        .header h1 {
            color: #5f6368;
            font-size: 24px;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .add-event-btn {
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 24px;
            padding: 12px 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .month-title {
            font-size: 24px;
            font-weight: bold;
        }

        .view-options {
            display: flex;
            gap: 10px;
        }

        .view-btn {
            padding: 8px 16px;
            border-radius: 20px;
            border: none;
            background-color: #e8eaed;
            cursor: pointer;
        }

        .view-btn.active {
            background-color: #6366f1;
            color: white;
        }

        .navigation {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #5f6368;
        }

        .today-btn {
            background-color: #e8eaed;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
        }

        .sidebarsec {
            width: 200px;
            float: right;
            padding-left: 20px;
        }

        .sidebarsec h3 {
            margin-bottom: 15px;
            color: #5f6368;
        }

        .calendar-list {
            list-style: none;
        }

        .calendar-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .calendar-checkbox {
            width: 16px;
            height: 16px;
            margin-left: 8px;
            cursor: pointer;
        }

        .work-calendar {
            border: 2px solid #1a73e8;
        }

        .personal-calendar {
            border: 2px solid #1a73e8;
        }

        .important-calendar {
            border: 2px solid #1a73e8;
        }

        .travel-calendar {
            border: 2px solid #1a73e8;
        }

        .friends-calendar {
            border: 2px solid #1a73e8;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background-color: #eaeaea;
            border: 1px solid #eaeaea;
        }

        .day-header {
            background-color: #fff;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            color: #5f6368;
        }

        .calendar-day {
            background-color: #fff;
            min-height: 120px;
            padding: 5px;
            position: relative;
        }

        .day-number {
            font-size: 14px;
            color: #5f6368;
            text-align: center;
            margin-bottom: 10px;
        }

        .current-day {
            background-color: #6366f1;
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        .event {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin-bottom: 3px;
            color: white;
            cursor: pointer;
        }

        .work-event {
            background-color: #1a73e8;
        }

        .meeting-event {
            background-color: #4caf50;
        }

        .lunch-event {
            background-color: #1a73e8;
        }

        .event-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            width: 400px;
        }

        .event-form h3 {
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .save-btn {
            background-color: #1a73e8;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .cancel-btn {
            background-color: #f1f3f4;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

    </style>
</head>

<body id="page-top">
    
    <!-- Page Wrapper -->
    <div id="wrapper">
        
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
            </a>
            
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
                </li>
                
                <!-- Divider -->
                <hr class="sidebar-divider">
                
                <!-- Heading -->
                <div class="sidebar-heading">
                    Interface
                </div>
                
                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="./buttons.php">Buttons</a>
                        <a class="collapse-item" href="./cards.php">Cards</a>
                    </div>
                </div>
            </li>
            
            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Utilities</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="./utilities-color.php">Colors</a>
                <a class="collapse-item" href="./utilities-border.php">Borders</a>
                <a class="collapse-item" href="./utilities-animation.php">Animations</a>
                <a class="collapse-item" href="./utilities-other.php">Other</a>
            </div>
        </div>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider">
    
    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>
    
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
        aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="./login.php">Login</a>
                        <a class="collapse-item" href="./register.php">Register</a>
                        <a class="collapse-item" href="./forgot-password.php">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="./404.php">404 Page</a>
                        <a class="collapse-item" href="./blank.php">Blank Page</a>
                    </div>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEmployees"
                aria-expanded="true" aria-controls="collapseEmployees">
                <i class="fas fa-fw fa-folder"></i>
                <span>Employees</span>
            </a>
            <div id="collapseEmployees" class="collapse" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Employees Screens:</h6>
                <a class="collapse-item" href="./blank.php">Employees</a>
                <a class="collapse-item" href="./blank.php">Create new employee</a>
                <a class="collapse-item" href="./blank.php">Detiels Employee</a>
                <a class="collapse-item" href="./blank.php">Edit Employee</a>
                <a class="collapse-item" href="./blank.php">Organization Chart</a>
                
                    </div>
                </div>
            </li>
            
            <!-- Payroll Management -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePayroll"
                aria-expanded="true" aria-controls="collapsePayroll">
                <i class="fas fa-fw fa-folder"></i>
                <span>Payroll Management</span>
            </a>
            <div id="collapsePayroll" class="collapse" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="./blank.php">Calculating salaries</a>
                <a class="collapse-item" href="./blank.php">Payment history</a>
                <a class="collapse-item" href="./blank.php">payroll reports</a>
            </div>
                </div>
            </li>
            <!-- Client -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClients"
                aria-expanded="true" aria-controls="collapseClients">
                <i class="fas fa-fw fa-folder"></i>
                <span>Client </span>
            </a>
            <div id="collapseClients" class="collapse" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Clients</h6>
                <a class="collapse-item" href="./blank.php">Clients</a>
                <a class="collapse-item" href="./blank.php">Details Client </a>
                <a class="collapse-item" href="./blank.php">Add Client </a>
            </div>
        </div>
    </li>
    <!-- Project -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProjects"
        aria-expanded="true" aria-controls="collapseProjects">
        <i class="fas fa-fw fa-folder"></i>
        <span> Projects </span>
    </a>
    <div id="collapseProjects" class="collapse" aria-labelledby="headingPages"
    data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Clients</h6>
        <a class="collapse-item" href="./blank.php">Projects</a>
        <a class="collapse-item" href="./blank.php">Projects Details</a>
        <a class="collapse-item" href="./blank.php">Create Projects </a>
    </div>
</div>
</li>

<!-- leave Management -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseleave"
    aria-expanded="true" aria-controls="collapseleave">
    <i class="fas fa-fw fa-folder"></i>
    <span>leave</span>
</a>
<div id="collapseleave" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">leave</h6>
        <a class="collapse-item" href="./blank.php">Leave requests</a>
        <a class="collapse-item" href="./blank.php">Leave reports</a>
        <a class="collapse-item" href="./blank.php">Approval of leave</a>
    </div>
</div>
</li>

<!-- permissions Management -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsepermissions"
    aria-expanded="true" aria-controls="collapsepermissions">
    <i class="fas fa-fw fa-folder"></i>
    <span>permissions</span>
</a>
<div id="collapsepermissions" class="collapse" aria-labelledby="headingPages"
data-parent="#accordionSidebar">
<div class="bg-white py-2 collapse-inner rounded">
    <h6 class="collapse-header">permissions</h6>
    <a class="collapse-item" href="./blank.php">User Management</a>
    <a class="collapse-item" href="./blank.php">Role management</a>
    <a class="collapse-item" href="./blank.php">Modify permissions</a>
</div>
</div>
</li>


<!-- Nav Item - Charts -->
<li class="nav-item">
    <a class="nav-link" href="./charts.php">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Charts</span></a>
    </li>
    <!-- Nav Item - Activities -->
    <li class="nav-item">
        <a class="nav-link" href="./charts.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Activities</span></a>
        </li>
        
        
        
        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="./tables.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Tables</span></a>
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
                    
                    <!-- Topbar Search -->
                    <form
                    class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                        aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
                
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
                            <span class="badge badge-danger badge-counter">3+</span>
                        </a>
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">
                            Alerts Center
                        </h6>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">December 12, 2019</div>
                                <span class="font-weight-bold">A new monthly report is ready to download!</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-donate text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">December 7, 2019</div>
                                $290.29 has been deposited into your account!
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">December 2, 2019</div>
                                Spending Alert: We've noticed unusually high spending for your account.
                            </div>
                        </a>
                        <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                    </div>
                </li>
                
                <!-- Nav Item - Messages -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-envelope fa-fw"></i>
                    <!-- Counter - Messages -->
                    <span class="badge badge-danger badge-counter">7</span>
                </a>
                <!-- Dropdown - Messages -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                    Message Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="...">
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                            problem I've been having.</div>
                            <div class="small text-gray-500">Emily Fowler · 58m</div>
                        </div>
                    </a>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="dropdown-list-image mr-3">
                            <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="...">
                            <div class="status-indicator"></div>
                        </div>
                        <div>
                            <div class="text-truncate">I have the photos that you ordered last month, how
                                would you like them sent to you?</div>
                                <div class="small text-gray-500">Jae Chun · 1d</div>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="dropdown-list-image mr-3">
                                <img class="rounded-circle" src="img/undraw_profile_3.svg" alt="...">
                                <div class="status-indicator bg-warning"></div>
                            </div>
                            <div>
                                <div class="text-truncate">Last month's report looks great, I am very happy with
                                    the progress so far, keep up the good work!</div>
                                    <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                    alt="...">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div>
                                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                        told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>
                        
                        <div class="topbar-divider d-none d-sm-block"></div>
                        
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                            <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                            Activity Log
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
            
            
            <div class="container">
                <div class="calendar-header">
                    <div class="left-section">
                        <button class="add-event-btn" id="addEventBtn"> Add Event </button>
                    </div>
                    <div class="navigation">
                        <button class="nav-btn" id="prevMonth">❮</button>
                        <button class="nav-btn" id="nextMonth">❯</button>
                        <button class="today-btn" id="todayBtn">today</button>
                        <h2 class="month-title" id="monthTitle">March  2025</h2>
                    </div>
                    <div class="view-options">
                        <button class="view-btn">list</button>
                        <button class="view-btn">day</button>
                        <button class="view-btn">week</button>
                <button class="view-btn active">month</button>
            </div>
        </div>
        
        <div class="calendar-content">
            <div class="sidebarsec">
                <h3>My Calendars</h3>
                <ul class="calendar-list">
                    <li class="calendar-item">
                        <input type="checkbox" class="calendar-checkbox work-calendar" checked>
                        <span>Work</span>
                    </li>
                    <li class="calendar-item">
                        <input type="checkbox" class="calendar-checkbox personal-calendar" checked>
                        <span>Personal</span>
                    </li>
                    <li class="calendar-item">
                        <input type="checkbox" class="calendar-checkbox important-calendar" checked>
                        <span>Important</span>
                    </li>
                    <li class="calendar-item">
                        <input type="checkbox" class="calendar-checkbox travel-calendar" checked>
                        <span>Travel</span>
                    </li>
                    <li class="calendar-item">
                        <input type="checkbox" class="calendar-checkbox friends-calendar" checked>
                        <span>Friends</span>
                    </li>
                </ul>
            </div>
            
            <div class="calendar-grid" id="calendarGrid">
                <div class="day-header">Sun</div>
                <div class="day-header">Mon</div>
                <div class="day-header">Tue</div>
                <div class="day-header">Wed</div>
                <div class="day-header">Thu</div>
                <div class="day-header">Fri</div>
                <div class="day-header">Sat</div>
            </div>
        </div>
    </div>
    
    <div class="overlay" id="overlay"></div>
    <div class="event-form" id="eventForm">
        <h3>New Event</h3>
        <div class="form-group">
            <label for="eventTitle">title </label>
            <input type="text" id="eventTitle" placeholder="Add Event">
        </div>
        <div class="form-group">
            <label for="eventDate">date</label>
            <input type="date" id="eventDate">
        </div>
        <div class="form-group">
            <label for="eventTime">time</label>
            <input type="time" id="eventTime">
        </div>
        <div class="form-group">
            <label for="eventCalendar">Category</label>
            <select id="eventCalendar">
                <option value="work">Work</option>
                <option value="personal">Personal</option>
                <option value="important">Important</option>
                <option value="travel">travel</option>
                <option value="friends">Friends</option>
            </select>
        </div>
        <div class="form-buttons">
            <button class="cancel-btn" id="cancelEvent">Cancel</button>
            <button class="save-btn" id="saveEvent">save</button>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
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
            <a class="btn btn-primary" href="login.php">Logout</a>
        </div>
    </div>
</div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const currentYear = today.getFullYear();
        const currentMonth = today.getMonth();
        
        let displayedYear = 2025; 
        let displayedMonth = 2; 
        
        const monthNames = [
            "January", "February", "March ", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        
        
        const events = [
            { date: new Date(2025, 2, 11), title: "8:30a travel", type: "lunch-event" },
            { date: new Date(2025, 2, 13), title: "10a meeting", type: "meeting-event" }
        ];
        
        
        const calendarGrid = document.getElementById('calendarGrid');
        const monthTitle = document.getElementById('monthTitle');
        const prevMonthBtn = document.getElementById('prevMonth');
        const nextMonthBtn = document.getElementById('nextMonth');
        const todayBtn = document.getElementById('todayBtn');
        const addEventBtn = document.getElementById('addEventBtn');
        const eventForm = document.getElementById('eventForm');
        const overlay = document.getElementById('overlay');
        const cancelEventBtn = document.getElementById('cancelEvent');
        const saveEventBtn = document.getElementById('saveEvent');
        
        
        function generateCalendar(year, month) {
            
            while (calendarGrid.children.length > 7) {
                calendarGrid.removeChild(calendarGrid.lastChild);
            }
            
            
            monthTitle.textContent = `${monthNames[month]} ${year}`;
            
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            
            
            let firstDayOfWeek = firstDay.getDay();
            
            
            const prevMonthLastDay = new Date(year, month, 0).getDate();
            for (let i = 0; i < firstDayOfWeek; i++) {
                const dayNumber = prevMonthLastDay - firstDayOfWeek + i + 1;
                addDayToCalendar(dayNumber, false, new Date(year, month - 1, dayNumber));
            }
            
            for (let day = 1; day <= daysInMonth; day++) {
                const currentDate = new Date(year, month, day);
                const isToday = currentDate.getDate() === today.getDate() && 
                currentDate.getMonth() === today.getMonth() && 
                currentDate.getFullYear() === today.getFullYear();
                
                addDayToCalendar(day, true, currentDate, isToday);
            }
            
            
            const totalCells = 42; 
            const remainingCells = totalCells - (firstDayOfWeek + daysInMonth);
            
            for (let day = 1; day <= remainingCells; day++) {
                addDayToCalendar(day, false, new Date(year, month + 1, day));
            }
        }
        
        
        function addDayToCalendar(dayNumber, isCurrentMonth, date, isToday = false) {
            const dayCell = document.createElement('div');
            dayCell.className = 'calendar-day';
            
            if (!isCurrentMonth) {
                dayCell.style.color = '#bbb';
            }
            
            const dayNumberElement = document.createElement('div');
            if (isToday) {
                dayNumberElement.className = 'current-day';
            } else {
                dayNumberElement.className = 'day-number';
            }
            dayNumberElement.textContent = dayNumber;
            dayCell.appendChild(dayNumberElement);
            
            
            const dayEvents = events.filter(event => 
            event.date.getDate() === date.getDate() && 
            event.date.getMonth() === date.getMonth() && 
            event.date.getFullYear() === date.getFullYear()
        );
        
        dayEvents.forEach(event => {
            const eventElement = document.createElement('div');
            eventElement.className = `event ${event.type}`;
            eventElement.textContent = event.title;
            dayCell.appendChild(eventElement);
        });
        
        dayCell.addEventListener('click', () => {
            openEventForm(date);
        });
        
        calendarGrid.appendChild(dayCell);
    }
    
    
    function openEventForm(date) {
        document.getElementById('eventDate').valueAsDate = date;
        eventForm.style.display = 'block';
        overlay.style.display = 'block';
    }
    
    
    function closeEventForm() {
        eventForm.style.display = 'none';
        overlay.style.display = 'none';
    }
    
    
    function saveEvent() {
        const title = document.getElementById('eventTitle').value;
        const date = document.getElementById('eventDate').valueAsDate;
        const time = document.getElementById('eventTime').value;
        const calendar = document.getElementById('eventCalendar').value;
        
        if (!title || !date) {
            alert('الرجاء إدخال عنوان الحدث والتاريخ');
            return;
        }
        
        
        const newEvent = {
            date: date,
            title: time ? `${time} ${title}` : title,
            type: `${calendar}-event`
        };
        
        events.push(newEvent);
        closeEventForm();
        generateCalendar(displayedYear, displayedMonth);
    }
    
    
    function goToPrevMonth() {
        displayedMonth--;
        if (displayedMonth < 0) {
            displayedMonth = 11;
            displayedYear--;
        }
        generateCalendar(displayedYear, displayedMonth);
    }
    
    
    function goToNextMonth() {
        displayedMonth++;
        if (displayedMonth > 11) {
            displayedMonth = 0;
            displayedYear++;
        }
        generateCalendar(displayedYear, displayedMonth);
    }
    
    function goToToday() {
        displayedYear = today.getFullYear();
        displayedMonth = today.getMonth();
        generateCalendar(displayedYear, displayedMonth);
    }
    
    prevMonthBtn.addEventListener('click', goToPrevMonth);
    nextMonthBtn.addEventListener('click', goToNextMonth);
    todayBtn.addEventListener('click', goToToday);
    addEventBtn.addEventListener('click', () => openEventForm(new Date()));
    cancelEventBtn.addEventListener('click', closeEventForm);
    saveEventBtn.addEventListener('click', saveEvent);
    
    generateCalendar(displayedYear, displayedMonth);
});
</script>
</body>
</php>
