<?php
include '../includes/database_connections/sabooks_user.php';
require_once '../controllers/OrdersController.php';

$controller = new OrdersController($con);
?>
<div class="wrapper ovh">
      <?php include 'includes/mobile-guide.php'; ?>
      <?php include 'includes/sidebar.php'; ?>
      <div class="dashboard__content hover-bgc-color">
        <?php include 'includes/topnav.php'; ?>
        <div class="row pb40">
          <div class="col-lg-12">
            <div class="dashboard_title_area">
              <h2>Orders</h2>
              <p class="para">Monitor & manage order activity</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-xxl-3" id="income">
            <div class="d-flex align-items-center justify-content-between statistics_funfact">
              <div class="details">
                <div class="fz15">Net Income</div>
                <div class="title">R<?= $controller->netIncome ?></div>
              </div>
              <div class="icon text-center"><i class="flaticon-income"></i></div>
            </div>
          </div>
          <div class="col-sm-6 col-xxl-3" id="transactions">
            <div class="d-flex align-items-center justify-content-between statistics_funfact">
              <div class="details">
                <div class="fz15">Transactions</div>
                <div class="title"><?= $controller->transactions ?></div>
              </div>
              <div class="icon text-center"><i class="flaticon-withdraw"></i></div>
            </div>
          </div>
          <div class="col-sm-6 col-xxl-3" id="customers">
            <div class="d-flex align-items-center justify-content-between statistics_funfact">
              <div class="details">
                <div class="fz15">Total Customers</div>
                <div class="title"><?= $controller->totalCustomers ?></div>
              </div>
              <div class="icon text-center"><i class="flaticon-review"></i></div>
            </div>
          </div>
          <div class="col-sm-6 col-xxl-3" id="orders">
            <div class="d-flex align-items-center justify-content-between statistics_funfact">
              <div class="details">
                <div class="fz15">Pending Orders</div>
                <div class="title"><?= $controller->pendingOrders ?></div>
              </div>
              <div class="icon text-center"><i class="flaticon-review-1"></i></div>
            </div>
          </div>
        </div>
        <div class="row mt50 mb50">
          <div class="col-xl-12">
            <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
              <div class="bdrb1 pb15 mb25">
                <h5 class="list-title">Order List</h5>
              </div>
              <div class="dashboard-table table-responsive">
                <table class="table-style3 table at-savesearch">
                  <thead class="t-head">
                    <tr>
                      <th scope="col">Invoice #</th>
                      <th scope="col">User</th>
                      <th scope="col">Date</th>
                      <th scope="col">Total</th>
                      <th scope="col">Status</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="t-body">
                    <?php foreach ($controller->invoices as $invoice): ?>
                      <tr>
                        <th scope="row"><div>#<?= $invoice['number'] ?></div></th>
                        <td class="vam"><span class="pending-style style4"><?= $invoice['user_fullname'] ?></span></td>
                        <td class="vam"><?= $invoice['date'] ?></td>
                        <td class="vam">R<?= $invoice['total'] ?></td>
                        <td class="vam"><?= $invoice['status'] ?></td>
                        <td class="vam">
                          <a href="view-order-user?invoice=<?= $invoice['number'] ?>&invoiceid=<?= $invoice['user_id'] ?>" class="table-action fz15 fw500 text-thm2" data-bs-toggle="tooltip" data-bs-placement="top" title="View"><span class="flaticon-website me-2 vam"></span></a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <?php include 'includes/footer.php'; ?>
      </div>
    </div>