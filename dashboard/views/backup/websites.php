<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="description" content="SA Books Online">
<meta name="SA Books Online" content="ATFN">
<!-- css file -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/ace-responsive-menu.css">
<link rel="stylesheet" href="css/menu.css">
<link rel="stylesheet" href="css/fontawesome.css">
<link rel="stylesheet" href="css/flaticon.css">
<link rel="stylesheet" href="css/bootstrap-select.min.css">
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/slider.css">
<link rel="stylesheet" href="css/jquery-ui.min.css">
<link rel="stylesheet" href="css/magnific-popup.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/ud-custom-spacing.css">
<link rel="stylesheet" href="css/dashbord_navitaion.css">

<!-- Responsive stylesheet -->     
<link rel="stylesheet" href="css/responsive.css">
<!-- Title -->    
<title>Manage Websites</title>
<!-- Favicon -->
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" />
<!-- Apple Touch Icon -->
<link href="images/apple-touch-icon-60x60.png" sizes="60x60" rel="apple-touch-icon">
<link href="images/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
<link href="images/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
<link href="images/apple-touch-icon-180x180.png" sizes="180x180" rel="apple-touch-icon">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="sweetalert2.min.css">

<script src="https://jsuites.net/v4/jsuites.js"></script>
<script src="https://jsuites.net/v4/jsuites.webcomponents.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />

<script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>  

<style>
    .dashboard_sidebar_list .sidebar_list_item a:hover,
