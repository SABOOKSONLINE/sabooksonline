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

            </div>

            <div class="col-lg-3 no-print">
              <div class="text-lg-end">
                <a href="#" class="ud-btn btn-dark default-box-shadow2" onclick="printPage()">Print To PDF<i class="fal fa-print"></i></a>
              </div>
            </div>
          </div>

          <form class="row" action="index">
          <div class="col-md-4"><label>Showing Data From: </label><input type="date" value="<?php echo $start_date;?>" class="form-control bdrs2" name="start"></div>
            
          <div class="col-md-4"><label> To: </label><input type="date" value="<?php echo $end_date;?>" class="form-control bdrs2" name="end"></div>
            
          <div class="col-md-4"><br><button type="submit" class="ud-btn btn-thm w-100">Update Analytics<i class="fal fa-arrow-right-long"></i></button></div>

          </form>
          <hr>
<div class="row">
<div class="col-sm-6 col-xxl-3">
  <div class="d-flex align-items-center justify-content-between statistics_funfact">
    <div class="details">
      <div class="fz15">Net Income</div>
      <div class="title">R<?php echo 0?>
      </div>
    </div>
    <div class="icon text-center"><i class="flaticon-income"></i></div>
  </div>
</div>
<div class="col-sm-6 col-xxl-3">
  <div class="d-flex align-items-center justify-content-between statistics_funfact">
    <div class="details">
      <div class="fz15">Transactions</div>
        <div class="title"><?php echo 0?>
        </div>
    </div>
    <div class="icon text-center"><i class="flaticon-withdraw"></i></div>
  </div>
</div>
<div class="col-sm-6 col-xxl-3">
  <div class="d-flex align-items-center justify-content-between statistics_funfact">
    <div class="details">
      <div class="fz15">Total Customers</div>
      <div class="title"><?php echo 0?>
        </div>
    </div>
    <div class="icon text-center"><i class="flaticon-review"></i></div>
  </div>
</div>
<div class="col-sm-6 col-xxl-3">
  <div class="d-flex align-items-center justify-content-between statistics_funfact">
    <div class="details">
      <div class="fz15">Pending Orders</div>
      <div class="title"><?php echo 0?>
      </div>
    </div>
    <div class="icon text-center"><i class="flaticon-review-1"></i></div>
  </div>
</div>
</div>
          <div class="row">
            <div class="col-sm-6 col-xxl-3 book-status">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Book Store</div>
                  <div class="title"><?php echo 0?>

                  </div>
                  <div class="text fz14"><span class="text-thm"><?php echo 0?>
                </span> Users</div>
                </div>
                <div class="icon text-center"><i class="flaticon-contract"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Services Views</div>
                  <div class="title"><?php echo "0" ?>
                  </div>
                  <div class="text fz14"><span class="text-thm"><?php echo "0"?>
                  </span> Users</div>
                </div>
                <div class="icon text-center"><i class="flaticon-contract"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Book Views</div>
                  <div class="title"><?php echo 0;?>
                  </div>
                  <div class="text fz14"><span class="text-thm"><?php echo "0";?>

                  </span> Users</div>
                </div>
                <div class="icon text-center"><i class="flaticon-success"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Events Views</div>
                  <div class="title"><?php echo "0";?>
                </div>
                  <div class="text fz14"><span class="text-thm"><?php echo "0";?>
                  </span> Users</div>
                </div>
                <div class="icon text-center"><i class="flaticon-review"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Profile Views</div>
                  <div class="title"><?php echo "0";?>
                    </div>
                  <div class="text fz14"><span class="text-thm"><?php echo "0";?>
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
                  
          ?>
                       
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