<?php
        
        //DATABASE CONNECTIONS SCRIPT
        include '../../database_connections/sabooks.php';

        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $desc = mysqli_real_escape_string($conn, $_POST['desc']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $time = mysqli_real_escape_string($conn, $_POST['time']);
        $venue = mysqli_real_escape_string($conn, $_POST['venue']);
        $keywords = mysqli_real_escape_string($conn, $_POST['keywords']);


        $contentid = substr(uniqid(), '0', '6');
        $contentid = strtolower(str_replace(" ", "-", $title.$contentid));

        $sourcePath = $_FILES['eng_cover']['tmp_name'];

        $title_show = $title;
        $title = str_replace("'", "`", $title);
       
        //$date = date("l jS \of F Y h:i:s A");
        $dateposted = date("l, jS \of F Y");

        $context = strip_tags(substr($desc, '0', '50'));

        $page = '
        <!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="SA Books">
            <title><?php include "includes/title.php";?> - SA Books Online</title>
        
            <!-- Favicons-->
            <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
            <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
            <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
            <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
            <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">
        
            <!-- GOOGLE WEB FONT -->
            <link rel="dns-prefetch" href="https://fonts.gstatic.com/">
            <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="anonymous">
            <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap" as="fetch" crossorigin="anonymous">
            <script type="text/javascript">
            !function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap",r="__3perf_googleFonts_c2536";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
            </script>
        
            <!-- BASE CSS -->
            <link href="../../css/bootstrap_customized.min.css" rel="stylesheet">
            <link href="../../css/style.css" rel="stylesheet">
        
            <!-- SPECIFIC CSS -->
            <link href="../../css/blog.css" rel="stylesheet">
        
            <!-- ALTERNATIVE COLORS CSS -->
            <link href="#" id="colors" rel="stylesheet">

            <style type="text/css">
                header {
                    background-color: #222;
                }
            </style>
        
        </head>
        
        <body>
                        
            <?php include "../../includes/header.php";?>
            
            <main style="margin-top: 80px">
                <div class="page_header element_to_stick">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8 col-lg-7 col-md-7 d-none d-md-block">
                                <div class="breadcrumbs blog">
                                    <ul>
                                        <li><a href="https://my.sabooksonline.co.za/">Home</a></li>
                                        <li><a href="https://my.sabooksonline.co.za/events.php">Events</a></li>
                                        <li><?php include "includes/title.php";?></li>
                                    </ul>
                                    </div>
                            </div>
                            
                        </div>
                        <!-- /row -->		       
                    </div>
                </div>
                <!-- /page_header -->
        
                <div class="container margin_30_40">			
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="singlepost">
                                <figure><img alt="<?php include "includes/title.php";?>" class="img-fluid" src="<?php include "includes/cover.php";?>"></figure>
                                <h1><?php include "includes/title.php";?></h1>
                                <div class="postmeta">
                                    <ul>
                                        <li><a href="#"><i class="icon_pencil-edit"></i> Admin</a></li>
                                        <li><i class="icon_calendar"></i> <?php include "includes/date.php";?></li>
                                        <li><a href="#"><i class="icon_clock"></i> <?php include "includes/time.php";?></a></li>
                                    </ul>
                                </div>
                                <!-- /post meta -->
                                <div class="post-content">
                                    <?php include "includes/overview.php";?>
                                </div>
                                <!-- /post -->
                            </div>
                            <!-- /single-post -->
        
        
                            <hr>
        
                            <!--<h5>RSVP NOW</h5>
							<form id="rsvp">
                            <div class="row">
                            
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="name" id="name2" class="form-control" placeholder="Name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="email" id="email2" class="form-control" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="contact" id="website" class="form-control" placeholder="Contact number" required>
                                        <input type="hidden" name="contentid" id="website" class="form-control" value="'.$contentid.'" required>
                                    </div>
                                </div>
								 <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                       <div class="form-group">
                                <button type="submit" id="submit2" class="btn_1 add_bottom_15">Submit Your Request</button>
                            </div></div>
                                </div>
								 
        
                            <div class="form-group" id="rsvp_result">
                                
                            </div>
                            </div>
                            
                           
                        <form>-->
                        </div>
                        <!-- /col -->
        
                        <?php include "../../includes/events/aside.php"?>
                        <!-- /aside -->
                    </div>
                    <!-- /row -->	
                </div>
                <!-- /container -->
                
            </main>
            <!-- /main -->
        
            <?php include "../../includes/footer.php";?>
            
            <!-- COMMON SCRIPTS -->
            <script src="../../js/common_scripts.min.js"></script>
            <script src="../../js/common_func.js"></script>
            <script src="../../assets/validate.js"></script>
            <script src="../../includes/register.js"></script>
        </body>
        </html>
                  
                  ';

        $folder = strtolower(str_replace(' ', '-', $title));
        //create book folders

        if(mkdir("../../../../events/".$contentid)){

            mkdir("../../../../events/".$contentid."/includes");

            $file = "../../../../events/".$contentid."/index.php";
            $myfile = fopen($file , "w");
            fwrite($myfile, $page);

            if(fclose($myfile)){
                $page_overview = "../../../../events/".$contentid."/includes/overview.php";
                $myfile = fopen($page_overview , "w");
                fwrite($myfile, $desc);

                $page_overview = "../../../../events/".$contentid."/includes/title.php";
                $myfile = fopen($page_overview , "w");
                fwrite($myfile, $title);

                $page_overview = "../../../../events/".$contentid."/includes/date.php";
                $myfile = fopen($page_overview , "w");
                fwrite($myfile, $date);

                $page_overview = "../../../../events/".$contentid."/includes/time.php";
                $myfile = fopen($page_overview , "w");
                fwrite($myfile, $time);


                if(fclose($myfile)){
                    //upload the image

                    $targetPath = "../../../../events/".$contentid."/".$_FILES['eng_cover']['name'];

                    $targetPath1 = $_FILES['eng_cover']['name'];
                    if(move_uploaded_file($sourcePath,$targetPath)){
                        $targetPath1 = $_FILES['eng_cover']['name'];
    
                        $page_profile = "../../../../events/".$contentid."/includes/cover.php";
                        $myfile = fopen($page_profile , "w");
                        fwrite($myfile, $targetPath1);
                        $sql = "INSERT INTO events ( TITLE ,DESCRIPTION ,EVENTDATE ,EVENTTIME ,VENUE ,KEYWORDS ,CONTENTID ,DATEPOSTED, COVER, CURRENT) VALUES ('$title','$desc','$date','$time','$venue','$keywords','$contentid','$dateposted','$targetPath1', 'Pending');";
          
                        if(mysqli_query($conn, $sql)){
      
                        echo "Event Has Been Published!";

                        } else {

                            echo "We encountered some issues!";
                        
                        }
                       
                    }else {
                      
                    }

                }else{
                    echo "Post Has Not Been Published!";
                }

            }else {
                echo "Something went wrong";
            }


        }else {
            echo "../../../../events/".$contentid."/".$title;
        }


        //image upload CODESET
        /*$targetPath = "../../../../posts/".$_SESSION['ADMIN_USERKEY']."/".$_FILES['eng_cover']['name'];
        $targetPath1 = "../".$_FILES['eng_cover']['name'];
        if(move_uploaded_file($sourcePath,$targetPath)){



        }else{
            echo 'Image not uploaded';
        }*/




     
?>