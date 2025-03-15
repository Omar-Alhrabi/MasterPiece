
<?php require_once "components/header.php" ?>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once "components/sidebar.php" ?>

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

            <!-- Navbar -->  
            <?php require_once "components/navbar.php" ?>
            <!-- End Navbar -->   
              
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                </div>
                <!-- Content Row -->
                <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                     <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                   Earnings (Monthly)</div>
               <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
           </div>
           <div class="col-auto">
               <i class="fas fa-calendar fa-2x text-gray-300"></i>
           </div>
       </div>
   </div>
                        </div>
                    </div>
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
   <div class="card-body">
       <div class="row no-gutters align-items-center">
           <div class="col mr-2">
               <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                   Earnings (Annual)</div>
               <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
           </div>
           <div class="col-auto">
               <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
           </div>
       </div>
   </div>
                        </div>
                    </div>
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
   <div class="card-body">
       <div class="row no-gutters align-items-center">
           <div class="col mr-2">
               <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
               </div>
               <div class="row no-gutters align-items-center">
                   <div class="col-auto">
                       <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                   </div>
                   <div class="col">
                       <div class="progress progress-sm mr-2">
  <div class="progress-bar bg-info" role="progressbar"
      style="width: 50%" aria-valuenow="50" aria-valuemin="0"
      aria-valuemax="100"></div>
                       </div>
                   </div>
               </div>
           </div>
           <div class="col-auto">
               <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
           </div>
       </div>
   </div>
                        </div>
                    </div>
                    <!-- Pending Requests Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
   <div class="card-body">
       <div class="row no-gutters align-items-center">
           <div class="col mr-2">
               <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                   Pending Requests</div>
               <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
           </div>
           <div class="col-auto">
               <i class="fas fa-comments fa-2x text-gray-300"></i>
           </div>
       </div>
   </div>
                        </div>
                    </div>
                </div>
                <!-- Content Row -->
                <div class="row">
                    <!-- Area Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
   <!-- Card Header - Dropdown -->
   <div
       class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
       <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
       <div class="dropdown no-arrow">
           <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
           </a>
           <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
               aria-labelledby="dropdownMenuLink">
               <div class="dropdown-header">Dropdown Header:</div>
               <a class="dropdown-item" href="#">Action</a>
               <a class="dropdown-item" href="#">Another action</a>
               <div class="dropdown-divider"></div>
               <a class="dropdown-item" href="#">Something else here</a>
           </div>
       </div>
   </div>
   <!-- Card Body -->
   <div class="card-body">
       <div class="chart-area">
           <canvas id="myAreaChart"></canvas>
       </div>
   </div>
                        </div>
                    </div>
                    <!-- Pie Chart -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
   <!-- Card Header - Dropdown -->
   <div
       class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
       <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
       <div class="dropdown no-arrow">
           <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
           </a>
           <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
               aria-labelledby="dropdownMenuLink">
               <div class="dropdown-header">Dropdown Header:</div>
               <a class="dropdown-item" href="#">Action</a>
               <a class="dropdown-item" href="#">Another action</a>
               <div class="dropdown-divider"></div>
               <a class="dropdown-item" href="#">Something else here</a>
           </div>
       </div>
   </div>
   <!-- Card Body -->
   <div class="card-body">
       <div class="chart-pie pt-4 pb-2">
           <canvas id="myPieChart"></canvas>
       </div>
       <div class="mt-4 text-center small">
           <span class="mr-2">
               <i class="fas fa-circle text-primary"></i> Direct
           </span>
           <span class="mr-2">
               <i class="fas fa-circle text-success"></i> Social
           </span>
           <span class="mr-2">
               <i class="fas fa-circle text-info"></i> Referral
           </span>
       </div>
   </div>
                        </div>
                    </div>
                </div>
                <!-- Content Row -->
                <div class="row">
                    <!-- Content Column -->
                    <div class="col-lg-6 mb-4">
                        <!-- Project Card Example -->
                        <div class="card shadow mb-4">
   <div class="card-header py-3">
       <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
   </div>
   <div class="card-body">
       <h4 class="small font-weight-bold">Server Migration <span
               class="float-right">20%</span></h4>
       <div class="progress mb-4">
           <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
               aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
       </div>
       <h4 class="small font-weight-bold">Sales Tracking <span
               class="float-right">40%</span></h4>
       <div class="progress mb-4">
           <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
               aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
       </div>
       <h4 class="small font-weight-bold">Customer Database <span
               class="float-right">60%</span></h4>
       <div class="progress mb-4">
           <div class="progress-bar" role="progressbar" style="width: 60%"
               aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
       </div>
       <h4 class="small font-weight-bold">Payout Details <span
               class="float-right">80%</span></h4>
       <div class="progress mb-4">
           <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
               aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
       </div>
       <h4 class="small font-weight-bold">Account Setup <span
               class="float-right">Complete!</span></h4>
       <div class="progress">
           <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
               aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
       </div>
   </div>