.dashboard_sidebar_list .sidebar_list_item a:active,
.dashboard_sidebar_list .sidebar_list_item a:focus,
.dashboard_sidebar_list .sidebar_list_item a.web{
  background-color: #222222;
  color: #ffffff;
}
</style>
</head>
<body>
<div class="wrapper">
  <div class="preloader"></div>
  
  <!-- Main Header Nav -->
  <?php include 'includes/header-dash-main.php';?>
  <div class="dashboard_content_wrapper">
    <div class="dashboard dashboard_wrapper pr30 pr0-xl">
      <?php include 'includes/header-dash.php';?>
      <div class="dashboard__main pl0-md">
        <div class="dashboard__content hover-bgc-color">
          <div class="row pb40">
           <?php include 'includes/mobile-guide.php';?>
            <div class="col-lg-9">
              <div class="dashboard_title_area">
                <h2>Website Management & Emails</h2>
                <p class="text">You can manage, add or delete your websites.</p>
              </div>
            </div>
            <div class="col-lg-3 d-none">
              <div class="text-lg-end">
                <a href="create" class="ud-btn btn-dark default-box-shadow2">Create New Website<i class="fal fa-arrow-right-long"></i></a>
              </div>
            </div>
          </div>

         <?php 

        include '../includes/database_connections/sabooks_plesk.php';

        $domain_name = '';

        // Assuming you have defined the USERKEY value you want to use for the query
        $userkeyToSearch = $_SESSION['ADMIN_USERKEY'];

        // Prepare the SELECT statement
        $query = "SELECT * FROM plesk_accounts WHERE USERKEY = ?";

        // Prepare and bind the parameter
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('s', $userkeyToSearch);

        // Execute the prepared statement
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Check if any rows are found
        if ($result->num_rows > 0) {
            // Fetch and display the rows
            while ($row = $result->fetch_assoc()) {
                $domain_name = $row['DOMAIN'];
                $domain_id = $row['PLESK_ID'];
            }

            if(strpos($domain_name, 'sabooksonline.co.za') !== false){
                $registerDomain = true;
            } else {
                $registerDomain = false;
            }

            $domain = true;

        } else {
            $domain = false;
        }

        // Close the statement and connection
        $stmt->close();
        $mysqli->close();


         if($_SESSION['ADMIN_SUBSCRIPTION'] == 'Free'){

            include '../includes/backend/screens/subdomain-creation.php';

         } elseif($_SESSION['ADMIN_SUBSCRIPTION'] == 'Standard'){

            if($domain == true){
                
                if($registerDomain == false){
                    include '../includes/backend/screens/email-creation.php';
                } else if($registerDomain == true){
                    include '../includes/backend/screens/register-domain.php';
                } 

            } else if($domain == false){
                include '../includes/backend/screens/register-domain.php';
            } 

         } elseif($_SESSION['ADMIN_SUBSCRIPTION'] == 'Premium'){

            if($domain == true){
                if($registerDomain == false){
                    include '../includes/backend/screens/email-creation.php';
                } else if($registerDomain == true){
                    include '../includes/backend/screens/register-domain.php';
                } 

            } else if($domain == false){
                include '../includes/backend/screens/register-domain.php';
            } 

         } elseif($_SESSION['ADMIN_SUBSCRIPTION'] == 'Deluxe'){

            if($domain == true){
                if($registerDomain == false){
                    include '../includes/backend/screens/email-creation.php';
                } else if($registerDomain == true){
                    include '../includes/backend/screens/register-domain.php';
                } 

            } else if($domain == false){
                include '../includes/backend/screens/register-domain.php';
            } 

         }


         

         
            ?>



          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="navtab-style1">
                  <nav>
                    <div class="nav nav-tabs mb30" id="nav-tab2" role="tablist">
                      <button class="nav-link active fw500 ps-0" id="nav-item1-tab" data-bs-toggle="tab" data-bs-target="#nav-item1" type="button" role="tab" aria-controls="nav-item1" aria-selected="true">Active Websites</button>
                      <button class="nav-link fw500" id="nav-item2-tab" data-bs-toggle="tab" data-bs-target="#nav-item2" type="button" role="tab" aria-controls="nav-item2" aria-selected="false">Email Accounts</button>
                    
                    </div>
                  </nav>
                  <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-item1" role="tabpanel" aria-labelledby="nav-item1-tab">
                      <div class="packages_table table-responsive">
                        <table class="table-style3 table at-savesearch">
                          <thead class="t-head">
                            <tr>
                              <th scope="col">Website Name</th>
                              <th scope="col">Domain</th>
                              <th scope="col">Date Created</th>
                              <th scope="col">Status</th>
                              <th scope="col">Actions</th>
                              <th scope="col">View Website</th>
                            </tr>
                          </thead>
                          <tbody class="t-body">

                          <?php 

                          //DATABASE CONNECTIONS SCRIPT
                          include '../includes/database_connections/sabooks_plesk.php';

                          //sabo/demo/html-demo/home/index.php

                          $userkey = $_SESSION['ADMIN_USERKEY'];

                          $sqls = "SELECT * FROM plesk_accounts WHERE USERKEY = '$userkey';";


                          if(!$results = mysqli_query($mysqli, $sqls)){

                            echo "<div class='alert alert-info border-none'>You currently have no content uploaded.<a href='dashboaord-add-book'> Add New Book</a>.</div";

                            } else{

                            while($rows = mysqli_fetch_assoc($results)) {

                                $status = $rows['STATUS'];
                                $service_status = $rows['STATUS'];

                                if($status == "Active" || $status == "active"){
                                    $status = '<td class="vam"><span class="pending-style style1 bg-success text-white">'.ucwords($rows['STATUS']).'</span></td>';
                                } else if($status == "Service Locked"){
                                    $status = '<td class="vam"><span class="pending-style style1 bg-danger text-white">'.ucwords($rows['STATUS']).'</span></td>';
                                } else if($status == "Pending"){
                                    $status = '<td class="vam"><span class="pending-style style1 bg-warning text-white">'.ucwords($rows['STATUS']).'</span></td>';
                                } else if($status == "Draft"){
                                    $status = '<td class="vam"><span class="pending-style style1 bg-info text-white">'.ucwords($rows['STATUS']).'</span></td>';
                                }

                                if($service_status == "Service Locked"){
                                    $service_status = '';
                                } else {
                                    $service_status = '<a href="edit-website?domain='.$rows['DOMAIN'].'" class="icon me-2 text-" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="flaticon-pencil"></span></a>';
                                }

                                if((strpos($rows['DOMAIN'], '.sabooksonline.co.za') !== false)){
                                    $type = 'delete_subdomain';

                                }else{
                                    $type = 'delete_customer';

                                }


                                $_SESSION['DOMAIN'] = $rows['DOMAIN'];

                            echo ' <tr>
                            <th class="dashboard-img-service" scope="row">
                              <div class="listing-style1 list-style d-block d-xl-flex align-items-start border-0 mb-0">
                                <!--<div class="list-thumb flex-shrink-0 bdrs4 mb10-lg" id="webpage">
                                  
                                </div>-->
                                <div class="list-content flex-grow-1 py-0 pl15 pl0-lg">
                                  <h6 class="list-title mb-0"><a href="page-services-single.html">'.$rows['ACCOUNT_NAME'].'</a></h6>
                                  <!--<ul class="list-style-type-bullet ps-3 dashboard-style mb-0">
                                    <li>Delievred with in a day</li>
                                    <li>Delievry Time Descreased</li>
                                    <li>Upload apps to Stores</li>
                                  </ul>-->
                                </div>
                              </div>
                            </th>

                            <td class="align-top"><span class="fz15 fw400"><a class="text-success" href="https://'.$rows['DOMAIN'].'/" target="_blank">'.$rows['DOMAIN'].' <i class="fa fa-external-link"></i></a></span></td>
                            <td class="align-top"><span class="fz14 fw400">'.$rows['CREATED'].'</span></td>
                            '.$status.'
                            <td class="align-top">
                              <div class="d-flex">
                              '.$service_status.'
                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalWebsite'.$rows['ID'].'" class="icon me-2 " data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" id="delete_customer"><span class="flaticon-delete"></span></a>
                                <a href="http://41.76.111.78/plesk-site-preview/'.$rows['DOMAIN'].'/https/41.76.111.78/"  target="_blank" class="icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Temporary Link" ><span class="flaticon-website"></span></a>
                              </div>
                            </td>
                            <td class="vam">
                                <a href="https://'.$rows['DOMAIN'].'/" target="_blank" class="table-action fz15 fw500 text-thm2" data-bs-toggle="tooltip" data-bs-placement="top" title="View"><span class="flaticon-website me-2 vam"></span> Visit Site</a>
                            </td>
                          </tr>
                          
                          
                          <!-- Modal -->
                          <div class="modal fade" id="exampleModalWebsite'.$rows['ID'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered border-none" style="border: none !important;">
                              <div class="modal-content border-none" style="border: none !important;">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Are You Sure You want to delete the book?</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  You are about to delete <b>'.ucwords($rows['DOMAIN']).'</b>, this action cannot be undone once complete. Your Website & Data including emails will be removed from this platform instantly.
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancel</button>
                                  <a href="#" type="button" class="btn btn-danger text-white '.$type.'" data-contentid="'.$rows['DOMAIN'].'">Continue <small class="icon_trash"></small></a>
                              </div>
                              </div>
                          </div>
                          </div>';

                            }
                          }

                          if(isset($_GET['status'])){

                                if($_GET['status'] == 'message'){
                                    echo '<div class="alert alert-danger">Your website is not yet secure with SSL, <b>The SSL will be installed between 1 - 2 hours from creation time</b>. In the meantime you can use a temporary link  <b><a href="http://41.76.111.78/plesk-site-preview/'.$_SESSION['DOMAIN'].'/https/41.76.111.78/" target="_blank">View Temporary Link  <i class="fa fa-external-link"></i></a></b></div>';
                                }
                                
                            }

                           ?>
    
                           
                            
                          </tbody>
                        </table>
                        
                      </div>
                    </div>

                    <div class="tab-pane fade show" id="nav-item2" role="tabpanel" aria-labelledby="nav-item2-tab">

                    
                      <div class="packages_table table-responsive">
                        <table class="table-style3 table at-savesearch">
                          <thead class="t-head">
                            <tr>
                              <th scope="col">Email Address</th>
                              <th scope="col">Date Created</th>
                              <th scope="col">Password</th>
                              <th scope="col">Actions</th>
                              <th scope="col">Webmail</th>
                            </tr>
                          </thead>
                          <tbody class="t-body">

                          <?php 

                          session_start(); 

                          ini_set('display_errors', 1);
                          ini_set('display_startup_errors', 1);
                          error_reporting(E_ALL);

                                            
                          //DATABASE CONNECTIONS SCRIPT
                          include '../includes/database_connections/sabooks_plesk.php';

                          //sabo/demo/html-demo/home/index.php

                          $userkey = $_SESSION['ADMIN_USERKEY'];

                          $sqls = "SELECT * FROM plesk_emails WHERE EMAIL_USERID = '$userkey';";


                          if(!$results = mysqli_query($mysqli, $sqls)){

                            echo "<div class='alert alert-info border-none'>You currently have no emails created.</div";

                            } else{

                            while($rows = mysqli_fetch_assoc($results)) {

                            echo ' <tr>
                            <th class="dashboard-img-service" scope="row">
                              <div class="listing-style1 list-style d-block d-xl-flex align-items-start border-0 mb-0">
                                <!--<div class="list-thumb flex-shrink-0 bdrs4 mb10-lg" id="webpage">
                                  
                                </div>-->
                                <div class="list-content flex-grow-1 py-0 pl15 pl0-lg">
                                  <h6 class="list-title mb-0"><a href="#">'.$rows['EMAIL_ACCOUNT'].'</a></h6>
                                  <!--<ul class="list-style-type-bullet ps-3 dashboard-style mb-0">
                                    <li>Delievred with in a day</li>
                                    <li>Delievry Time Descreased</li>
                                    <li>Upload apps to Stores</li>
                                  </ul>-->
                                </div>
                              </div>
                            </th>

                            <td class="align-top"><span class="fz15 fw400">'.$rows['EMAIL_DATE'].'</span></td>
                            <td class="align-top"><span class="fz14 fw400 copyText">'.$rows['EMAIL_PASSWORD'].'</span> <button   class="copy-button" style="border: none !important;border-radius:5px;" data-password="'.$rows['EMAIL_PASSWORD'].'">Copy<input type="hidden" class="copyText" value="'.$rows['EMAIL_PASSWORD'].'"></button></td>
                            <td class="align-top">
                              <div class="d-flex">
                                <a href="#" class="icon d-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="flaticon-pencil"></span></a>

                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal'.$rows['ID'].'" class="icon " data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" target-contentid="'.$rows['EMAIL_ACCOUNT'].'"><span class="flaticon-delete"></span></a>
                              </div>
                            </td>
                            <td class="vam">
                                <a href="https://webmail.'.$_SESSION['DOMAIN'].'/" target="_blank" class="table-action fz15 fw500 text-thm2" data-bs-toggle="tooltip" data-bs-placement="top" title="View"><span class="flaticon-website me-2 vam"></span> Login to mail</a>
                            </td>
                          </tr>
                          
                          
                          <!-- Modal -->
                          <div class="modal fade" id="exampleModal'.$rows['ID'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered border-none" style="border: none !important;">
                              <div class="modal-content border-none" style="border: none !important;">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Are You Sure You want to delete the website?</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  You are about to delete <b>'.ucwords($rows['EMAIL_ACCOUNT']).'</b>, this action cannot be undone once complete. Your data will be removed from this platform instantly.
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancel</button>
                                  <a href="#" type="button" class="btn btn-danger text-white delete_item" data-contentid="'.$rows['EMAIL_ACCOUNT'].'">Continue <small class="icon_trash"></small></a>
                              </div>
                              </div>
                          </div>
                          </div>';

                            }
                          }

                           ?>

                           
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                        var copyButtons = document.querySelectorAll('.copy-button');
                        
                        copyButtons.forEach(function(button) {
                            button.addEventListener('click', function() {
                            var copyText = button.previousElementSibling.innerText;
                            copyToClipboard(copyText);
                            alert('Text copied to clipboard: ' + copyText);
                            });
                        });
                        
                        function copyToClipboard(text) {
                            var textArea = document.createElement('textarea');
                            textArea.value = text;
                            document.body.appendChild(textArea);
                            textArea.select();
                            document.execCommand('copy');
                            document.body.removeChild(textArea);
                        }
                        });
                        </script>

    
                           
                            
                          </tbody>
                        </table>
                        
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php include 'includes/footer.php';?>
      </div>
    </div>
  </div>
  <a class="scrollToHome" href="#"><i class="fas fa-angle-up"></i></a>
