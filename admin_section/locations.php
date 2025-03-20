<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Ansonika">
    <title>Add Locations | SA Books Online</title>
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
    <link href="vendor/dropzone.css" rel="stylesheet">
    <link href="css/date_picker.css" rel="stylesheet">
    <!-- Your custom styles -->
    <link href="css/custom.css" rel="stylesheet">
    <!-- WYSIWYG Editor -->
    <link rel="stylesheet" href="js/editor/summernote-bs4.css">
</head>

<body class="fixed-nav sticky-footer" id="page-top">
   <?php include 'includes/header.php';?>
    <!-- /Navigation-->
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Add Brands</li>
            </ol>

           
            <!-- /box_general-->
            <form id="location">
                <div class="box_general padding_bottom col-md-6"">
                    <div class="header_box version_2">
                        <h2><i class="fa fa-list"></i>Add item to Brand list</h2>
                    </div>
                    <div class="wrapper_indent">
                    
                        <div class="menu-item-section clearfix">
                            <h4>You are required to add a city/province along with its country</h4>
                            <div><a href="#0"><i class="fa fa-info-circle"></i></a><a href="#0"></a>
                            </div>
                        </div>
                        <div class="strip_menu_items">
                            <div class="row">
                                
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Brand name</label>
                                                <input type="text" name="city" class="form-control" required>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="form-group" id="results_add">
                                                
                                                </div>
                                </div><!-- End row -->
                            </div><!-- End strip_menu_items -->
                        </div>
                        <!-- /box_general-->
                    </div>
                    <!-- /.container-fluid-->
                </div>
                <!-- /.container-wrapper-->
                <p><button class="btn_1 medium" type="submit" name="submit">Add location</button></p>
            </form>


                <!-- /box_general-->
                <form id="location_remove">
              <div class="box_general padding_bottom col-md-6">
                <div class="header_box version_2">
                    <h2><i class="fa fa-list"></i>Remove Brand</h2>
                </div>
                <div class="wrapper_indent">
                   
                    <div class="menu-item-section clearfix">
                        <h4>You are required to select a brand you would like to remove</h4>
                        <div><a href="#0"><i class="fa fa-info-circle"></i></a><a href="#0"></a>
                        </div>
                    </div>
                    <div class="strip_menu_items">
                        <div class="row">
                            
                            <div class="col-xl-12">
                                <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Select Province</label>
                                        <div class="styled-select">
                                            <select name="city">
                                                      
                                            <?php


                                        //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/dbh.inc.php';
                                                                                        
                                        $sql = "SELECT * FROM brand;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result) == false){

                                            }else{
                                            while($row = mysqli_fetch_assoc($result)) {

                                                echo '<option value="'.$row['BRAND'].'">'.$row['BRAND'].'</option>';

                                            }
                                        }

                                        ?>

                                            </select>
                                        </div>
                                       
                                    </div>
                                    <div class="form-group" id="results_remove">
                                                
                                                </div>
                                </div>
                                 
                                </div>
                            </div><!-- End row -->
                        </div><!-- End strip_menu_items -->
                    </div>
                    <!-- /box_general-->
                </div>
                <!-- /.container-fluid-->
                </div>
                <!-- /.container-wrapper-->
                <p><button class="btn_1 medium btn-danger" type="submit" name="name">Remove Location</button></p>

                </form>



            <footer class="sticky-footer">
                <div class="container">
                    <div class="text-center">
                        <small>Copyright © SA Books Online 2021</small>
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
            <!-- Custom scripts for this page-->
            <script src="vendor/dropzone.min.js"></script>
            <script src="vendor/bootstrap-datepicker.js"></script>
            <script>
            $('input.date-pick').datepicker();
            </script>
            <!-- WYSIWYG Editor -->
            <script src="js/editor/summernote-bs4.min.js"></script>

            
            <script src="main.js"></script>


            <script>
            $('.editor').summernote({
                fontSizes: ['10', '14'],
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']]
                ],
                placeholder: 'Write here ....',
                tabsize: 2,
                height: 200
            });
            </script>
</body>

</html>