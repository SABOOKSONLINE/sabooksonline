<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Ansonika">
    <title>Pending Listing | SA Books Online</title>
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
    <?php  include'includes/header.php'?>
    <!-- /Navigation-->
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Inactive Events</li>
            </ol>
            <div class="box_general">
             <h5>Inactive Events</h5>
                <div class="list_general">
                    <div class="pb-3 col-6"><?php if(isset($_GET['status'])){
                          if($_GET['status'] == "success"){
                            echo "<p class='text-success'>Your listing has been successfully deleted!</p>";
                    } else if($_GET['status'] == "failed") {
                        echo "<p class='text-warning'>Your listing could not be deleted!</p>";
                    }
                    }
                    
                  ?>
                  
                  <?php if(isset($_GET['status1'])){
                    if($_GET['status1'] == "success"){
                      echo "<p class='text-success'>Your listing has been successfully Activated!</p>";
                    } else if($_GET['status1'] == "failed") {
                        echo "<p class='text-warning'>Your listing could not be Activated!</p>";
                    }
                    }
                    
                    ?>
                    </div>
                    <ul>

                    <?php
                                  
        //DATABASE CONNECTIONS SCRIPT
        include 'includes/database_connections/sabooks.php';
                                        $sql = "SELECT * FROM events WHERE CURRENT = 'Pending' ORDER BY ID DESC;";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {
                                                
                                                echo ' <li>
                                                <figure><img src="https://my.sabooksonline.co.za/events/'.$row['CONTENTID'].'/'.$row['COVER'].'" alt=""></figure>
                                                <small>'.$row['EVENTDATE'].' - <b>'.$row['EVENTTIME'].'</b></small>
                                                <a href="https://my.sabooksonline.co.za/events/'.$row['CONTENTID'].'/"><h4>'.$row['TITLE'].'</h4></a>
                                                <p>'.substr(strip_tags($row['DESCRIPTION']), '0', '200').'....</p>
                                                <p><a href="includes/backend/activate/event.php?contentid='.$row['CONTENTID'].'" class="btn_1 gray"><i class="fa fa-fw fa-eye"></i> Activate item </a>   <a href="edit-event.php?contentid='.$row['CONTENTID'].'" class="btn_1 gray"><i class="fa fa-fw fa-edit"></i> Edit listing</a></p>
                                                <ul class="buttons">
                                                    <li><a href="#0" class="btn_1 gray" data-toggle="modal" data-target="#'.$row['CONTENTID'].'"><i class="fa fa-fw fa-times-circle-o"></i> Delete</a></li>
                                                </ul>
                                            </li>
                                            
                                            <!-- Edit Booking Modal -->
                                            <div class="modal fade" id="'.$row['CONTENTID'].'" tabindex="-1" role="dialog" aria-labelledby="edit_bookingLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="edit_bookingLabel">Confirm Removal</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                          
                                                           <h6>Are you sure you want to delete this?</h6>
                                                            <!-- /Row -->
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a class="btn btn-danger" href="includes/backend/delete/event.php?contentid='.$row['CONTENTID'].'&page=event-pending">Confirm</a>
                                                        </div>
                                                    </div>
                                                </div>
                                         </div>';
                                            
                                            }
                                        ?>
                       
                      
                    </ul>
                </div>
            </div>
            <!-- /box_general-->
          
            <!-- /pagination-->
        </div>
        <!-- /container-fluid-->
    </div>

  
    <!-- /container-wrapper-->
   <?php include 'includes/footer.php';?>
    
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