</div>
 
<div class="card flex-fill shadow mb-4">
   <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
       
       <h5 class="mb-2">Todo</h5>
       <div class="d-flex align-items-center">
           <div class="dropdown mb-2 me-2">
               <a class="btn btn-white border btn-sm d-inline-flex align-items-center"
                   data-bs-toggle="dropdown" href="/react/template/index"
                   data-discover="true">
                   <i class="ti ti-calendar me-1"></i>Today</a>
               <ul class="dropdown-menu  dropdown-menu-end p-3">
                   <li><a class="dropdown-item rounded-1" href="/react/template/index" data-discover="true">This Month</a></li>
                   <li><a class="dropdown-item rounded-1" href="/react/template/index" data-discover="true">This Week</a></li>
                   <li><a class="dropdown-item rounded-1" href="/react/template/index" data-discover="true">Today</a></li>
               </ul>
           </div>
           <a class="btn btn-primary btn-icon btn-xs rounded-circle d-flex align-items-center justify-content-center p-0 mb-2"
               data-bs-toggle="modal" data-inert="true" data-bs-target="#add_todo"
               href="/react/template/index" data-discover="true">
               <i class="ti ti-plus fs-16"></i></a>
       </div>
   </div>
   <div class="card-body">
       <div class="d-flex align-items-center todo-item border p-2 br-5 mb-2 ">
           <i class="ti ti-grid-dots me-2"></i>
           <div class="form-check">
               <input class="form-check-input" id="todo1" type="checkbox">
               <label class="form-check-label fw-medium" for="todo1">Add Holidays</label>
           </div>
       </div>
       <div class="d-flex align-items-center todo-item border p-2 br-5 mb-2 ">
           <i class="ti ti-grid-dots me-2"></i>
           <div class="form-check">
               <input class="form-check-input" id="todo2" type="checkbox">
               <label class="form-check-label fw-medium" for="todo2">Add Meeting to
                   Client</label>
           </div>
       </div>
       <div class="d-flex align-items-center todo-item border p-2 br-5 mb-2 ">
           <i class="ti ti-grid-dots me-2"></i>
           <div class="form-check">
               <input class="form-check-input" id="todo3" type="checkbox">
               <label class="form-check-label fw-medium" for="todo3">Chat with
                   Adrian</label>
           </div>
       </div>
       <div class="d-flex align-items-center todo-item border p-2 br-5 mb-2 ">
           <i class="ti ti-grid-dots me-2"></i>
           <div class="form-check">
               <input class="form-check-input" id="todo4" type="checkbox">
               <label class="form-check-label fw-medium" for="todo4">Management
                   Call</label>
           </div>
       </div>
       <div class="d-flex align-items-center todo-item border p-2 br-5 mb-2 ">
           <i class="ti ti-grid-dots me-2"></i>
           <div class="form-check">
               <input class="form-check-input" id="todo5" type="checkbox">
               <label class="form-check-label fw-medium" for="todo5">Add Payroll</label>
           </div>
       </div>
       <div class="d-flex align-items-center todo-item border p-2 br-5 mb-2 ">
           <i class="ti ti-grid-dots me-2"></i>
           <div class="form-check">
               <input class="form-check-input" id="todo6" type="checkbox">
               <label class="form-check-label fw-medium" for="todo6">
                   Add Policy for Increment </label>
           </div>
       </div>
   </div>
</div>
</div>
    <div class="col-lg-6 mb-4">
                        <!-- Illustrations -->
    <div class="card shadow mb-4">
   <div class="card-header py-3">
       <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
   </div>
   <div class="card-body">
       <div class="text-center">
           <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
               src="img/undraw_posting_photo.svg" alt="...">
       </div>
       <p>Add some quality, svg illustrations to your project courtesy of <a
               target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
           constantly updated collection of beautiful svg images that you can use
           completely free and without attribution!</p>
       <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
           unDraw &rarr;</a>
   </div>
</div>
                    
        <!-- Approach -->
<div class="card shadow mb-4">
   <div class="card-header py-3">
       <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
   </div>
   <div class="card-body">
       <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
           CSS bloat and poor page performance. Custom CSS classes are used to create
           custom components and custom utility classes.</p>
       <p class="mb-0">Before working with this theme, you should become familiar with the
           Bootstrap framework, especially the utility classes.</p>
   </div>
  </div>
