<?php
session_start();
include '../../includes/database_connections/sabooks.php';
require_once '../controllers/ServiceController.php';

$controller = new ListingsController($conn);
$userId = $_SESSION['ADMIN_USERKEY'];
$services = $controller->listServices($userId);
?>

<?php include '../includes/header-dash-main.php'; ?>
<div class="dashboard_content_wrapper">
  <div class="dashboard dashboard_wrapper pr30 pr0-xl">
    <?php include '../includes/header-dash.php'; ?>

    <div class="dashboard__main pl0-md">
      <div class="dashboard__content hover-bgc-color">
        <div class="row pb40">
          <?php include '../includes/mobile-guide.php'; ?>
          
          <!-- Success/Failure Alerts -->
          <?php include '../includes/service-messages.php'; ?>

          <div class="col-lg-9">
            <div class="dashboard_title_area">
              <h2>Manage Services</h2>
              <p class="text">You can manage, add or delete your service listings.</p>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="text-lg-end">
              <a href="add-service" class="ud-btn btn-dark default-box-shadow2">Add New Service<i class="fal fa-arrow-right-long"></i></a>
              <div id="reg_statu"></div>
            </div>
          </div>
        </div>

        <!-- Service Table -->
        <div class="row">
          <div class="col-xl-12">
            <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
              <div class="packages_table table-responsive">
                <table class="table-style3 table at-savesearch">
                  <thead class="t-head">
                    <tr>
                      <th>Service</th><th>Min.Price</th><th>Max.Price</th><th>Status</th><th>Action</th>
                    </tr>
                  </thead>
                  <tbody class="t-body">
                    <?php if (mysqli_num_rows($services) > 0): ?>
                      <?php while ($row = mysqli_fetch_assoc($services)): ?>
                        <?php
                          $badge = match(strtolower($row['STATUS'])) {
                            "active" => "bg-success",
                            "service locked" => "bg-danger",
                            "pending" => "bg-warning",
                            "draft" => "bg-info",
                            default => "bg-secondary"
                          };

                          $editable = strtolower($row['STATUS']) !== 'service locked';
                        ?>
                        <tr>
                          <th>
                            <div class="freelancer-style1 p-0 mb-0 box-shadow-none">
                              <div class="d-lg-flex align-items-lg-center">
                                <div class="details ml15 ml0-md mb15-md">
                                  <h5 class="title mb-2"><?= $row['SERVICE'] ?></h5>
                                </div>
                              </div>
                            </div>
                          </th>
                          <td><b>R<?= $row['MINIMUM'] ?></b></td>
                          <td><b>R<?= $row['MAXIMUM'] ?></b></td>
                          <td><span class="pending-style style1 <?= $badge ?> text-white"><?= ucwords($row['STATUS']) ?></span></td>
                          <td>
                            <div class="d-flex">
                              <?php if ($editable): ?>
                                <a href="edit-service?contentid=<?= $row['ID'] ?>" class="icon me-2 text-success" data-bs-toggle="tooltip" title="Edit"><span class="flaticon-pencil"></span></a>
                              <?php endif; ?>
                              <a data-bs-toggle="modal" data-bs-target="#exampleModal<?= $row['ID'] ?>" class="icon" title="Delete"><span class="flaticon-delete"></span></a>
                            </div>
                          </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="exampleModal<?= $row['ID'] ?>" tabindex="-1">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">Are You Sure?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body">
                                You are about to delete <b><?= $row['SERVICE'] ?></b>. This action cannot be undone.
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <a href="#" class="btn btn-danger delete_item" data-contentid="<?= $row['ID'] ?>">Continue</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr><td colspan="5">No services found. <a href="add-service">Add New</a></td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
                <div id="domain_status"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php include '../includes/footer.php'; ?>
    </div>
  </div>
</div>
