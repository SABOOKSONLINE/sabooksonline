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
                <li class="breadcrumb-item">View Book</li>
                <li class="breadcrumb-item active"> <?php include 'includes/database_connections/sabooks.php';
                    $contentid = $_GET['contentid'];$sql = "SELECT * FROM posts WHERE CONTENTID = '$contentid';";$result = mysqli_query($conn, $sql);while($row = mysqli_fetch_assoc($result)) {echo $row['TITLE']; }
                ?></li>
                                        
            </ol>
            <div class="box_general pb-3">
                <div class="header_box">
                    <h2 class="d-inline-block">Book Listing Information</h2>
                </div>
                <div class="list_general order">
                <?php if(isset($_GET['status'])){
                          if($_GET['status'] == "success"){
                            echo "<p class='text-success'>Your listing has been successfully <b>Deleted</b>!</p>";
                            } else if($_GET['status'] == "failed") {
                                echo "<p class='text-warning'>Your listing could not be <b>Deleted</b>!</p>";
                            }
                            }
                            
                        ?>
                        
                        <?php if(isset($_GET['status1'])){
                            if($_GET['status1'] == "success"){
                            echo "<p class='text-success'>Your listing has been successfully <b>Hidden</b>!</p>";
                            } else if($_GET['status1'] == "failed") {
                                echo "<p class='text-warning'>Your listing could not be <b>Hidden</b>!</p>";
                            }
                            }
                            
                            ?>
                    <ul>
                        <li>
                           
                            <?php include 'includes/database_connections/sabooks.php';
                            
                            if(isset($_GET['contentid'])){
                                $contentid = $_GET['contentid'];
                            
                            $sql = "SELECT * FROM posts WHERE CONTENTID = '$contentid';";
                            
                            if($result = mysqli_query($conn, $sql)) {
                                
                            while($row = mysqli_fetch_assoc($result)) {
                                
                                echo '
                                <h4>'.$row['ID'].'. '.$row['TITLE'].' <i class="'.$row['STATUS'].'">'.$row['STATUS'].'</i></h4>
                                <ul class="booking_list">
								   <li><strong>Listed By</strong> '.ucfirst($row['PUBLISHER']).'</li><br>
                                <li><strong>Book Title</strong> '.$row['TITLE'].'</li>
                                <li><strong>Content ID</strong> '.$row['CONTENTID'].'</li>
                                <li><strong>Date Posted</strong> '.$row['DATEPOSTED'].'</li>
                                <li><strong>ISBN Number</strong> '.$row['ISBN'].'</li>
                                <li><strong>Category</strong> <a href="#0">'.$row['CATEGORY'].'</a></li>
                                <li><strong>Retail Price</strong> R'.$row['RETAILPRICE'].'</li>
                                <li><strong>Accout Type</strong> '.$row['TYPE'].'</li>    
                                </ul>
                                
                                <p><a href="#0" class="btn_1" data-toggle="modal" data-target="#approve-'.$row['CONTENTID'].'"><i class="fa fa-fw fa-eye"></i> Activate Book Listing</a></p>
                            <ul class="buttons">
                               <li><a href="#0" class="btn_1 gray approve" data-toggle="modal" data-target="#hide-'.$row['CONTENTID'].'"><i class="fa fa-fw fa-eye"></i> Hide Listing</a></li>
                                <li><a href="#0" class="btn_1 gray delete" data-toggle="modal" data-target="#decline-'.$row['CONTENTID'].'"><i class="fa fa-fw fa-times-circle-o"></i> Remove Book</a></li>


                                <!-- decline Booking Modal -->
                                <div class="modal fade" id="decline-'.$row['CONTENTID'].'" tabindex="-1" role="dialog" aria-labelledby="edit_bookingLabel" aria-hidden="true">
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
                                                <a class="btn btn-danger" href="includes/backend/delete/book-listing.php?contentid='.$row['CONTENTID'].'&page=book-information">Confirm</a>
                                            </div>
                                        </div>
                                    </div>
                             </div>
<img src="https://my.sabooksonline.co.za/books/'.$row['CONTENTID'].'/'.$row['COVER'].'" alt="" width="200px">
                             <!-- hide Booking Modal -->
                             <div class="modal fade" id="hide-'.$row['CONTENTID'].'" tabindex="-1" role="dialog" aria-labelledby="edit_bookingLabel" aria-hidden="true">
                                 <div class="modal-dialog" role="document">
                                     <div class="modal-content">
                                         <div class="modal-header">
                                             <h5 class="modal-title" id="edit_bookingLabel">Confirm Deactivation</h5>
                                             <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                 <span aria-hidden="true">×</span>
                                             </button>
                                         </div>
                                         <div class="modal-body">
                                           
                                            <h6>Are you sure you want to Hide this?</h6>
                                             <!-- /Row -->
                                         </div>
                                         <div class="modal-footer">
                                             <a class="btn btn-warning" href="includes/backend/hide/book-listing.php?contentid='.$row['CONTENTID'].'&page=book-information">Confirm</a>
                                         </div>
                                     </div>
                                 </div>
                          </div>

                          <!-- Approve modal -->
                          <div class="modal fade" id="approve-'.$row['CONTENTID'].'" tabindex="-1" role="dialog" aria-labelledby="edit_bookingLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="edit_bookingLabel">Confirm Activation</h5>
                                          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">×</span>
                                          </button>
                                      </div>
                                      <div class="modal-body">
                                        
                                         <h6>Are you sure you want to Approve this Application?</h6>
                                          <!-- /Row -->
                                      </div>
                                      <div class="modal-footer">
                                          <a class="btn btn-success" href="includes/backend/activate/book-listing.php?contentid='.$row['CONTENTID'].'&page=pending-users">Confirm</a>
                                      </div>
                                  </div>
                              </div>
                       </div>

                       <!-- delete modal -->
                       <div class="modal fade" id="decline-'.$row['CONTENTID'].'" tabindex="-1" role="dialog" aria-labelledby="edit_bookingLabel" aria-hidden="true">
                           <div class="modal-dialog" role="document">
                               <div class="modal-content">
                                   <div class="modal-header">
                                       <h5 class="modal-title" id="edit_bookingLabel">Confirm Activation</h5>
                                       <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                           <span aria-hidden="true">×</span>
                                       </button>
                                   </div>
                                   <div class="modal-body">
                                     
                                      <h6>Are you sure you want to Approve this Application?</h6>
                                       <!-- /Row -->
                                   </div>
                                   <div class="modal-footer">
                                       <a class="btn btn-success" href="includes/backend/delete/book-listing.php?contentid='.$row['CONTENTID'].'&page=pending-users">Confirm</a>
                                   </div>
                               </div>
                           </div>
                    </div>
                             ';
                                
                                
                                }
                            } else {

                                echo "<p class='text-warning'>They are no pending users at the moment.</p>";

                            }

                            }
                            
                            
                            ?>

                            
                            
                            </ul>
                        </li>
                    </ul>
                </div>
                
            </div>
            <!-- /box_general-->
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