</div>





<!-- Wrapper End -->
<script src="js/jquery-3.6.4.min.js"></script> 
<script src="js/jquery-migrate-3.0.0.min.js"></script> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/bootstrap-select.min.js"></script> 
<script src="js/jquery.mmenu.all.js"></script> 
<script src="js/ace-responsive-menu.js"></script> 
<script src="js/jquery-scrolltofixed-min.js"></script>
<script src="js/dashboard-script.js"></script>
<script src="js/pricing-table.js"></script>
<!-- Custom script for all pages --> 
<script src="js/script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$("#domain-search").on('submit',(function(e) {
        e.preventDefault();

        //let value = $("#reg_load").value();
    
        $("#domain_load").html('<div class="spinner-border text-light" role="status"> <span class="sr-only">Loading...</span> </div>');
        
        //showSwal('success-message');
            $.ajax({
                    url: "../includes/backend/scripts/domains/search_domain.php",
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                        cache: false,
                    processData:false,
                    success: function(data)
                {
                    $("#domain_load").html('Search');
                    $("#domain_status").html(data);
                    },
                error: function(){}
                });

            }));

            $("#email-creation").on('submit',(function(e) {
        e.preventDefault();

        //let value = $("#reg_load").value();
    
        $("#domain_load").html('<div class="spinner-border text-light" role="status"> <span class="sr-only">Loading...</span> </div>');
        
        //showSwal('success-message');
            $.ajax({
                    url: "../includes/backend/scripts/functions/email_permissions.php",
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                        cache: false,
                    processData:false,
                    success: function(data)
                {
                    $("#domain_load").html('Create Email');
                    $("#domain_status").html(data);
                    },
                error: function(){}
                });



            }));



            $(".delete_customer").click(function(){

                let contentid = $(this).attr('data-contentid');
                //let contentid = 'data';

                //alert(locate);

                $(this).html('<div class="spinner-border text-white" role="status"> <span class="sr-only">Loading...</span> </div>');

                $.post("../includes/backend/scripts/website/remove_customer.php",
                {
                    email_name: contentid
                },

                function(data, status){
                    $("#domain_status").html(data);
                }

                /*function(data, status){
                    alert("Data: " + data + "\nStatus: " + status);
                }*/);
            });

            $(".delete_subdomain").click(function(){

            let contentid = $(this).attr('data-contentid');
            //let contentid = 'data';

            //alert(locate);

            $(this).html('<div class="spinner-border text-white" role="status"> <span class="sr-only">Loading...</span> </div>');

            $.post("../includes/backend/scripts/website/remove_subdomain_website.php",
            {
                domain: contentid
            },

            function(data, status){
                $("#domain_status").html(data);
            }

            /*function(data, status){
                alert("Data: " + data + "\nStatus: " + status);
            }*/);
            });


            $(".delete_item").click(function(){

                let contentid = $(this).attr('data-contentid');

                //alert(locate);

                $(this).html('<div class="spinner-border text-white" role="status"> <span class="sr-only">Loading...</span> </div>');

                $.post("../includes/backend/scripts/website/remove_email.php",
                {
                    email_name: contentid
                },

                function(data, status){
                    $("#domain_status").html(data);
                }

                /*function(data, status){
                    alert("Data: " + data + "\nStatus: " + status);
                }*/);
            });


                $(".copyButton").click(function() {

                    var copyText = $(this).$('data-password');
                    copyText.select();
                    document.execCommand("copy");
                    alert("Copied the text: " + copyText.val());
                });


