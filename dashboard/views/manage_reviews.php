<?php
session_start();
include '../includes/database_connections/sabooks.php';
include '../controllers/ListingsController.php';

$userkey = $_SESSION['ADMIN_USERKEY'];
$reviewController = new ListingsController($conn);
$reviews = $reviewController->listReviews($userkey);
?>

<div class="wrapper">
  <div class="preloader"></div>

  <?php include 'includes/header-dash-main.php';?>

  <div class="dashboard_content_wrapper">
    <div class="dashboard dashboard_wrapper pr30 pr0-xl">
      <?php include 'includes/header-dash.php';?>

      <div class="dashboard__main pl0-md">
        <div class="dashboard__content hover-bgc-color">
          <div class="row pb40">
            <?php include 'includes/mobile-guide.php';?>

            <div class="col-lg-12">
              <div class="dashboard_title_area">
                <h2>Reviews</h2>
                <p class="text">You can manage, add or delete your reviews based on listings.</p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="packages_table table-responsive">
                  <div class="navtab-style1">
                    <nav>
                      <div class="nav nav-tabs mb20" id="nav-tab2" role="tablist">
                        <button class="nav-link active fw500 ps-0" id="nav-item1-tab" data-bs-toggle="tab" data-bs-target="#nav-item1" type="button" role="tab" aria-controls="nav-item1" aria-selected="true">All Reviews</button>
                      </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                      <div class="tab-pane fade show active" id="nav-item1" role="tabpanel" aria-labelledby="nav-item1-tab">
                        <?php if (empty($reviews)): ?>
                          <div class="alert alert-info b-none" style="border: none !important;">
                            No content for reviews yet
                          </div>
                        <?php else: ?>
                          <?php foreach ($reviews as $row): ?>
                            <?php
                              $reviewTitle = ucfirst($row['TITLE']);
                              $reviewReview = ucfirst($row['REVIEW']);
                              $reviewDate = ucfirst($row['DATEPOSTED']);
                              $reviewRating = ucfirst($row['RATING']);
                              $reviewUsername = ucfirst($row['USERNAME']);
                              $reviewid = $row['ID'];
                            ?>
                            <div class="col-md-12">
                              <div class="bdrb1 pb20">
                                <div class="mbp_first position-relative d-sm-flex align-items-center justify-content-start mb30-sm mt30">
                                  <img src="../img/avatar.jpg" class="mr-3" alt="comments-2.png" width="50px" style="border-radius:50%;">
                                  <div class="ml20 ml0-xs mt20-xs">
                                    <div class="del-edit"><span class="flaticon-flag"></span></div>
                                    <h6 class="mt-0 mb-1"><?= $reviewUsername ?></h6>
                                    <div class="d-flex align-items-center">
                                      <div><i class="fas fa-star vam fz10 review-color me-2"></i><span class="fz15 fw500"><?= $reviewRating ?></span></div>
                                      <div class="ms-3"><span class="fz14 text"><?= $reviewDate ?></span></div>
                                    </div>
                                  </div>
                                </div>
                                <p class="text mt20 mb20"><?= $reviewTitle ?></p>
                                <a href="#" data-contentid="<?= $reviewid ?>" class="ud-btn bgc-thm4 text-thm delete_item">Delete Review</a>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        <?php endif; ?>
                        <div id="domain_status"></div>
                      </div>
                    </div> <!-- tab-content -->
                  </div>
                </div> <!-- packages_table -->
              </div>
            </div>
          </div>

        </div> <!-- dashboard__content -->
      </div> <!-- dashboard__main -->
    </div> <!-- dashboard_wrapper -->
  </div> <!-- dashboard_content_wrapper -->
</div> <!-- wrapper -->
