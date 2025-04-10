<?php 

?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="SABO" content="ATFN">
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
<title>Dashboard</title>
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


<script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>

<style>
    .dashboard_sidebar_list .sidebar_list_item a:hover,
.dashboard_sidebar_list .sidebar_list_item a:active,
.dashboard_sidebar_list .sidebar_list_item a:focus,
.dashboard_sidebar_list .sidebar_list_item a.dashboard{
  background-color: #222222;
  color: #ffffff;
}

        /* Define CSS styles for printing */
        @media print {
            /* Define styles for elements to be printed */
            body {
                font-size: 10pt;
            }

            /* Define styles for elements to be excluded */
            .no-print {
                display: none;
            }
        }

        .welcome-message {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .next-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .blurred {
  color: #444;
  filter: blur(5px);
  cursor: pointer;
  transition: 0.3s ease;
  position: relative;
}
.blurred:hover {
  filter: blur(3px);
}
.blurred::after {
  content: " 🔒";
  position: absolute;
  right: -1.5em;
  color: #ff6b6b;
}

    </style>

</head>
<body>
<div class="wrapper">

  <!-- Main Header Nav -->
  <?php include 'includes/header-dash-main.php';
  
 

  ?>

  <div class="dashboard_content_wrapper">
    <div class="dashboard dashboard_wrapper pr30 pr0-xl">
    <?php include 'includes/header-dash.php';?>
      <div class="dashboard__main pl0-md">
        <div class="dashboard__content hover-bgc-color">
          <div class="row pb40">
            <div class="no-print">
                <?php include 'includes/mobile-guide.php';?>
            </div>
            <div class="col-lg-9">
              <div class="dashboard_title_area">
                <h2>Dashboard</h2>
                <p class="text">Traffic & Data Overview</p>
              </div>

              <?php  
    
                //DATABASE CONNECTIONS SCRIPT
                include '../includes/database_connections/sabooks_user.php';
                    
                    // if ($_SESSION['ADMIN_SUBSCRIPTION'] == 'Free') {

                    //     echo "<script>window.location.replace('basic');</script>";

                    // } 
            
                ?>

              
            </div>

            <div class="col-lg-3 no-print">
              <div class="text-lg-end">
                <a href="#" class="ud-btn btn-dark default-box-shadow2" onclick="printPage()">Print To PDF<i class="fal fa-print"></i></a>
              </div>
            </div>
          </div>

          <form class="row" action="index">

          <?php

          if(!isset($_GET['start']) || !isset($_GET['end'])){

            $current_date = new DateTime();
            $start_date = $current_date->modify('-30 days')->format('Y-m-d');

            // End date is today + 1 day
            $end_date = date('Y-m-d', strtotime('+1 day'));

            // Create an empty array to store the date range
            $date_range = [];

            // Convert the start and end dates to DateTime objects
            $start_datetime = new DateTime($start_date);
            $end_datetime = new DateTime($end_date);

            // Iterate through the date range and add each day to the array
            while ($start_datetime <= $end_datetime) {
                $date_range[] = $start_datetime->format('Y-m-d');
                $start_datetime->modify('+5 day');
            }


          } else {

            $start_date = $_GET['start'];
            $end_date = $_GET['end'];

          }

          ?>

          <div class="col-md-4"><label>Showing Data From: </label><input type="date" value="<?php echo $start_date;?>" class="form-control bdrs2" name="start"></div>
            
          <div class="col-md-4"><label> To: </label><input type="date" value="<?php echo $end_date;?>" class="form-control bdrs2" name="end"></div>
            
          <div class="col-md-4"><br><button type="submit" class="ud-btn btn-thm w-100">Update Analytics<i class="fal fa-arrow-right-long"></i></button></div>

          </form>
          <hr>
         <?php

 //DATABASE CONNECTIONS SCRIPT
 include '../includes/database_connections/sabooks_user.php';

// Initialize default monthly sums to 0
$monthly_sums = array_fill_keys(['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'], 0);


    $jan = $monthly_sums['jan'];
    $feb = $monthly_sums['feb'];
    $mar = $monthly_sums['mar'];
    $apr = $monthly_sums['apr'];
    $may = $monthly_sums['may'];
    $jun = $monthly_sums['jun'];
    $jul = $monthly_sums['jul'];
    $aug = $monthly_sums['aug'];
    $sep = $monthly_sums['sep'];
    $oct = $monthly_sums['oct'];
    $nov = $monthly_sums['nov'];
    $dec = $monthly_sums['dec'];
    //die("Connection failed: " . $con->connect_error);


?>


<div class="row">
<div class="col-sm-6 col-xxl-3">
  <div class="d-flex align-items-center justify-content-between statistics_funfact">
    <div class="details">
      <div class="fz15">Net Income</div>
      <div class="title">R
        
      <?php
          

            if($websitedata == false){
                echo '0';
            } else {
                $result = mysqli_query($con, "SELECT SUM(product_total) AS value_sum FROM product_order WHERE product_current = 'completed'"); 
                $row = mysqli_fetch_assoc($result); 
                $sum = $row['value_sum'];

                echo $sum;
            }


                                    
            
    ?>

      </div>
    </div>
    <div class="icon text-center"><i class="flaticon-income"></i></div>
  </div>
</div>
<div class="col-sm-6 col-xxl-3">
  <div class="d-flex align-items-center justify-content-between statistics_funfact">
    <div class="details">
      <div class="fz15">Transactions</div>
        <div class="title">
        <?php

        include '../includes/database_connections/sabooks_user.php';

        if ($websitedata) {
            $rows_query = mysqli_query($con, "SELECT COUNT(*) as number_rows FROM product_order WHERE product_current = 'COMPLETED';");

            if ($rows_query) {
                $rows = mysqli_fetch_assoc($rows_query);
                echo $rows['number_rows'];
            } else {
                echo "Error fetching data: " . mysqli_error($con);
            }
        } else {
            echo '0';
        }

        ?>

        </div>
    </div>
    <div class="icon text-center"><i class="flaticon-withdraw"></i></div>
  </div>
</div>
<div class="col-sm-6 col-xxl-3">
  <div class="d-flex align-items-center justify-content-between statistics_funfact">
    <div class="details">
      <div class="fz15">Total Customers</div>
      <div class="title"><?php


        if($websitedata == false){
            echo '0';
        } else if($websitedata == true){

        $rows_query = mysqli_query($con, "SELECT COUNT(*) as number_rows FROM user_info;");

        $rows = mysqli_fetch_assoc($rows_query);

        echo $rows['number_rows'];

        }

        ?></div>
    </div>
    <div class="icon text-center"><i class="flaticon-review"></i></div>
  </div>
</div>
<div class="col-sm-6 col-xxl-3">
  <div class="d-flex align-items-center justify-content-between statistics_funfact">
    <div class="details">
      <div class="fz15">Pending Orders</div>
      <div class="title"><?php

        if($websitedata == false){
            echo '0';
        } else if($websitedata == true){

      $rows_query = mysqli_query($con, "SELECT COUNT(*) as number_rows FROM  product_order WHERE product_current = 'cart';");

      $rows = mysqli_fetch_assoc($rows_query);

      echo $rows['number_rows'];

        }

      ?></div>
    </div>
    <div class="icon text-center"><i class="flaticon-review-1"></i></div>
  </div>
</div>
</div>


          <div class="row">

          <!-- <?php
          if ($_SESSION['ADMIN_SUBSCRIPTION'] == 'Deluxe') {
              
          } else {
              echo '<style>.book-status{display:none !important;}</style>';
          }
          ?> -->


            <div class="col-sm-6 col-xxl-3 book-status">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Book Store</div>
                  <div class="title">
                    
                    <?php

                    //DATABASE CONNECTIONS SCRIPT
                    include '../includes/database_connections/sabooks.php';

                  // Define the USERID you want to filter by
                  $user_id = $_SESSION['ADMIN_USERKEY'];

                  // Define the start and end date for your date range (replace with actual dates)
                  // Calculate the start date (30 days ago from today)

                  // Prepare the SQL query to select CONTENTID based on USERID
                  $query = "SELECT ID FROM book_stores WHERE USERID = ?";
                  $stmt = $conn->prepare($query);

                  if ($stmt) {
                      // Bind the USERID parameter and execute the query
                      $stmt->bind_param("s", $user_id);
                      $stmt->execute();

                      // Get the results into an array
                      $result = $stmt->get_result();
                      $book_ids = $result->fetch_all(MYSQLI_ASSOC);

                      // Close the prepared statement
                      $stmt->close();

                      // Extract CONTENTID values and format as an array
                      $book_ids = array_column($book_ids, 'ID');

                      // Prepare the SQL query to count page visits within the date range
                      $query = "SELECT COUNT(*) AS visit_count
                                FROM page_visits AS pv
                                INNER JOIN book_stores AS p ON pv.page_url LIKE CONCAT('%', p.ID, '%')
                                WHERE pv.page_url LIKE ? AND p.ID = ?
                                AND DATE(pv.visit_time) BETWEEN ? AND ?"; // Adjust the date format if needed

                      $stmt = $conn->prepare($query);

                      if ($stmt) {
                          // Initialize a variable to store the combined visit count
                          $combined_visit_count = 0;

                          // Define the LIKE pattern
                          $likePattern = "%store%";

                          foreach ($book_ids as $book_id) {
                              // Bind the parameters
                              $stmt->bind_param("siss", $likePattern, $book_id, $start_date, $end_date);

                              // Execute the query
                              $stmt->execute();

                              // Bind the result and fetch
                              $stmt->bind_result($visit_count);
                              $stmt->fetch();

                              // Add the visit count to the combined total
                              $combined_visit_count += $visit_count;
                          }

                          // Close the prepared statement
                          $stmt->close();

                          $store = $combined_visit_count;

                          // Output the combined visit count
                          echo $combined_visit_count;
                      } else {
                          echo "0";
                      }
                  } else {
                      echo "0";
                  }


                  ?>

                  </div>
                  <div class="text fz14"><span class="text-thm"><?php

                  // Define the USERID you want to filter by
                  $user_id = $_SESSION['ADMIN_USERKEY'];

                  // Prepare the SQL query to select CONTENTID based on USERID
                  $query = "SELECT ID FROM book_stores WHERE USERID = ?";
                  $stmt = $conn->prepare($query);

                  if ($stmt) {
                      // Bind the USERID parameter and execute the query
                      $stmt->bind_param("s", $user_id);
                      $stmt->execute();

                      // Get the results into an array
                      $result = $stmt->get_result();
                      $book_ids = $result->fetch_all(MYSQLI_ASSOC);

                      // Close the prepared statement
                      $stmt->close();

                      // Extract CONTENTID values and format as an array
                      $book_ids = array_column($book_ids, 'ID');

                      // Check if there are book IDs
                      if (!empty($book_ids)) {
                          // Prepare the SQL query to count unique users based on IPs within the date range
                          $query = "SELECT COUNT(DISTINCT user_ip) AS unique_user_count
                                    FROM page_visits AS pv
                                    INNER JOIN book_stores AS p ON pv.page_url LIKE CONCAT('%', p.ID, '%')
                                    WHERE pv.page_url LIKE ? AND p.ID IN (" . implode(',', array_fill(0, count($book_ids), '?')) . ")
                                    AND DATE(pv.visit_time) BETWEEN ? AND ?"; // Adjust the date format if needed

                          $stmt = $conn->prepare($query);

                          if ($stmt) {
                              // Initialize a variable to store the unique user count
                              $unique_user_count = 0;

                              // Define the LIKE pattern
                              $likePattern = "%store%";

                              // Bind parameters for IN clause (book IDs)
                              $params = array_merge(array($likePattern), $book_ids, array($start_date, $end_date));
                              $paramTypes = str_repeat("s", count($params));
                              $stmt->bind_param($paramTypes, ...$params);

                              // Execute the query
                              $stmt->execute();

                              // Bind the result to a variable
                              $stmt->bind_result($unique_user_count);

                              // Fetch the result
                              $stmt->fetch();

                              // Close the prepared statement
                              $stmt->close();

                              // Output the total number of unique users based on their IPs
                              if ($_SESSION['PLAN'] === 'deluxe') {
                                  echo 'Based on ' . $uniqueUsers;
                              } else {
                                  echo '<span class="blurred" title="Subscribe to unlock full stats">Based on ' . $uniqueUsers . '</span>';
                              }
                          } else {
                              echo "0";
                          }
                      } else {
                          echo "0";
                      }
                  } else {
                      echo "0";
                  }
                  ?></span> Users</div>
                </div>
                <div class="icon text-center"><i class="flaticon-contract"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Services Views</div>
                  <div class="title"><?php

                  session_start();
                  ini_set('display_errors', 1);
                  ini_set('display_startup_errors', 1);
                  error_reporting(E_ALL);
                  // Define the keyword to search for in the page_url
                  $keyword = "provider";

                  // Define the specific provider ID you want to filter by
                  $provider_id = $_SESSION['ADMIN_ID']; // Replace with your provider ID

                  // Define the start and end dates for your date range
                 

                  // Execute a SQL query to count page visits where page_url contains the keyword, the provider ID, and falls within the specified date range
                  $stmt = $conn->prepare("SELECT COUNT(*) AS visit_count
                                          FROM page_visits
                                          WHERE page_url LIKE ? AND page_url LIKE ? AND visit_time BETWEEN ? AND ?");
                  $stmt->bind_param("ssss", $likePattern, $providerPattern, $start_date, $end_date);

                  // Define the LIKE patterns for the keyword and provider ID
                  $likePattern = "%$keyword%";
                  $providerPattern = "%$provider_id%";

                  $stmt->execute();

                  // Bind the result to a variable
                  $stmt->bind_result($visit_count);

                  // Fetch the result
                  $stmt->fetch();

                  $services = $visit_count;

                  // Output the visit count
                  // echo $visit_count;
                  if ($_SESSION['PLAN'] === 'deluxe') {
                    echo $visit_count;
                } else {
                    echo '<span class="blurred" title="Subscribe to unlock full stats">' . $visit_count . '</span>';
                }
?>

                  // Close the prepared statement
                  $stmt->close();
                  ?>


                  </div>
                  <div class="text fz14"><span class="text-thm"><?php
                  // Define the keyword to search for in the page_url
                  $keyword = "provider";

                  // Define the specific provider ID you want to filter by
                  $provider_id = $_SESSION['ADMIN_ID']; // Replace with your provider ID

                  // Execute a SQL query to count unique users (based on IP addresses) who visited pages with the keyword and the provider ID within the specified date range
                  $stmt = $conn->prepare("SELECT COUNT(DISTINCT user_ip) AS unique_user_count
                                          FROM page_visits
                                          WHERE page_url LIKE ? AND page_url LIKE ? AND visit_time BETWEEN ? AND ?");
                  $stmt->bind_param("ssss", $likePattern, $providerPattern, $start_date, $end_date);

                  // Define the LIKE patterns for the keyword and provider ID
                  $likePattern = "%$keyword%";
                  $providerPattern = "%$provider_id%";

                  $stmt->execute();

                  // Bind the result to a variable
                  $stmt->bind_result($unique_user_count);

                  // Fetch the result
                  $stmt->fetch();

                  $uniqueUsers = $unique_user_count;

                  // Output the total number of unique users
                  echo 'Based on '.$uniqueUsers;

                  // Close the prepared statement
                  $stmt->close();
                  ?>
                  </span> Users</div>
                </div>
                <div class="icon text-center"><i class="flaticon-contract"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Book Views</div>
                  <div class="title"><?php
                  // Include your database connection code here (e.g., using mysqli).
                  

                  // Define the USERID you want to filter by
                  $user_id = $_SESSION['ADMIN_USERKEY'];

                  // Define the start and end date for your date range (replace with actual dates)
                  // Calculate the start date (30 days ago from today)

                  // Prepare the SQL query to select CONTENTID based on USERID
                  $query = "SELECT CONTENTID FROM posts WHERE USERID = ?";
                  $stmt = $conn->prepare($query);

                  if ($stmt) {
                      // Bind the USERID parameter and execute the query
                      $stmt->bind_param("s", $user_id);
                      $stmt->execute();

                      // Get the results into an array
                      $result = $stmt->get_result();
                      $book_ids = $result->fetch_all(MYSQLI_ASSOC);

                      // Close the prepared statement
                      $stmt->close();

                      // Extract CONTENTID values and format as an array
                      $book_ids = array_column($book_ids, 'CONTENTID');

                      // Prepare the SQL query to count page visits within the date range
                      $query = "SELECT COUNT(*) AS visit_count
                                FROM page_visits AS pv
                                INNER JOIN posts AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
                                WHERE pv.page_url LIKE ? AND p.CONTENTID = ?
                                AND DATE(pv.visit_time) BETWEEN ? AND ?"; // Adjust the date format if needed

                      $stmt = $conn->prepare($query);

                      if ($stmt) {
                          // Initialize a variable to store the combined visit count
                          $combined_visit_count = 0;

                          // Define the LIKE pattern
                          $likePattern = "%book%";

                          foreach ($book_ids as $book_id) {
                              // Bind the parameters
                              $stmt->bind_param("ssss", $likePattern, $book_id, $start_date, $end_date);

                              // Execute the query
                              $stmt->execute();

                              // Bind the result and fetch
                              $stmt->bind_result($visit_count);
                              $stmt->fetch();

                              // Add the visit count to the combined total
                              $combined_visit_count += $visit_count;
                          }

                          // Close the prepared statement
                          $stmt->close();

                          $books = $combined_visit_count;

                          // Output the combined visit count
                          // echo $combined_visit_count;
                          if ($_SESSION['PLAN'] === 'deluxe') {
                            echo $combined_visit_count;
                        } else {
                            echo '<span class="blurred" title="Subscribe to unlock full stats">' . $visit_count . '</span>';
                        }

                      } else {
                          echo "0";
                      }
                  } else {
                      echo "0";
                  }


                  ?>




                  </div>
                  <div class="text fz14"><span class="text-thm"><?php

                    // Define the USERID you want to filter by
                    $user_id = $_SESSION['ADMIN_USERKEY'];

                    // Prepare the SQL query to select CONTENTID based on USERID
                    $query = "SELECT CONTENTID FROM posts WHERE USERID = ?";
                    $stmt = $conn->prepare($query);

                    if ($stmt) {
                        // Bind the USERID parameter and execute the query
                        $stmt->bind_param("s", $user_id);
                        $stmt->execute();

                        // Get the results into an array
                        $result = $stmt->get_result();
                        $book_ids = $result->fetch_all(MYSQLI_ASSOC);

                        // Close the prepared statement
                        $stmt->close();

                        // Extract CONTENTID values and format as an array
                        $book_ids = array_column($book_ids, 'CONTENTID');

                        // Check if there are book IDs
                        if (!empty($book_ids)) {
                            // Prepare the SQL query to count unique users based on IPs within the date range
                            $query = "SELECT COUNT(DISTINCT user_ip) AS unique_user_count
                                      FROM page_visits AS pv
                                      INNER JOIN posts AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
                                      WHERE pv.page_url LIKE ? AND p.CONTENTID IN (" . implode(',', array_fill(0, count($book_ids), '?')) . ")
                                      AND DATE(pv.visit_time) BETWEEN ? AND ?"; // Adjust the date format if needed

                            $stmt = $conn->prepare($query);

                            if ($stmt) {
                                // Initialize a variable to store the unique user count
                                $unique_user_count = 0;

                                // Define the LIKE pattern
                                $likePattern = "%book%";

                                // Bind parameters for IN clause (book IDs)
                                $params = array_merge(array($likePattern), $book_ids, array($start_date, $end_date));
                                $paramTypes = str_repeat("s", count($params));
                                $stmt->bind_param($paramTypes, ...$params);

                                // Execute the query
                                $stmt->execute();

                                // Bind the result to a variable
                                $stmt->bind_result($unique_user_count);

                                // Fetch the result
                                $stmt->fetch();

                                // Close the prepared statement
                                $stmt->close();

                                // Output the total number of unique users based on their IPs
                                echo 'Based on ' . $unique_user_count;
                            } else {
                                echo "0";
                            }
                        } else {
                            echo "0";
                        }
                    } else {
                        echo "0";
                    }
                    ?>

                  </span> Users</div>
                </div>
                <div class="icon text-center"><i class="flaticon-success"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Events Views</div>
                  <div class="title"><?php
                  // Define the USERID you want to filter by
                  $user_id = $_SESSION['ADMIN_USERKEY'];


                  // Prepare the SQL query to select CONTENTID based on USERID
                  $query = "SELECT CONTENTID FROM events WHERE USERID = ?";
                  $stmt = $conn->prepare($query);

                  if ($stmt) {
                      // Bind the USERID parameter and execute the query
                      $stmt->bind_param("s", $user_id);
                      $stmt->execute();

                      // Get the results into an array
                      $result = $stmt->get_result();
                      $book_ids = $result->fetch_all(MYSQLI_ASSOC);

                      // Close the prepared statement
                      $stmt->close();

                      // Extract CONTENTID values and format as an array
                      $book_ids = array_column($book_ids, 'CONTENTID');

                      // Prepare the SQL query to count page visits within the date range
                      $query = "SELECT COUNT(*) AS visit_count
                                FROM page_visits AS pv
                                INNER JOIN events AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
                                WHERE pv.page_url LIKE ? AND p.CONTENTID = ?
                                AND DATE(pv.visit_time) BETWEEN ? AND ?"; // Adjust the date format if needed

                      $stmt = $conn->prepare($query);

                      if ($stmt) {
                          // Initialize a variable to store the combined visit count
                          $combined_visit_count = 0;

                          // Define the LIKE pattern
                          $likePattern = "%event-details%";

                          foreach ($book_ids as $book_id) {
                              // Bind the parameters
                              $stmt->bind_param("ssss", $likePattern, $book_id, $start_date, $end_date);

                              // Execute the query
                              $stmt->execute();

                              // Bind the result and fetch
                              $stmt->bind_result($visit_count);
                              $stmt->fetch();

                              // Add the visit count to the combined total
                              $combined_visit_count += $visit_count;
                          }

                          // Close the prepared statement
                          $stmt->close();

                          $events = $combined_visit_count;

                          // Output the combined visit count
                          echo $combined_visit_count;
                      } else {
                          echo "0";
                      }
                  } else {
                      echo "0";
                  }


                  ?>
                </div>
                  <div class="text fz14"><span class="text-thm"><?php

                    // Define the USERID you want to filter by
                    $user_id = $_SESSION['ADMIN_USERKEY'];

                    // Prepare the SQL query to select CONTENTID based on USERID
                    $query = "SELECT CONTENTID FROM events WHERE USERID = ?";
                    $stmt = $conn->prepare($query);

                    if ($stmt) {
                        // Bind the USERID parameter and execute the query
                        $stmt->bind_param("s", $user_id);
                        $stmt->execute();

                        // Get the results into an array
                        $result = $stmt->get_result();
                        $book_ids = $result->fetch_all(MYSQLI_ASSOC);

                        // Close the prepared statement
                        $stmt->close();

                        // Extract CONTENTID values and format as an array
                        $book_ids = array_column($book_ids, 'CONTENTID');

                        // Check if there are book IDs
                        if (!empty($book_ids)) {
                            // Prepare the SQL query to count unique users based on IPs within the date range
                            $query = "SELECT COUNT(DISTINCT user_ip) AS unique_user_count
                                    FROM page_visits AS pv
                                    INNER JOIN events AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
                                    WHERE pv.page_url LIKE ? AND p.CONTENTID IN (" . implode(',', array_fill(0, count($book_ids), '?')) . ")
                                    AND DATE(pv.visit_time) BETWEEN ? AND ?"; // Adjust the date format if needed

                            $stmt = $conn->prepare($query);

                            if ($stmt) {
                                // Initialize a variable to store the unique user count
                                $unique_user_count = 0;

                                // Define the LIKE pattern
                                $likePattern = "%event%";

                                // Bind parameters for IN clause (book IDs)
                                $params = array_merge(array($likePattern), $book_ids, array($start_date, $end_date));
                                $paramTypes = str_repeat("s", count($params));
                                $stmt->bind_param($paramTypes, ...$params);

                                // Execute the query
                                $stmt->execute();

                                // Bind the result to a variable
                                $stmt->bind_result($unique_user_count);

                                // Fetch the result
                                $stmt->fetch();

                                // Close the prepared statement
                                $stmt->close();

                                // Output the total number of unique users based on their IPs
                                echo 'Based on ' . $unique_user_count;
                            } else {
                                echo "0";
                            }
                        } else {
                            echo "0";
                        }
                    } else {
                        echo "0";
                    }
                    ?>

                  </span> Users</div>
                </div>
                <div class="icon text-center"><i class="flaticon-review"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Profile Views</div>
                  <div class="title"><?php
                    // Define the keyword to search for in the page_url
                    $keyword = $_SESSION['ADMIN_USERKEY'];

                    // Define the start and end dates for your date range

                    // Execute a SQL query to count page visits where page_url contains the keyword and falls within the specified date range
                    $stmt = $conn->prepare("SELECT COUNT(*) AS visit_count
                                          FROM page_visits
                                          WHERE page_url LIKE ? AND visit_time BETWEEN ? AND ?");
                    $stmt->bind_param("sss", $likePattern, $start_date, $end_date);

                    // Define the LIKE pattern to search for the keyword in page_url
                    $likePattern = "%$keyword%";

                    $stmt->execute();

                    // Bind the result to a variable
                    $stmt->bind_result($visit_count);

                    // Fetch the result
                    $stmt->fetch();

                    $profile = $visit_count;

                    // Output the visit count
                    echo $visit_count;

                    // Close the prepared statement
                    $stmt->close();
                    ?>
                    </div>
                  <div class="text fz14"><span class="text-thm"><?php

                    // Define the keyword to search for in the page_url
                    $keyword = $_SESSION['ADMIN_USERKEY'];

                    // Prepare the SQL query to count unique users based on IPs within the date range
                    $query = "SELECT COUNT(DISTINCT user_ip) AS unique_user_count
                              FROM page_visits
                              WHERE page_url LIKE ? AND DATE(visit_time) BETWEEN ? AND ?";
                    $stmt = $conn->prepare($query);

                    if ($stmt) {
                        // Define the LIKE pattern to search for the keyword in page_url
                        $likePattern = "%$keyword";

                        // Bind the parameters
                        $stmt->bind_param("sss", $likePattern, $start_date, $end_date);

                        // Execute the query
                        $stmt->execute();

                        // Bind the result to a variable
                        $stmt->bind_result($unique_user_count);

                        // Fetch the result
                        $stmt->fetch();

                        // Output the total number of unique users based on their IPs for profile visits
                        echo 'Based on ' . $unique_user_count;

                        // Close the prepared statement
                        $stmt->close();
                    } else {
                        echo "0";
                    }
                    ?>

                    </span> Users</div>
                </div>
                <div class="icon text-center"><i class="flaticon-review-1"></i></div>
              </div>
            </div>
          </div>


          
  
          <div class="row">
            <div class="col-xl-4 d-none">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="navtab-style1">
                  <div class="d-sm-flex align-items-center justify-content-between">
                    <h4 class="title fz17 mb20">Profile Views</h4>
                    <div class="page_control_shorting dark-color pr10 text-center text-md-end">
                      <select class="selectpicker show-tick">
                        <option>This Week</option>
                        <option>This Month</option>
                        <option>This Year</option>
                      </select>
                    </div>
                  </div>
                  <canvas id="myChartweave" style="height:230px;"></canvas>
                </div>
              </div>
            </div>
            <div class="col-xl-4">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb50">
                  <h5 class="title">Traffic Analysis</h5>
                </div>
                <canvas id="myChart" height="200px"></canvas>
              </div>
            </div>

            <div class="col-xl-8">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb50">
                  <h5 class="title">Traffic By Provinces</h5>
                </div>
                <canvas id="myDevice" height="100px"></canvas>
              </div>
            </div>
          </div>

          <?php 
          // Calculate the total sum of all variables
          $total = $services + $events + $profile + $books + $store;

          if($books == 0 && $profile == 0 && $events == 0 && $services == 0 ){
            $services_percentage = 0;
            $events_percentage = 0;
            $profile_percentage = 0;
            $books_percentage = 0;
            $store_percentage = 0;
          } else {

              // Calculate the percentages and round them to two decimal places
              $services_percentage = round(($services / $total) * 100, 0);
              $events_percentage = round(($events / $total) * 100, 0);
              $profile_percentage = round(($profile / $total) * 100, 0);
              $books_percentage = round(($books / $total) * 100, 0);
              $store_percentage = round(($store / $total) * 100, 0);


          }

        
          
          ?>


          <div class="row" id="pageContent">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="navtab-style1">
                  <nav>
                    <div class="nav nav-tabs mb30" id="nav-tab2" role="tablist">
                      <button class="nav-link active fw500 ps-0" id="nav-item1-tab" data-bs-toggle="tab" data-bs-target="#nav-item1" type="button" role="tab" aria-controls="nav-item1" aria-selected="true">Book Views</button>
                      <button class="nav-link fw500" id="nav-item2-tab" data-bs-toggle="tab" data-bs-target="#nav-item2" type="button" role="tab" aria-controls="nav-item2" aria-selected="false">Events Views</button>
                      <button class="nav-link fw500" id="nav-item3-tab" data-bs-toggle="tab" data-bs-target="#nav-item3" type="button" role="tab" aria-controls="nav-item3" aria-selected="false">Profile Views</button>
                      <button class="nav-link fw500" id="nav-item4-tab" data-bs-toggle="tab" data-bs-target="#nav-item4" type="button" role="tab" aria-controls="nav-item4" aria-selected="false">Service Views</button>
                      <button class="nav-link fw500" id="nav-item5-tab" data-bs-toggle="tab" data-bs-target="#nav-item5" type="button" role="tab" aria-controls="nav-item5" aria-selected="false">Books Store Views</button>
                    </div>
                  </nav>
                  <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-item1" role="tabpanel" aria-labelledby="nav-item1-tab">
                      <div class="packages_table table-responsive">
                        <table class="table-style3 table at-savesearch">
                          <thead class="t-head">
                            <tr>
                              <th scope="col">Page Url</th>
                              <th scope="col">Device</th>
                              <th scope="col">Referer</th>
                              <th scope="col">Date</th>
                              <th scope="col">Country</th>
                              <th scope="col">City</th>
                              <th scope="col">Province</th>
                            </tr>
                          </thead>
                          <tbody class="t-body">

                          <?php
                        // Include your database connection code here (e.g., using mysqli).

                        // Define the USERID you want to filter by
                        $user_id = $_SESSION['ADMIN_USERKEY'];

                        // Define the start and end date for your date range (replace with actual dates)
                        // Calculate the start date (30 days ago from today)

                        // Prepare the SQL query to select CONTENTID based on USERID
                        $query = "SELECT CONTENTID FROM posts WHERE USERID = ?";
                        $stmt = $conn->prepare($query);

                        if ($stmt) {
                            // Bind the USERID parameter and execute the query
                            $stmt->bind_param("s", $user_id);
                            $stmt->execute();

                            // Get the results into an array
                            $result = $stmt->get_result();
                            $book_ids = $result->fetch_all(MYSQLI_ASSOC);

                            // Close the prepared statement
                            $stmt->close();

                            // Initialize an array to store the selected data
                            $selected_data = [];

                            // Define the LIKE pattern
                            $likePattern = "%book%";

                            foreach ($book_ids as $book_id) {
                                // Prepare the SQL query to retrieve data from page_visits
                                $query = "SELECT *
                                          FROM page_visits AS pv
                                          INNER JOIN posts AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
                                          WHERE pv.page_url LIKE ? AND p.CONTENTID = ?
                                          AND DATE(pv.visit_time) BETWEEN ? AND ?"; // Adjust the date format if needed

                                $stmt = $conn->prepare($query);

                                if ($stmt) {
                                    // Bind the parameters
                                    $stmt->bind_param("ssss", $likePattern, $book_id['CONTENTID'], $start_date, $end_date);

                                    // Execute the query
                                    $stmt->execute();

                                    // Get the results into an associative array
                                    $result = $stmt->get_result();
                                    
                                    // Fetch and store the data
                                    while ($row = $result->fetch_assoc()) {
                                        $selected_data[] = $row;
                                    }

                                    // Close the prepared statement
                                    $stmt->close();
                                }
                            }

                            // The string to search within
                            $string = "This is a sample string containing words.";

                            // The word you want to check for
                            $wordToCheck = "sample";

                            // Use strpos() to check if the word is found in the string

                            

                            // Output the selected data (you can format and display it as needed)
                            foreach ($selected_data as $data) {
                              $user_agent = $data['user_agent'];

                                if (strpos($user_agent, 'Android') !== false) {
                                    $user_agent = 'Android';
                                } elseif (strpos($user_agent, 'iPhone') !== false) {
                                    $user_agent = 'iPhone';
                                } elseif (strpos($user_agent, 'Windows') !== false) {
                                    $user_agent = 'Windows PC';
                                } elseif (strpos($user_agent, 'Macintosh') !== false) {
                                    $user_agent = 'Macbook PC';
                                } else {
                                    $user_agent = 'Unspecified';
                                }
                                
                                echo '<tr>
                                <td class="align-top"><span class="fz15 fw400">'. $data['page_url'] .'</span></td>
                                <td class="align-top"><span class="fz15 fw400">'. $user_agent .'</span></td>
                                <td class="align-top"><span class="fz15 fw400"><a href="'. $data['referer'] .'">'. $data['referer'] .'</a></span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['visit_time'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['user_country'] .'</span></td>
                                 <td class="align-top"><span class="fz14 fw400">'. $data['user_city'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['user_province'] .'</span></td>
                              </tr>';
                                // Output other data fields as needed
                            }
                        } else {
                            echo "0";
                        }
                        ?>

                            
                          </tbody>
                        </table>
                        <div class="mbp_pagination text-center mt30 d-none">
                          <ul class="page_navigation">
                            <li class="page-item">
                              <a class="page-link" href="#"> <span class="fas fa-angle-left"></span></a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active" aria-current="page">
                              <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <li class="page-item"><a class="page-link" href="#">20</a></li>
                            <li class="page-item">
                              <a class="page-link" href="#"><span class="fas fa-angle-right"></span></a>
                            </li>
                          </ul>
                          <p class="mt10 mb-0 pagination_page_count text-center">1 – 20 of 300+ property available</p>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="nav-item2" role="tabpanel" aria-labelledby="nav-item2-tab">
                      <div class="packages_table table-responsive">
                        <table class="table-style3 table at-savesearch">
                        <thead class="t-head">
                            <tr>
                              <th scope="col">Page Url</th>
                              <th scope="col">Device</th>
                              <th scope="col">Referer</th>
                              <th scope="col">Date</th>
                              <th scope="col">Country</th>
                               <th scope="col">City</th>
                              <th scope="col">Province</th>
                            </tr>
                          </thead>
                          <tbody class="t-body">

                          <?php
                        // Include your database connection code here (e.g., using mysqli).

                        // Define the USERID you want to filter by
                        $user_id = $_SESSION['ADMIN_USERKEY'];

                        // Define the start and end date for your date range (replace with actual dates)
                        // Calculate the start date (30 days ago from today)

                        // Prepare the SQL query to select CONTENTID based on USERID
                        $query = "SELECT CONTENTID FROM events WHERE USERID = ?";
                        $stmt = $conn->prepare($query);

                        if ($stmt) {
                            // Bind the USERID parameter and execute the query
                            $stmt->bind_param("s", $user_id);
                            $stmt->execute();

                            // Get the results into an array
                            $result = $stmt->get_result();
                            $book_ids = $result->fetch_all(MYSQLI_ASSOC);

                            // Close the prepared statement
                            $stmt->close();

                            // Initialize an array to store the selected data
                            $selected_data = [];

                            // Define the LIKE pattern
                            $likePattern = "%event-details%";

                            foreach ($book_ids as $book_id) {
                                // Prepare the SQL query to retrieve data from page_visits
                                $query = "SELECT *
                                          FROM page_visits AS pv
                                          INNER JOIN events AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
                                          WHERE pv.page_url LIKE ? AND p.CONTENTID = ?
                                          AND DATE(pv.visit_time) BETWEEN ? AND ?"; // Adjust the date format if needed

                                $stmt = $conn->prepare($query);

                                if ($stmt) {
                                    // Bind the parameters
                                    $stmt->bind_param("ssss", $likePattern, $book_id['CONTENTID'], $start_date, $end_date);

                                    // Execute the query
                                    $stmt->execute();

                                    // Get the results into an associative array
                                    $result = $stmt->get_result();
                                    
                                    // Fetch and store the data
                                    while ($row = $result->fetch_assoc()) {
                                        $selected_data[] = $row;
                                    }

                                    // Close the prepared statement
                                    $stmt->close();
                                }
                            }

                            // The string to search within
                            $string = "This is a sample string containing words.";

                            // The word you want to check for
                            $wordToCheck = "sample";

                            // Use strpos() to check if the word is found in the string

                            

                            // Output the selected data (you can format and display it as needed)
                            foreach ($selected_data as $data) {
                              $user_agent = $data['user_agent'];

                                if (strpos($user_agent, 'Android') !== false) {
                                    $user_agent = 'Android';
                                } elseif (strpos($user_agent, 'iPhone') !== false) {
                                    $user_agent = 'iPhone';
                                } elseif (strpos($user_agent, 'Windows') !== false) {
                                    $user_agent = 'Windows PC';
                                } elseif (strpos($user_agent, 'Macintosh') !== false) {
                                    $user_agent = 'Macbook PC';
                                } else {
                                    $user_agent = 'Unspecified';
                                }
                                
                                echo '<tr>
                                <td class="align-top"><span class="fz15 fw400">'. $data['page_url'] .'</span></td>
                                <td class="align-top"><span class="fz15 fw400">'. $user_agent .'</span></td>
                                <td class="align-top"><span class="fz15 fw400"><a href="'. $data['referer'] .'">'. $data['referer'] .'</a></span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['visit_time'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['user_country'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['user_city'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['user_province'] .'</span></td>
                              </tr>';
                                // Output other data fields as needed
                            }
                        } else {
                            echo "0";
                        }
                        ?>


                            
                          </tbody>
                        </table>
                        
                      </div>
                    </div>
                    <div class="tab-pane fade" id="nav-item3" role="tabpanel" aria-labelledby="nav-item3-tab">
                      <div class="packages_table table-responsive">
                        <table class="table-style3 table at-savesearch">
                        <thead class="t-head">
                            <tr>
                              <th scope="col">Page Url</th>
                              <th scope="col">Device</th>
                              <th scope="col">Referer</th>
                              <th scope="col">Date</th>
                              <th scope="col">Country</th>
                               <th scope="col">City</th>
                              <th scope="col">Province</th>
                            </tr>
                          </thead>
                          <tbody class="t-body">

                          <?php
                        // Define the USERID you want to filter by
                        $user_id = $_SESSION['ADMIN_USERKEY'];

                        // Define the start and end dates for your date range

                        // Execute a SQL query to select data from page_visits where the USERID is found in the page_url and falls within the specified date range
                        $stmt = $conn->prepare("SELECT *
                                              FROM page_visits
                                              WHERE page_url LIKE ? AND visit_time BETWEEN ? AND ?");
                        $likePatternUserId = "%$user_id%";
                        $stmt->bind_param("sss", $likePatternUserId, $start_date, $end_date);

                        $stmt->execute();

                        // Get the results into a variable
                        $result = $stmt->get_result();

                        // Output the data (you can format and display it as needed)
                        while ($row = $result->fetch_assoc()) {

                          $user_agent = $data['user_agent'];

                                if (strpos($user_agent, 'Android') !== false) {
                                    $user_agent = 'Android';
                                } elseif (strpos($user_agent, 'iPhone') !== false) {
                                    $user_agent = 'iPhone';
                                } elseif (strpos($user_agent, 'Windows') !== false) {
                                    $user_agent = 'Windows PC';
                                } elseif (strpos($user_agent, 'Macintosh') !== false) {
                                    $user_agent = 'Macbook PC';
                                } else {
                                    $user_agent = 'Unspecified';
                                }

                            echo '<tr>
                            <td class="align-top"><span class="fz15 fw400">'. $row['page_url'] .'</span></td>
                            <td class="align-top"><span class="fz15 fw400">'. $user_agent .'</span></td>
                            <td class="align-top"><span class="fz15 fw400"><a href="'. $row['referer'] .'">'. $row['referer'] .'</a></span></td>
                            <td class="align-top"><span class="fz14 fw400">'. $row['visit_time'] .'</span></td>
                            <td class="align-top"><span class="fz14 fw400">'. $row['user_country'] .'</span></td>
                             <td class="align-top"><span class="fz14 fw400">'. $data['user_city'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['user_province'] .'</span></td>
                          </tr>';
                        }

                        // Close the prepared statement
                        $stmt->close();
                        ?>


                            
                          </tbody>
                        </table>
                        
                      </div>
                    </div>
                    <div class="tab-pane fade" id="nav-item4" role="tabpanel" aria-labelledby="nav-item4-tab">
                      <div class="packages_table table-responsive">
                        <table class="table-style3 table at-savesearch">
                          <thead class="t-head">
                            <tr>
                              <th scope="col">Page Url</th>
                              <th scope="col">Device</th>
                              <th scope="col">Referer</th>
                              <th scope="col">Date</th>
                              <th scope="col">Country</th>
                               <th scope="col">City</th>
                              <th scope="col">Province</th>
                            </tr>
                          </thead>
                          <tbody class="t-body">
                           
                          <?php
                            // Define the keyword to search for in the page_url (e.g., "profile")
                            $keyword = $provider_id;

                            // Define the start and end dates for your date range

                            // Execute a SQL query to select data from page_visits where page_url contains the keyword and falls within the specified date range
                            $stmt = $conn->prepare("SELECT *
                                                  FROM page_visits
                                                  WHERE page_url LIKE ? AND visit_time BETWEEN ? AND ?");
                            $likePattern = "%$keyword%";
                            $stmt->bind_param("sss", $likePattern, $start_date, $end_date);

                            $stmt->execute();

                            // Get the results into a variable
                            $result = $stmt->get_result();

                            // Output the data (you can format and display it as needed)
                            while ($row = $result->fetch_assoc()) {

                              $user_agent = $data['user_agent'];

                                if (strpos($user_agent, 'Android') !== false) {
                                    $user_agent = 'Android';
                                } elseif (strpos($user_agent, 'iPhone') !== false) {
                                    $user_agent = 'iPhone';
                                } elseif (strpos($user_agent, 'Windows') !== false) {
                                    $user_agent = 'Windows PC';
                                } elseif (strpos($user_agent, 'Macintosh') !== false) {
                                    $user_agent = 'Macbook PC';
                                } else {
                                    $user_agent = 'Unspecified';
                                }

                                echo '<tr>
                                <td class="align-top"><span class="fz15 fw400">'. $row['page_url'] .'</span></td>
                                <td class="align-top"><span class="fz15 fw400">'. $user_agent  .'</span></td>
                                <td class="align-top"><span class="fz15 fw400"><a href="'. $row['referer'] .'">'. $data['referer'] .'</a></span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $row['visit_time'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $row['user_country'] .'</span></td>
                                 <td class="align-top"><span class="fz14 fw400">'. $data['user_city'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['user_province'] .'</span></td>
                              </tr>';
                            }

                            // Close the prepared statement
                            $stmt->close();
                            ?>

                          </tbody>
                        </table>  
                        
                      </div>
                    </div>
                    <div class="tab-pane fade" id="nav-item5" role="tabpanel" aria-labelledby="nav-item5-tab">
                      <div class="packages_table table-responsive">
                        <table class="table-style3 table at-savesearch">
                        <thead class="t-head">
                            <tr>
                              <th scope="col">Page Url</th>
                              <th scope="col">Device</th>
                              <th scope="col">Referer</th>
                              <th scope="col">Date</th>
                              <th scope="col">Country</th>
                              <th scope="col">City</th>
                               <th scope="col">City</th>
                              <th scope="col">Province</th>
                            </tr>
                          </thead>
                          <tbody class="t-body">
                           
                          <?php
                        // Include your database connection code here (e.g., using mysqli).

                        // Define the USERID you want to filter by
                        $user_id = $_SESSION['ADMIN_USERKEY'];

                        // Define the start and end date for your date range (replace with actual dates)
                        // Calculate the start date (30 days ago from today)

                        // Prepare the SQL query to select CONTENTID based on USERID
                        $query = "SELECT ID FROM book_stores WHERE USERID = ?";
                        $stmt = $conn->prepare($query);

                        if ($stmt) {
                            // Bind the USERID parameter and execute the query

                            $stmt->bind_param("s", $user_id);
                            $stmt->execute();

                            // Get the results into an array
                            $result = $stmt->get_result();
                            $book_ids = $result->fetch_all(MYSQLI_ASSOC);

                            // Close the prepared statement
                            $stmt->close();

                            // Initialize an array to store the selected data
                            $selected_data = [];

                            // Define the LIKE pattern
                            $likePattern = "%store%";

                            foreach ($book_ids as $book_id) {
                                // Prepare the SQL query to retrieve data from page_visits
                                $query = "SELECT *
                                          FROM page_visits AS pv
                                          INNER JOIN book_stores AS p ON pv.page_url LIKE CONCAT('%', p.ID, '%') 
                                          WHERE pv.page_url LIKE ? AND p.ID = ?
                                          AND DATE(pv.visit_time) BETWEEN ? AND ?"; // Adjust the date format if needed

                                $stmt = $conn->prepare($query);

                                if ($stmt) {
                                    // Bind the parameters
                                    $stmt->bind_param("ssss", $likePattern, $book_id['ID'], $start_date, $end_date);

                                    // Execute the query
                                    $stmt->execute();

                                    // Get the results into an associative array
                                    $result = $stmt->get_result();
                                    
                                    // Fetch and store the data
                                    while ($row = $result->fetch_assoc()) {
                                        $selected_data[] = $row;
                                    }

                                    // Close the prepared statement
                                    $stmt->close();
                                }
                            }


                            // Output the selected data (you can format and display it as needed)
                            foreach ($selected_data as $data) {
                              $user_agent = $data['user_agent'];

                                if (strpos($user_agent, 'Android') !== false) {
                                    $user_agent = 'Android';
                                } elseif (strpos($user_agent, 'iPhone') !== false) {
                                    $user_agent = 'iPhone';
                                } elseif (strpos($user_agent, 'Windows') !== false) {
                                    $user_agent = 'Windows PC';
                                } elseif (strpos($user_agent, 'Macintosh') !== false) {
                                    $user_agent = 'Macbook PC';
                                } else {
                                    $user_agent = 'Unspecified';
                                }
                                
                                echo '<tr>
                                <td class="align-top"><span class="fz15 fw400">'. $data['page_url'] .'</span></td>
                                <td class="align-top"><span class="fz15 fw400">'. $user_agent .'</span></td>
                                <td class="align-top"><span class="fz15 fw400"><a href="'. $data['referer'] .'">'. $data['referer'] .'</a></span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['visit_time'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['user_country'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['user_city'] .'</span></td>
                                <td class="align-top"><span class="fz14 fw400">'. $data['user_province'] .'</span></td>
                              </tr>';
                                // Output other data fields as needed
                            }
                        } else {
                            echo "0";
                        }
                        ?>

                          </tbody>
                        </table>
                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div id="results"></div>
        </div>
        <?php include 'includes/footer.php';?>
      </div>
    </div>
  </div>
  <a class="scrollToHome" href="#"><i class="fas fa-angle-up"></i></a>
</div>
<!-- Wrapper End -->
<!-- Wrapper End -->
<script src="js/jquery-3.6.4.min.js"></script> 
<script src="js/jquery-migrate-3.0.0.min.js"></script> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/bootstrap-select.min.js"></script> 
<script src="js/jquery.mmenu.all.js"></script> 
<script src="js/ace-responsive-menu.js"></script> 
<script src="js/chart.min.js"></script>
<script src="js/chart-custome.js"></script>
<script src="js/jquery-scrolltofixed-min.js"></script>
<script src="js/dashboard-script.js"></script>
<!-- Custom script for all pages --> 
<script src="js/script.js"></script>


<script>
        function printPage() {
            window.print(); // Opens the browser's print dialog
        }
    </script>


<script>


// /* ----- Employee Dashboard Chart Js For Applications Statistics ----- */
// Circle Doughnut Chart
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Books: 0%', 'Events: 0%', 'Profile: 0%', 'Books: 0%', 'Books-Store: 0%'],
        datasets: [{
            label: 'My First Dataset',
            backgroundColor: ["#5BBB7B", "#FFEDE8", "#FBF7ED"],
            data: [0, 0, 0,0,0], // Adjust your data percentages accordingly
        }]
    },
    options: {
        cutoutPercentage: 60,
        responsive: true,
        legend: {
            position: 'left',
        },
    },
});

// /* ----- Employee Dashboard Chart Js For Applications Statistics ----- */
// Circle Doughnut Chart
// Define the text strings to count
const wordsToCount = ["Kwazulu-Natal", "Limpopo", "Eastern Cape", "North West", "Western Cape", "Northern Cape", "Mpumalanga", "Gauteng", "Free State"];

// Function to count occurrences of each word
function countWordOccurrences(text, word) {
    const regex = new RegExp(word, 'gi');
    const matches = text.match(regex);
    return matches ? matches.length : 0;
}

// Function to calculate percentages for each word
function calculateWordPercentages(text, words) {
    const totalWords = words.reduce((count, word) => count + countWordOccurrences(text, word), 0);
    const percentages = {};

    for (const word of words) {
        const count = countWordOccurrences(text, word);
        const percentage = ((count / totalWords) * 100).toFixed(2) + '%';
        percentages[word] = percentage;
    }

    return percentages;
}

// Get all text content on the page
const pageText = document.body.innerText;

// Calculate percentages for each word
const wordPercentages = calculateWordPercentages(pageText, wordsToCount);

// Store percentages in separate variables
const kznPercentage = wordPercentages["Kwazulu-Natal"];
const lpPercentage = wordPercentages["Limpopo"];
const ecPercentage = wordPercentages["Eastern Cape"];
const nwPercentage = wordPercentages["North West"];
const wcPercentage = wordPercentages["Western Cape"];
const ncPercentage = wordPercentages["Northern Cape"];
const mpPercentage = wordPercentages["Mpumalanga"];
const gpPercentage = wordPercentages["Gauteng"];
const fsPercentage = wordPercentages["Free State"];

var kzn = kznPercentage.replace(/%/g, '');
var lp = lpPercentage.replace(/%/g, '');
var ec = ecPercentage.replace(/%/g, '');
var nw = nwPercentage.replace(/%/g, '');
var wc = wcPercentage.replace(/%/g, '');
var nc = ncPercentage.replace(/%/g, '');
var mp = mpPercentage.replace(/%/g, '');
var gp = gpPercentage.replace(/%/g, '');
var fs = fsPercentage.replace(/%/g, '');


var ctx = document.getElementById('myDevice').getContext('2d');
var chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['KwaZulu-Natal: '+kzn, 'Limpopo: '+lp, 'Eastern Cape: '+ec, 'North West: '+nw, 'Western Cape: '+wc, 'Northern Cape: '+nc, 'Mpumalanga: '+mp, 'Gauteng: '+gp, 'Free State: '+fs],
        datasets: [{
            label: 'SA Provinces',
            backgroundColor: ["#5BBB7B", "#FFEDE8", "#FBF7ED"],
            data: [ kzn,  lp, ec, nw, wc, nc, mp, gp, fs], // Adjust your data percentages accordingly
        }]
    },
    options: {
        cutoutPercentage: 60,
        responsive: true,
        legend: {
            position: 'left',
        },
    },
});




// LineChart Style 2
var ctx = document.getElementById('myChartweave').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels: ["Jan 1", "Jan 2", "Jan 3", "Jan 4", "Jan 5", "Jan 6", "Jan 7", "Jan 8", "Jan 9", "Jan 10"],
        // Information about the dataset
        datasets: [{
            label: "Traffic Data",
            backgroundColor: 'rgba(251, 247, 237, 0.9)',
            borderColor: '#5BBB7B',
            data: [800, 1000, 950, 1200, 1300, 1100, 1400, 1500, 1400, 1600],
        }]
    },

    // Configuration options
    options: {
        layout: {
            padding: 10,
        },
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Traffic Data in Toronto (Daily)',
        },
        scales: {
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Traffic Volume',
                },
                ticks: {
                    min: 0,
                    max: 2000,
                    stepSize: 200,
                },
            }],
            xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Date',
                },
            }],
        },
    },
});