</script>

<script>
$(document).ready(function() {
    $("#copyButton").click(function() {
        var copyText = $("#copyText");
        copyText.select();
        document.execCommand("copy");
        alert("Copied the text: " + copyText.val());
    });
});
</script>


<script>
    function myFunctions() {
    // Get the text field
    var copyText = document.getElementByClass("myInput");

    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);

    // Alert the copied text
    alert("Copied the text: " + copyText.value);
    }
</script>

<!-- Add this script after including Shepherd.js -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Shepherd.js
    const tour = new Shepherd.Tour({
        defaultStepOptions: {
            classes: 'shepherd-theme-arrows', // Use the blue theme
            arrowClass: 'custom-arrow', // Add custom arrow class
            attachToElement: document.body, // Ensure arrow visibility
            arrow: true, // Show arrow
            arrowColor: '#111', // Set arrow color
            arrowSize: 15, // Set arrow size
        },
    });  

    // Add tour steps
    tour.addStep({
        id: 'step1',
        text: '<b>Websites:</b><hr> You can create, edit and manage your website.',
        attachTo: { element: '#element5', on: 'right' },
        classes: 'example-step-extra-class custom-step-content', // Add custom step class
        buttons: [
            {
                text: 'Skip',
                action: tour.cancel,
            },
            {
                text: 'Next',
                action: tour.next,
            },
        ],
    });

    // Add tour steps
    tour.addStep({
        id: 'step2',
        text: '<b>Type in your domain:</b><hr> You can type in your prefered domain name.',
        attachTo: { element: '#domain-type', on: 'bottom' },
        classes: 'example-step-extra-class custom-step-content', // Add custom step class
        buttons: [
            {
                text: 'Skip',
                action: tour.cancel,
            },
            {
                text: 'Next',
                action: tour.next,
            },
        ],
    });


    // Add tour steps
    tour.addStep({
        id: 'step2',
        text: '<b>Select the prefered extension:</b><hr> You can choose which domain tld you prefer.',
        attachTo: { element: '#domain-search', on: 'bottom' },
        classes: 'example-step-extra-class custom-step-content', // Add custom step class
        buttons: [
            {
                text: 'Skip',
                action: tour.cancel,
            },
            {
                text: 'Next',
                action: tour.next,
            },
        ],
    });


      // Add tour steps
      tour.addStep({
        id: 'step10',
        text: '<b>Search Domain:</b><hr> You can check if your domain is available for use.',
        attachTo: { element: '#domain_load', on: 'bottom' },
        classes: 'example-step-extra-class custom-step-content', // Add custom step class
        buttons: [
            {
                text: 'Skip',
                action: tour.cancel,
            },
            {
                text: 'Complete',
                action: tour.complete,
            },
        ],
    });

    

    // Attach the click event listener to the button
    document.getElementById('startTourButton').addEventListener('click', function () {
        // Start the tour  
        tour.start();
    });

});

</script>


</body>


</html>