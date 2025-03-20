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
                <li class="breadcrumb-item active">Products </li>
                <li class="breadcrumb-item active">Edit </li>
                <li class="breadcrumb-item active"><?php
                                        include 'includes/dbh.inc.php';
                                        $contentid = $_GET['contentid'];
                                        $sql = "SELECT * FROM products WHERE PRODUCTKEY = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo $row['TITLE'];}
                                        ?></li>
            </ol>
            <form id="edit">
                <div class="box_general padding_bottom">
                    <div class="header_box version_2">
                        <h2><i class="fa fa-file"></i>Listing information for - <b class="text-success">  <?php
                                        include 'includes/dbh.inc.php';
                                        $contentid = $_GET['contentid'];
                                        $sql = "SELECT * FROM products WHERE PRODUCTKEY = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo $row['TITLE'];}
                                        ?></b></h2>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Title</label>
                                <input type="text" class="form-control" placeholder="Kelloggs Cornflakes" name="title" value="  <?php
                                        //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/dbh.inc.php';
                                        $contentid = $_GET['contentid'];
                                        $sql = "SELECT * FROM products WHERE PRODUCTKEY = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo $row['TITLE']; }
                                        ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Keywords</label>
                                <input type="text" class="form-control" placeholder="Keywords should be separated by commas" value="  <?php
                                        //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/dbh.inc.php';
                                        $contentid = $_GET['contentid'];
                                        $sql = "SELECT * FROM products WHERE PRODUCTKEY = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo $row['KEYWORDS']; }
                                        ?>"  name="keywords" required>
                            </div>
                        </div>
                      
                    </div>
                    <!-- /row-->
                    <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label>Main Category</label>
                                <div class="styled-select">
                                    <select name="category">
                                    <option value="stationery">Stationery</option>
                                            <option value="Office Equipment">Office Equipment</option>
                                            <option value="Packaging">Packaging</option>
                                            <option value="Text Books">Text Books</option>

                                            <option value="groceries">Groceries</option>
                                            <option value="dry goods">Dry Goods</option>
                                            <option value="dairy products">Dairy Products</option>
                                            <option value="custom hampers">Custom Hampers</option>

                                            <option value="hygiene">Hygiene</option>
                                            <option value="toiletries">Toiletries</option>
                                            <option value="cleaning supplies">Cleaning Supplies</option>
                                            <option value="sanitary">Sanitary</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sub Category</label>
                                <div class="styled-select">
                                    <select name="sub">
                                    <?php
                                        //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/dbh.inc.php';
                                                                                        
                                        $sql = "SELECT * FROM office_equipment;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
                                                }
                                            }
     
                                     
                                                                                        
                                        $sql = "SELECT * FROM packaging;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
                                                }
                                            }
                        
                                        $sql = "SELECT * FROM text_books;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
                                                }
                                            }
                                      

                                                                                        
                                        $sql = "SELECT * FROM groceries;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
                                                }
                                            }
                                      

                                                                                        
                                        $sql = "SELECT * FROM dry_goods;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
                                                }
                                            }
                                      

                                                                                        
                                        $sql = "SELECT * FROM dairy_products;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
                                                }
                                            }
                                      

                                                                                        
                                        $sql = "SELECT * FROM custom_hampers;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
                                                }
                                            }
                                      

                                                                                        
                                        $sql = "SELECT * FROM hygiene;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
                                                }
                                            }
                                      

                                                                                        
                                        $sql = "SELECT * FROM toiletries;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
                                                }
                                            }
                                      

                    
                                        $sql = "SELECT * FROM cleaning_supplies;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
                                                }
                                            }
             
                                                                                        
                                        $sql = "SELECT * FROM sanitary;";
                                        $result = mysqli_query($conn, $sql);


                                            if(mysqli_num_rows($result)){
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    echo '<option value="'.$row['SUBCATEGORY'].'">'.$row['SUBCATEGORY'].'</option>';
    
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
                                <textarea class="editor" name="desc" required><?php include 'includes/dbh.inc.php';$contentid = $_GET['contentid'];include "../products/".$contentid."/includes/overview.php";?></textarea>
                            </div>
                        </div>
                    </div>
                
                    
                    <!-- /row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Cover image</label>
                                <input type="file" class="" name="eng_cover">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" class="" value="<?php echo $_GET['contentid']; ?>" name="contentid">
                            </div>
                        </div>
                    </div>
                    <!-- /row-->
                </div>

                   <!-- /box_general-->
                   <div class="box_general padding_bottom">
                    <div class="header_box version_2">
                        <h2><i class="fa fa-map-marker"></i>Additional Items</h2>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Select your brand</label>
                                <div class="styled-select">
                                    <select name="brand">
                                    <?php
                                        include 'includes/dbh.inc.php';
                                        $contentid = $_GET['contentid'];
                                        $sql = "SELECT * FROM products WHERE PRODUCTKEY = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo '<option value="'.$row['BRAND'].'">'.$row['BRAND'].'</option>';   }
                                        ?>
                                 

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
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>SKU Number</label>
                                <input type="text" class="form-control" placeholder="ex. KER3214567" value="<?php
                                        //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/dbh.inc.php';
                                        $contentid = $_GET['contentid'];
                                        $sql = "SELECT * FROM products WHERE PRODUCTKEY = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo $row['SKU']; }
                                        ?>" name="sku" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total number of products</label>
                                <input type="text" class="form-control" placeholder="ex. 2000" value="<?php
                                        //DATABASE CONNECTIONS SCRIPT
                                        include 'includes/dbh.inc.php';
                                        $contentid = $_GET['contentid'];
                                        $sql = "SELECT * FROM products WHERE PRODUCTKEY = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo $row['QUANTITY']; }
                                        ?>" name="products" required>
                            </div>
                        </div>
                    </div>
                
                </div>
                <!-- /box_general-->
            
                <!-- /box_general-->
                <div class="box_general padding_bottom">
                    <div class="header_box version_2">
                        <h2><i class="fa fa-list"></i>Add item to Menu List</h2>
                    </div>
                    <div class="wrapper_indent">
                    
                        <div class="menu-item-section clearfix">
                            <h4>Item Prices</h4>
                            <div><a href="#0"><i class="fa fa-plus-circle"></i></a><a href="#0"><i class="fa fa-minus-circle"></i></a>
                            </div>
                        </div>
                        <div class="strip_menu_items">
                            <div class="row">
                                
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Current/New Price</label>
                                                <input type="text" class="form-control" name="price_current" value="  <?php
                                        include 'includes/dbh.inc.php';
                                        $sql = "SELECT * FROM products WHERE PRODUCTKEY = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo $row['PRICE'];}
                                        ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Was/Old Price</label>
                                                <input type="text" class="form-control" name="price_old" value="  <?php
                                        include 'includes/dbh.inc.php';
                                        $sql = "SELECT * FROM products WHERE PRODUCTKEY = '$contentid';";
                                        $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {echo $row['OLDPRICE'];}
                                        ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- End row -->
                            </div><!-- End strip_menu_items -->
                        </div>
                        <!-- /box_general-->
                        <div class="form-group" id="results">
                                                
                                                </div>
                    </div>
                    <!-- /.container-fluid-->
                </div>
                <!-- /.container-wrapper-->
                <p><button type="submit" name="submit" class="btn_1 medium">Update Listing</button></p>

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