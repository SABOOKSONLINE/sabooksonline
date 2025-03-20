<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Ansonika">
    <title>Booksing | SA Books Online</title>
    <!-- Favicons-->
    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Main styles -->
    <link href="css/admin.css" rel="stylesheet">
    <!-- Icon fonts-->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Plugin styles -->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Your custom styles -->
    <link href="css/custom.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer" id="page-top">
    <!-- Navigation-->
    <?php include 'includes/header.php'?>
    <!-- /Navigation-->
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Bookings list</li>
            </ol>
            <div class="box_general">
                <div class="header_box">
                    <h2 class="d-inline-block">Bookings list</h2>
                    <div class="filter">
                    	<div class="styled-select short">
	                        <select name="orderby">
	                            <option value="Any status">Any status</option>
	                            <option value="Approved">Approved</option>
	                            <option value="Pending">Pending</option>
	                            <option value="Cancelled">Cancelled</option>
	                        </select>
	                    </div>
                    </div>
                </div>
                <div class="list_general">
                    <ul>
                        <li>
                            <figure><img src="img/item_1.jpg" alt=""></figure>
                            <h4>La Monnalisa <i class="pending">Pending</i></h4>
                            <ul class="booking_list">
                                <li><strong>Booking date</strong> 5 November 2019</li>
                                <li><strong>Booking details</strong> 2 People</li>
                                <li><strong>Client</strong> Mark Twain</li>
                            </ul>
                            <p><a href="#0" class="btn_1 gray"><i class="fa fa-fw fa-envelope"></i> Send Message</a></p>
                            <ul class="buttons">
                                <li><a href="#0" class="btn_1 gray approve"><i class="fa fa-fw fa-check-circle-o"></i> Approve</a></li>
                                <li><a href="#0" class="btn_1 gray delete" data-toggle="modal" data-target="#edit_booking"><i class="fa fa-fw fa-pencil"></i> Edit</a></li>
                                <li><a href="#0" class="btn_1 gray delete"><i class="fa fa-fw fa-times-circle-o"></i> Cancel</a></li>
                            </ul>
                        </li>
                        <li>
                            <figure><img src="img/item_2.jpg" alt=""></figure>
                            <h4>Da Alfredo <i class="cancel">Cancelled</i></h4>
                            <ul class="booking_list">
                                <li><strong>Booking date</strong> 11 November 2019</li>
                                <li><strong>Booking details</strong> 2 People</li>
                                <li><strong>Client</strong> Mark Twain</li>
                            </ul>
                            <p><a href="#0" class="btn_1 gray"><i class="fa fa-fw fa-envelope"></i> Send Message</a></p>
                            <ul class="buttons">
                                <li><a href="#0" class="btn_1 gray approve"><i class="fa fa-fw fa-check-circle-o"></i> Approve</a></li>
                                <li><a href="#0" class="btn_1 gray delete" data-toggle="modal" data-target="#edit_booking"><i class="fa fa-fw fa-pencil"></i> Edit</a></li>
                                <li><a href="#0" class="btn_1 gray delete"><i class="fa fa-fw fa-times-circle-o"></i> Cancel</a></li>
                            </ul>
                        </li>
                        <li>
                            <figure><img src="img/item_3.jpg" alt=""></figure>
                            <h4>Sushi Gold <i class="approved">Approved</i></h4>
                            <ul class="booking_list">
                                <li><strong>Booking date</strong> 21 November 2019</li>
                                <li><strong>Booking details</strong> 2 People</li>
                                <li><strong>Client</strong> Mark Twain</li>
                            </ul>
                            <p><a href="#0" class="btn_1 gray"><i class="fa fa-fw fa-envelope"></i> Send Message</a></p>
                            <ul class="buttons">
                                <li><a href="#0" class="btn_1 gray approve"><i class="fa fa-fw fa-check-circle-o"></i> Approve</a></li>
                                <li><a href="#0" class="btn_1 gray delete" data-toggle="modal" data-target="#edit_booking"><i class="fa fa-fw fa-pencil"></i> Edit</a></li>
                                <li><a href="#0" class="btn_1 gray delete"><i class="fa fa-fw fa-times-circle-o"></i> Cancel</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /box_general-->
            <nav aria-label="...">
                <ul class="pagination pagination-sm add_bottom_30">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
            <!-- /pagination-->
        </div>
        <!-- /container-fluid-->
    </div>
    <!-- /container-wrapper-->
    <footer class="sticky-footer">
        <div class="container">
            <div class="text-center">
                <small>Copyright © FOOGRA 2021</small>
            </div>
        </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>

     <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <a class="btn btn-primary" href="#0">Logout</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Booking Modal -->
    <div class="modal fade" id="edit_booking" tabindex="-1" role="dialog" aria-labelledby="edit_bookingLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_bookingLabel">Edit Booking</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="form-group">
                        <label>Restaurant Name</label>
                        <input type="text" class="form-control" value="Restaurant Name">
                    </div>
                    <div class="form-group">
                        <label>Client Name</label>
                        <input type="text" class="form-control" value="Mark Twain">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control" value="5 November 2020">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Time</label>
                                <input type="text" class="form-control" value="08.30pm">
                            </div>
                        </div>
                    </div>
                    <!-- /Row -->
                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Peolple</label>
                                <input type="text" class="form-control" value="2">
                            </div>
                        </div>
                    </div>
                    <!-- /Row -->
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" href="login.html">Save</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="vendor/jquery.magnific-popup.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/admin.js"></script>

</body>

</html>