</div>
                    
   <!-- project -->
               
<div class="shadow col-xxl-8 col-xl-12 d-flex">
<div class="card flex-fill">
  <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
       <h5 class="mb-2">Projects</h5>
       <div class="d-flex align-items-center">
           <div class="dropdown mb-2"><a
                   class="btn btn-white border btn-sm d-inline-flex align-items-center"
                   data-bs-toggle="dropdown" href="/react/template/index"
                   data-discover="true"><i class="ti ti-calendar me-1"></i>ThisWeek</a>
               
           <ul class="dropdown-menu  dropdown-menu-end p-3">
               <li><a class="dropdown-item rounded-1" href="/react/template/index"
                       data-discover="true">This Month</a></li>
               <li><a class="dropdown-item rounded-1" href="/react/template/index"
                       data-discover="true">This Week</a></li>
               <li><a class="dropdown-item rounded-1" href="/react/template/index"
                       data-discover="true">Today</a></li>
           </ul>
       </div>
   </div>
</div>
   <!-- table project -->
 <div class="card-body p-0">
   <div class="table-responsive">
       <table class="table table-nowrap mb-0">
<thead>
   <tr>
       <th>ID</th>
       <th>Name</th>
       <th>Team</th>
       <th>Hours</th>
       <th>Deadline</th>
       <th>Priority</th>
   </tr>
  </thead>
 <tbody>
