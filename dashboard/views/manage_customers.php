<?php
include '../../includes/database_connections/sabooks_user.php';
include '../controllers/ListingsController.php';

$userController = new ListingsController($con);
$users = $userController->listCustomers();
?>

<div class="wrapper">
  <div class="preloader"></div>

  <?php include '../includes/header-dash-main.php';?>

  <div class="dashboard_content_wrapper">
    <div class="dashboard dashboard_wrapper pr30 pr0-xl">
      <?php include '../includes/header-dash.php';?>

      <div class="dashboard__main pl0-md">
        <div class="dashboard__content hover-bgc-color">
          <div class="row pb40">
            <?php include '../includes/mobile-guide.php';?>
            <div class="col-lg-12">
              <div class="dashboard_title_area">
                <h2>Customers</h2>
                <p class="text">You can manage, add or delete your customers/users.</p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="packages_table table-responsive">
                  <table class="table-style3 table at-savesearch">
                    <thead class="t-head">
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Number</th>
                        <th scope="col">Address</th>
                      </tr>
                    </thead>
                    <tbody class="t-body">

                    <?php if (empty($users)): ?>
                      <div class='alert alert-info border-none'>You currently have no content uploaded.</div>
                    <?php else: ?>
                      <?php foreach ($users as $user): ?>
                        <tr>
                          <th scope="row"><?= ucwords($user['first_name'] . ' ' . $user['last_name']) ?></th>
                          <td class="vam"><span class="pending-style style4"><?= ucwords($user['email']) ?></span></td>
                          <td class="vam"><?= ucwords($user['mobile']) ?></td>
                          <td class="vam"><?= ucwords($user['address1']) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
        <?php include '../includes/footer.php';?>
      </div>
    </div>
  </div>

  <a class="scrollToHome" href="#"><i class="fas fa-angle-up"></i></a>
</div>
