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
                <li class="breadcrumb-item active">Add Advertisement</li>
            </ol>
            <form id="ads">
                <div class="box_general padding_bottom">
                    <div class="header_box version_2">
                        <h2><i class="fa fa-file"></i>Advertisement information</h2>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Advertisement Link</label>
                                <input type="text" class="form-control" placeholder="Kelloggs Cornflakes" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Advertisement placement</label>
                                <div class="styled-select">
                                    <select name="category">
                                    <option value="stationery">Stationery</option>
                                    <option value="groceries">Groceries</option>
                                    <option value="hygiene">Hygiene</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- /row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Cover image</label>
                                <input type="file" class="" name="eng_cover" required>
                            </div>
                        </div>
                    </div>
                    <!-- /row-->
                </div>
                
            
                <!-- /box_general-->
                <div class="box_general padding_bottom">
                    
                    <div class="wrapper_indent">
                    
                        <!-- /box_general-->
                        <div class="form-group" id="results">
                                                
                                                </div>
                    </div>
                    <!-- /.container-fluid-->
                </div>
                <!-- /.container-wrapper-->
                <p><button type="submit" name="submit" class="btn_1 medium">Publish Listing</button></p>

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