<tr>
   <td><a class="link-default"
           href="/react/template/index/project-details.html"
           data-discover="true">PRO-001</a></td>
   <td>
       <h6 class="fw-medium"><a
               href="/react/template/index/project-details.html"
               data-discover="true">Office Management App</a></h6>
   </td>
   <td>
       <div class="avatar-list-stacked avatar-group-sm"><span
            class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-02.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-03.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-05.jpg"></span>
       </div>
   </td>
   <td>
       <p class="mb-1">15/255 Hrs</p>
       <div class="progress progress-xs w-100" role="progressbar"
           aria-valuenow="40" aria-valuemin="0"
           aria-valuemax="100">
           <div class="progress-bar bg-primary"
               style="width: 40%;"></div>
       </div>
   </td>
   <td>12/09/2024</td>
   <td><span
           class="badge badge-danger d-inline-flex align-items-center badge-xs"><i
               class="ti ti-point-filled me-1"></i>High</span></td>
                        </tr>
                        <tr>
   <td><a class="link-default"
           href="/react/template/index/project-details.html"
           data-discover="true">PRO-002</a></td>
   <td>
       <h6 class="fw-medium"><a
               href="/react/template/index/project-details.html"
               data-discover="true">Clinic Management </a></h6>
   </td>
   <td>
       <div class="avatar-list-stacked avatar-group-sm"><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-06.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-07.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-08.jpg"></span><a
               class="avatar bg-primary avatar-rounded text-fixed-white fs-10 fw-medium"
               href="/react/template/index"
               data-discover="true">+1</a></div>
   </td>
   <td>
       <p class="mb-1">15/255 Hrs</p>
       <div class="progress progress-xs w-100" role="progressbar"
           aria-valuenow="40" aria-valuemin="0"
           aria-valuemax="100">
           <div class="progress-bar bg-primary"
               style="width: 40%;"></div>
       </div>
   </td>
   <td>24/10/2024</td>
   <td><span
           class="badge badge-success d-inline-flex align-items-center badge-xs"><i
               class="ti ti-point-filled me-1"></i>Low</span></td>
                        </tr>
                        <tr>
   <td><a class="link-default"
           href="/react/template/index/project-details.html"
           data-discover="true">PRO-003</a></td>
   <td>
       <h6 class="fw-medium"><a
               href="/react/template/index/project-details.html"
               data-discover="true">Educational Platform</a></h6>
   </td>
   <td>
       <div class="avatar-list-stacked avatar-group-sm"><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-06.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-08.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-09.jpg"></span>
       </div>
   </td>
   <td>
       <p class="mb-1">40/255 Hrs</p>
       <div class="progress progress-xs w-100" role="progressbar"
           aria-valuenow="50" aria-valuemin="0"
           aria-valuemax="100">
           <div class="progress-bar bg-primary"
               style="width: 50%;"></div>
       </div>
   </td>
   <td>18/02/2024</td>
   <td><span
           class="badge badge-pink d-inline-flex align-items-center badge-xs"><i
               class="ti ti-point-filled me-1"></i>Medium</span>
   </td>
                        </tr>
                        <tr>
   <td><a class="link-default"
           href="/react/template/index/project-details.html"
           data-discover="true">PRO-004</a></td>
   <td>
       <h6 class="fw-medium"><a
               href="/react/template/index/project-details.html"
               data-discover="true">Chat &amp; Call Mobile App</a>
       </h6>
   </td>
   <td>
       <div class="avatar-list-stacked avatar-group-sm"><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-11.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-12.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-13.jpg"></span>
       </div>
   </td>
   <td>
       <p class="mb-1">35/155 Hrs</p>
       <div class="progress progress-xs w-100" role="progressbar"
           aria-valuenow="50" aria-valuemin="0"
           aria-valuemax="100">
           <div class="progress-bar bg-primary"
               style="width: 50%;"></div>
       </div>
   </td>
   <td>19/02/2024</td>
   <td><span
           class="badge badge-danger d-inline-flex align-items-center badge-xs"><i
               class="ti ti-point-filled me-1"></i>High</span></td>
                        </tr>
                        <tr>
   <td><a class="link-default"
           href="/react/template/index/project-details.html"
           data-discover="true">PRO-005</a></td>
   <td>
       <h6 class="fw-medium"><a
               href="/react/template/index/project-details.html"
               data-discover="true">Travel Planning Website</a>
       </h6>
   </td>
   <td>
       <div class="avatar-list-stacked avatar-group-sm"><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-17.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-18.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-19.jpg"></span>
       </div>
   </td>
   <td>
       <p class="mb-1">50/235 Hrs</p>
       <div class="progress progress-xs w-100" role="progressbar"
           aria-valuenow="50" aria-valuemin="0"
           aria-valuemax="100">
           <div class="progress-bar bg-primary"
               style="width: 50%;"></div>
       </div>
   </td>
   <td>18/02/2024</td>
   <td><span
           class="badge badge-pink d-inline-flex align-items-center badge-xs"><i
               class="ti ti-point-filled me-1"></i>Medium</span>
   </td>
                        </tr>
                        <tr>
   <td><a class="link-default"
           href="/react/template/index/project-details.html"
           data-discover="true">PRO-006</a></td>
   <td>
       <h6 class="fw-medium"><a
               href="/react/template/index/project-details.html"
               data-discover="true">Service Booking Software</a>
       </h6>
   </td>
   <td>
       <div class="avatar-list-stacked avatar-group-sm"><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-06.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-08.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-09.jpg"></span>
       </div>
   </td>
   <td>
       <p class="mb-1">40/255 Hrs</p>
       <div class="progress progress-xs w-100" role="progressbar"
           aria-valuenow="50" aria-valuemin="0"
           aria-valuemax="100">
           <div class="progress-bar bg-primary"
               style="width: 50%;"></div>
       </div>
   </td>
   <td>20/02/2024</td>
   <td><span
           class="badge badge-success d-inline-flex align-items-center badge-xs"><i
               class="ti ti-point-filled me-1"></i>Low</span></td>
                        </tr>
                        <tr>
   <td class="border-0"><a class="link-default"
           href="/react/template/index/project-details.html"
           data-discover="true">PRO-008</a></td>
   <td class="border-0">
       <h6 class="fw-medium"><a
               href="/react/template/index/project-details.html"
               data-discover="true">Travel Planning Website</a>
       </h6>
   </td>
   <td class="border-0">
       <div class="avatar-list-stacked avatar-group-sm"><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-15.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-16.jpg"></span><span
               class="avatar avatar-rounded"><img
                   class="border border-white" alt="img"
                   src="/react/template/assets/img/profiles/avatar-17.jpg"></span><a
               class="avatar bg-primary avatar-rounded text-fixed-white fs-10 fw-medium"
               href="/react/template/index"
               data-discover="true">+2</a></div>
   </td>
   <td class="border-0">
       <p class="mb-1">15/255 Hrs</p>
       <div class="progress progress-xs w-100" role="progressbar"
           aria-valuenow="45" aria-valuemin="0"
           aria-valuemax="100">
           <div class="progress-bar bg-primary"
               style="width: 45%;"></div>
       </div>
   </td>
   <td class="border-0">17/10/2024</td>
   <td class="border-0"><span
           class="badge badge-pink d-inline-flex align-items-center badge-xs"><i
               class="ti ti-point-filled me-1"></i>Medium</span>
   </td>
                        </tr>
           </tbody>
       </table>
   </div>
                        </div>
                    </div>
                
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php require_once "components/footer.php" ?>
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
                    <a class="btn btn-primary" href="pages/login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>