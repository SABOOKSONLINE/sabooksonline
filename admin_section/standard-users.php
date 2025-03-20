<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Ansonika">
    <title>Edit Post | SA Books Online</title>
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
        <li class="breadcrumb-item">Subscribers</li>
        <li class="breadcrumb-item active">Standard Users</li>
      </ol>
		<!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Standard Users</div>
        <div class="card-body">
          <div class="table-responsive">
          <?php if(isset($_GET['status'])){
                          if($_GET['status'] == "success"){
                            echo "<p class='text-success'>Your <b>User</b> has been successfully <b>Declined & Deleted</b>!</p>";
                            } else if($_GET['status'] == "failed") {
                                echo "<p class='text-warning'>Your <b>User</b> could not be <b>Deleted</b>!</p>";
                            }
                            }
                            
                        ?>
                        
                        <?php if(isset($_GET['status1'])){
                            if($_GET['status1'] == "success"){
                            echo "<p class='text-success'>The <b>Account</b> has been successfully <b>Approved</b>!</p>";
                            } else if($_GET['status1'] == "failed") {
                                echo "<p class='text-warning'>The <b>Account</b> could not be <b>Approved</b>!</p>";
                            }
                            }
                            
                            ?>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Email</th>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Operations</th>
                </tr>
              </thead>
              <!--<tfoot>
                <tr>
                  <th>ID</th>
                  <th>Account Type</th>
                  <th>User</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Edit</th>
                </tr>
              </tfoot>-->
              <tbody>
              <?php
                                  
                                  //DATABASE CONNECTIONS SCRIPT
                                  include 'includes/database_connections/sabooks.php';
                                                                  $sql = "SELECT * FROM users_standard ORDER BY ID DESC;";
                                                                  $result = mysqli_query($conn, $sql);
                                                                      while($row = mysqli_fetch_assoc($result)) {
                                                                          
                                                                    echo '  <tr>
                                                                          <td>'.$row['ID'].'</td>
                                                                          <td>'.$row['ADMIN_EMAIL'].'</td>
                                                                          <td>'.$row['ADMIN_NAME'].'</td>
                                                                          <td>'.$row['ADMIN_DATE'].'</td>
                                                                          <td><i class="'.$row['ADMIN_USER_STATUS'].'">'.$row['ADMIN_USER_STATUS'].'</i></td>
                                                                          <td><a href="#0"  data-toggle="modal" data-target="#approve-'.$row['ADMIN_USERKEY'].'"><strong>Approve</strong></a> | <a href="#0"  data-toggle="modal" data-target="#decline-'.$row['ADMIN_USERKEY'].'"><strong>Remove Account</strong></a></td>
                                                                        </tr> 
                                                                        
                                                                        
                                                                        <!-- Edit Booking Modal -->
                                                                        <div class="modal fade" id="decline-'.$row['ADMIN_USERKEY'].'" tabindex="-1" role="dialog" aria-labelledby="edit_bookingLabel" aria-hidden="true">
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
                                                                                        <a class="btn btn-danger" href="includes/backend/delete/pending-user.php?contentid='.$row['ADMIN_USERKEY'].'&page=pending-users">Confirm</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                     </div>
                                                                     
                                                                     
                             <!-- Approve modal -->
                             <div class="modal fade" id="approve-'.$row['ADMIN_USERKEY'].'" tabindex="-1" role="dialog" aria-labelledby="edit_bookingLabel" aria-hidden="true">
                                 <div class="modal-dialog" role="document">
                                     <div class="modal-content">
                                         <div class="modal-header">
                                             <h5 class="modal-title" id="edit_bookingLabel">Confirm Approval</h5>
                                             <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                 <span aria-hidden="true">×</span>
                                             </button>
                                         </div>
                                         <div class="modal-body">
                                           
                                            <h6>Are you sure you want to Approve this Application?</h6>
                                             <!-- /Row -->
                                         </div>
                                         <div class="modal-footer">
                                             <a class="btn btn-success" href="includes/backend/approve/approve-user.php?contentid='.$row['ADMIN_USERKEY'].'&page=pending-users">Confirm</a>
                                         </div>
                                     </div>
                                 </div>
                          </div>';
                                                                      
                                                                      }
                                                                  ?>
                                                 
                                                
               
                <!--<tr>
                  <td>13</td>
                  <td>Da Alfredo</td>
                  <td>Jhon Doe</td>
                  <td>24/05/2020</td>
                  <td><i class="cancel">Cancelled</i></td>
                  <td><a href="edit-order.html"><strong>Edit</strong></a> | <a href="#0"><strong>Delete</strong></a></td>
                </tr>
                 <tr>
                  <td>14</td>
                  <td>Taxo Mex</td>
                  <td>Valeria Felice</td>
                  <td>24/05/2020</td>
                  <td><i class="approved">Processed</i></td>
                  <td><a href="edit-order.html"><strong>Edit</strong></a> | <a href="#0"><strong>Delete</strong></a></td>
                </tr>-->
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
      </div>
	  <!-- /tables-->
	  </div>
	  <!-- /container-fluid-->
   	</div>


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