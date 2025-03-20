<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Ansonika">
    <title>Add Listing | SA Books Online</title>
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
                <li class="breadcrumb-item">Edit</li>
                <li class="breadcrumb-item active"><?php
                                        //DATABASE CONNECTIONS SCRIPT
        include 'includes/database_connections/sabooks.php';
                                        $contentid = $_GET['contentid'];
                                        $sql = "SELECT * FROM posts WHERE CONTENTID = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo $row['TITLE'];}
                                        ?></li>
            </ol>
            <form id="updatebook">
                <div class="box_general padding_bottom">
                    <div class="header_box version_2">
                        <h2><i class="fa fa-file"></i>Book information For <b><?php
                                        //DATABASE CONNECTIONS SCRIPT
        include 'includes/database_connections/sabooks.php';
                                        $contentid = $_GET['contentid'];
                                        $sql = "SELECT * FROM posts WHERE CONTENTID = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo $row['TITLE'];}
                                        ?></b></h2>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Book Title</label>
                                <input type="text" class="form-control" placeholder="ie. I think i'm depressed" name="title" value="<?php  //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/database_connections/sabooks.php';$contentid = $_GET['contentid'];include "../books/".$contentid."/includes/title.php";?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Keywords</label>
                                <input type="text" class="form-control" placeholder="Keywords should be separated by commas" name="keywords" value="<?php  //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/database_connections/sabooks.php';$contentid = $_GET['contentid'];include "../books/".$contentid."/includes/keywords.php";?>" required>
                            </div>
                        </div>
                      
                    </div>
                    <!-- /row-->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Website Link</label>
                                <input type="text" class="form-control" placeholder="ie. https://" name="website" value="<?php  //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/database_connections/sabooks.php';$contentid = $_GET['contentid'];include "../books/".$contentid."/includes/website.php";?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Book Retail Price</label>
                                <input type="number" class="form-control" placeholder="ie. R200" name="price" value="<?php  //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/database_connections/sabooks.php';$contentid = $_GET['contentid'];include "../books/".$contentid."/includes/price.php";?>" required>
                            </div>
                        </div>

                        
                      
                    </div>

                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>ISBN Number</label>
                                <input type="text" class="form-control" placeholder="ie. https://" name="isbn" value="<?php  //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/database_connections/sabooks.php';$contentid = $_GET['contentid'];include "../books/".$contentid."/includes/isbn.php";?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date Published</label>
                                <input type="text" class="form-control" placeholder="ie. R200" name="date_published" value="<?php  //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/database_connections/sabooks.php';$contentid = $_GET['contentid'];include "../books/".$contentid."/includes/date-published.php";?>" required>
                            </div>
                        </div>

                                <input type="hidden" class="form-control" placeholder="ie. R200" name="contentid" value="<?php  //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/database_connections/sabooks.php';
                                        $contentid = $_GET['contentid'];
                                        echo $contentid;?>" required>
                        
                        <div class="col-md-4">
                        <div class="form-group">
                                        <label>Select A Category</label>
                                        <div class="styled-select">
                                            <select name="category">
                                            <option value="<?php  //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/database_connections/sabooks.php';$contentid = $_GET['contentid'];include "../books/".$contentid."/includes/category.php";?>"><?php  //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/database_connections/sabooks.php';$contentid = $_GET['contentid'];include "../books/".$contentid."/includes/category.php";?></option>
                                            <?php
                                                //DATABASE CONNECTIONS SCRIPT
                                                include 'database_connections/sabooks.php';
                                                $sql = "SELECT * FROM category;";
                                                $result = mysqli_query($conn, $sql);
                                                    if(mysqli_num_rows($result) == false){
                                                    }else{
                                                    while($row = mysqli_fetch_assoc($result)) {
                                                        echo '<option value="'.$row['CATEGORY'].'">'.$row['CATEGORY'].'</option>';
                                                    }
                                                }
                                            ?>
                                            </select>
                                        </div>
                                        
                                    </div>
                        </div>

                      
                    </div>
                  
                    
                    <!-- /row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="editor" name="desc" required><?php  //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/database_connections/sabooks.php';$contentid = $_GET['contentid'];include "../books/".$contentid."/includes/overview.php";?></textarea>
                            </div>
                        </div>
                    </div>
                
                    
                    <!-- /row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Book cover image</label>
                                <input type="file" class="" name="eng_cover">
                            </div>
                        </div>
                    </div>
                    <!-- /row-->
                </div>
              
            
                <div class="form-group" id="results"></div>
                                                
                                                
                <!-- /.container-wrapper-->
                <p><button type="submit" name="submit" class="btn_1 medium">Update Book Listing</button></p>

            </form>



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