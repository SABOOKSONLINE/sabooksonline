<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/database_connections/sabooks.php';

$plan = $_SESSION['ADMIN_SUBSCRIPTION'];
$userkey = $_SESSION['ADMIN_USERKEY'];

// SQL to select data
$sql = "SELECT * FROM subscriptions WHERE PLAN = '$plan'";

// SQL query to count posts
$count_events = "SELECT COUNT(*) AS event_count FROM events WHERE USERID = '$userkey' AND STATUS = 'Active'";
$result_events = $conn->query($count_events);
$row_events = $result_events->fetch_assoc();
$count_events = $row_events['event_count'];

$count_priced = "SELECT COUNT(*) AS priced_count FROM posts WHERE USERID = '$userkey' AND STATUS = 'Active'";
$result_priced = $conn->query($count_priced);
$row_priced = $result_priced->fetch_assoc();
$count_priced = $row_priced['priced_count'];

$count_free = "SELECT COUNT(*) AS free_count FROM posts WHERE USERID = '$userkey' AND STATUS = 'Active'";
$result_free = $conn->query($count_free);
$row_free = $result_free->fetch_assoc();
$count_free = $row_free['free_count'];

$count_services = "SELECT COUNT(*) AS services_count FROM services WHERE USERID = '$userkey' AND STATUS = 'Active'";
$result_services = $conn->query($count_services);
$row_services = $result_services->fetch_assoc();
$count_services = $row_services['services_count'];

$count_emails = "SELECT COUNT(*) AS emails_count FROM plesk_emails WHERE EMAIL_USERID = '$userkey'";
$result_emails = $mysqli->query($count_emails);
$row_emails = $result_emails->fetch_assoc();
$count_emails = $row_emails['emails_count'];

$count_website = "SELECT COUNT(*) AS website_count FROM plesk_accounts WHERE USERKEY = '$userkey' AND STATUS = 'Active'";
$result_website = $mysqli->query($count_website);
$row_website = $result_website->fetch_assoc();
$count_website = $row_website['website_count'];


$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $subscription_status = true;
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $sub_id = $row["ID"];
        $sub_plan = $row["PLAN"];
        $sub_books = str_replace('1000000000','&infin;',$row["BOOKS"]);
        $sub_priced = str_replace('1000000000','&infin;',$row["PRICED"]);
        $sub_services = str_replace('1000000000','&infin;',$row["SERVICES"]);
        $sub_events = str_replace('1000000000','&infin;',$row["EVENTS"]);
        $sub_website = str_replace('1000000000','&infin;',$row["WEBSITE"]);
        $sub_emails = str_replace('1000000000','&infin;',$row["EMAILS"]);
        $sub_analytics = $row["ANALYTICS"];
        $sub_api = $row["API"];
        $sub_push = $row["PUSH"];
       
        echo '<div class="row">
        <div class="col-sm-6 col-xxl-4 d-none">
          <div class="d-flex align-items-center justify-content-between statistics_funfact">
            <div class="details">
              <div class="fz15">Gross Spent</div>
              <div class="title">R
                
              </div>
            </div>
            <div class="icon text-center"><i class="flaticon-income"></i></div>
          </div>
        </div>
        <div class="col-sm-6 col-xxl-2">
          <div class="d-flex align-items-center justify-content-between statistics_funfact">
            <div class="details">
              <div class="fz15">Events</div>
                <div class="title">
                  '.$count_events.'/'.$sub_events.'
                </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xxl-2">
          <div class="d-flex align-items-center justify-content-between statistics_funfact">
            <div class="details">
              <div class="fz15">Priced Books </div>
              <div class="title">'.$count_priced.'/'.$sub_priced.'</div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xxl-2">
          <div class="d-flex align-items-center justify-content-between statistics_funfact">
            <div class="details">
              <div class="fz15">Services</div>
              <div class="title">'.$count_services.'/'.$sub_services.'</div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-xxl-2">
          <div class="d-flex align-items-center justify-content-between statistics_funfact">
            <div class="details">
              <div class="fz15">Free Books</div>
              <div class="title">'.$count_free.'/&infin;</div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-xxl-2">
          <div class="d-flex align-items-center justify-content-between statistics_funfact">
            <div class="details">
              <div class="fz15">Emails</div>
              <div class="title">'.$count_emails.'/'.$sub_emails.'</div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-xxl-2">
          <div class="d-flex align-items-center justify-content-between statistics_funfact">
            <div class="details">
              <div class="fz15">Website</div>
              <div class="title">'.$count_website.'/1</div>
            </div>
          </div>
        </div>
      </div>';
    }
} else {
    $subscription_status = false;
}

?>