</script>

<!-- Add this script after including Shepherd.js -->
 <!-- Add the Shepherd.js script here -->
<!-- <script>
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
                text: 'Welcome! This is the first element.',
                attachTo: { element: '#element1', on: 'right' },
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

            tour.addStep({
                id: 'step2',
                text: 'Here is the second element.',
                attachTo: { element: '#element2', on: 'top' },
                classes: 'example-step-extra-class custom-step-content', // Add custom step class
                buttons: [
                    {
                        text: 'Skip',
                        action: tour.cancel,
                    },
                    {
                        text: 'Done',
                        action: tour.complete,
                    },
                ],
            });

            // Start the tour
            tour.start();
        });
    </script>-->


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
        text: '<b>Book Listings:</b><hr> You can upload, edit and manage your book listings.',
        attachTo: { element: '#element1', on: 'right' },
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

    tour.addStep({
        id: 'step2',
        text: '<b>Events:</b><hr> You can upload, edit and manage your events listings.',
        attachTo: { element: '#element2', on: 'right' },
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
        id: 'step3',
        text: '<b>Services:</b><hr> You can create, edit and manage your services.',
        attachTo: { element: '#element3', on: 'right' },
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
        id: 'step4',
        text: '<b>Reviews:</b><hr> You can view and manage your reviews from Events, Books & services.',
        attachTo: { element: '#element4', on: 'right' },
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
        id: 'step5',
        text: '<b>Website:</b><hr> You can create and manage your website. Update Shipping fee, Payment details & description of your website.',
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
        id: 'step6',
        text: '<b>Customers:</b><hr> You can view and manage your clients from your website.',
        attachTo: { element: '#element6', on: 'right' },
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
        id: 'step7',
        text: '<b>Orders:</b><hr> You can manage and view orders from your website created using SA Books Online.',
        attachTo: { element: '#element7', on: 'right' },
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
        id: 'step8',
        text: '<b>Billing:</b><hr> You can view your payment history and download invoices for your subscription.',
        attachTo: { element: '#element8', on: 'right' },
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
        id: 'step9',
        text: '<b>Subscriptions:</b><hr> You can view your current and switch your subscription plan.',
        attachTo: { element: '#element9', on: 'right' },
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
        text: '<b>Profile:</b><hr> In this section you can update your profile.',
        attachTo: { element: '#element10', on: 'right' },
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