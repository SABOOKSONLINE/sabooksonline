<nav class="navbar navbar-expand-lg navbar-dark bg-default fixed-top" id="mainNav">
        <a class="navbar-brand" href="index.html"><img src="https://my.sabooksonline.co.za/img/logo.png" alt="" width="60" height="36"></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                    <a class="nav-link" href="index.php">
                        <i class="fa fa-fw fa-dashboard"></i>
                        <span class="nav-link-text">Admin Dashboard</span>
                    </a>
                </li>
            
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My listings">
                    <a class="nav-link"href="subscriptions.php">
                        <i class="fa fa-fw fa-dollar"></i>
                        <span class="nav-link-text">Subscriptions</span> 
						
						 <span class="badge badge-pill badge-success"><?php include 'database_connections/sabooks.php'; $rows_query = mysqli_query($conn, "SELECT COUNT(*) as number_rows FROM invoices");$rows = mysqli_fetch_assoc($rows_query); echo $rows['number_rows'];?></span>
                    </a>
                  
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My listings">
                    <a class="nav-link"href="book-listing.php">
                        <i class="fa fa-fw fa-list"></i>
                        <span class="nav-link-text">Book Listings</span> 
						
						 <span class="badge badge-pill badge-success"><?php include 'database_connections/sabooks.php'; $rows_query = mysqli_query($conn, "SELECT COUNT(*) as number_rows FROM posts");$rows = mysqli_fetch_assoc($rows_query); echo $rows['number_rows'];?></span>
                    </a>
                  
                </li>

                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My listings">
                    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMyUsers">
                        <i class="fa fa-fw fa-calendar-check-o"></i>
                        <span class="nav-link-text">Subscribers</span>
                    </a>
                    <ul class="sidenav-second-level collapse" id="collapseMyUsers">
                        <li>
                            <a href="standard-users.php">Starndard Users <span class="badge badge-pill badge-success"><?php include 'database_connections/sabooks.php';  $rows_query = mysqli_query($conn, "SELECT COUNT(*) as number_rows FROM users_standard");$rows = mysqli_fetch_assoc($rows_query); echo $rows['number_rows'];?></span></a>
                        </li>
                        <li>
                            <a href="pending-users.php">Super Users <span class="badge badge-pill badge-warning"><?php include 'database_connections/sabooks.php'; $rows_query = mysqli_query($conn, "SELECT COUNT(*) as number_rows FROM users");$rows = mysqli_fetch_assoc($rows_query); echo $rows['number_rows'];?></span></a>
                        </li>
                       
                    </ul>
                </li>

                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My listings">
                    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMyevents">
                        <i class="fa fa-fw fa-file"></i>
                        <span class="nav-link-text">Events Listings</span>
                    </a>
                    <ul class="sidenav-second-level collapse" id="collapseMyevents">
                        <li>
                            <a href="event-listings.php">Active Events <span class="badge badge-pill badge-success"><?php include 'database_connections/sabooks.php';  $rows_query = mysqli_query($conn, "SELECT COUNT(*) as number_rows FROM events WHERE CURRENT = 'Active'");$rows = mysqli_fetch_assoc($rows_query); echo $rows['number_rows'];?></span></a>
                        </li>
                        <li>
                            <a href="event-pending.php">Pending Events <span class="badge badge-pill badge-warning"><?php include 'database_connections/sabooks.php'; $rows_query = mysqli_query($conn, "SELECT COUNT(*) as number_rows FROM events WHERE CURRENT = 'Pending'");$rows = mysqli_fetch_assoc($rows_query); echo $rows['number_rows'];?></span></a>
                        </li>
                       
                    </ul>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Reviews">
                    <a class="nav-link" href="category.php">
                        <i class="fa fa-fw fa-star"></i>
                        <span class="nav-link-text">Category Options</span>
                    </a>
                </li>

             
            
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Add listing + Menu List">
                    <a class="nav-link" href="add-event.php">
                        <i class="fa fa-fw fa-pencil"></i>
                        <span class="nav-link-text">Add Events </span>
                    </a>
                </li>
               
                
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My listings">
                    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMyPages">
                        <i class="fa fa-fw fa-list"></i>
                        <span class="nav-link-text">Inner Pages</span>
                    </a>
                    <ul class="sidenav-second-level collapse" id="collapseMyPages">
                        <li>
                            <a href="listings.php">Home Banner</a>
                        </li>
                        <li>
                            <a href="pending.php">Pending Listings</a>
                        </li>
                       
                    </ul>
                </li>
                
            </ul>
            <ul class="navbar-nav sidenav-toggler">
                <li class="nav-item">
                    <a class="nav-link text-center" id="sidenavToggler">
                        <i class="fa fa-fw fa-angle-left"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
               
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-fw fa-sign-out"></i>Logout</a>
                </li>
            </ul>
        </div>
    </nav>