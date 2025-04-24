<?php
// Ensure $analyticsData is available as an associative array
// Example: $analyticsData = json_decode(file_get_contents('php://input'), true);
?>

<div class="row">
  <!-- Financial Stats -->
  <div class="col-sm-6 col-xxl-3">
    <div class="d-flex align-items-center justify-content-between statistics_funfact">
      <div class="details">
        <div class="fz15">Net Income</div>
        <div class="title">R<?= $analyticsData['netIncome'] ?></div>
      </div>
      <div class="icon text-center"><i class="flaticon-income"></i></div>
    </div>
  </div>

  <div class="col-sm-6 col-xxl-3">
    <div class="d-flex align-items-center justify-content-between statistics_funfact">
      <div class="details">
        <div class="fz15">Transactions</div>
        <div class="title"><?= $analyticsData['totalTransactions'] ?></div>
      </div>
      <div class="icon text-center"><i class="flaticon-withdraw"></i></div>
    </div>
  </div>

  <div class="col-sm-6 col-xxl-3">
    <div class="d-flex align-items-center justify-content-between statistics_funfact">
      <div class="details">
        <div class="fz15">Total Customers</div>
        <div class="title"><?= $analyticsData['totalCustomers'] ?></div>
      </div>
      <div class="icon text-center"><i class="flaticon-review"></i></div>
    </div>
  </div>

  <div class="col-sm-6 col-xxl-3">
    <div class="d-flex align-items-center justify-content-between statistics_funfact">
      <div class="details">
        <div class="fz15">Pending Orders</div>
        <div class="title"><?= $analyticsData['pendingOrders'] ?></div>
      </div>
      <div class="icon text-center"><i class="flaticon-review-1"></i></div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Book Store -->
  <div class="col-sm-6 col-xxl-3 book-status">
    <div class="d-flex align-items-center justify-content-between statistics_funfact">
      <div class="details">
        <div class="fz15">Book Store</div>
        <div class="title"><?= $analyticsData['bookViews'] ?></div>
        <div class="text fz14"><span class="text-thm"><?= $analyticsData['uniqueBookUsers'] ?></span> Users</div>
      </div>
      <div class="icon text-center"><i class="flaticon-contract"></i></div>
    </div>
  </div>

  <!-- Service Views -->
  <div class="col-sm-6 col-xxl-3">
    <div class="d-flex align-items-center justify-content-between statistics_funfact">
      <div class="details">
        <div class="fz15">Services Views</div>
        <div class="title"><?= $analyticsData['eventViews'] ?></div>
        <div class="text fz14"><span class="text-thm"><?= $analyticsData['uniqueEventUsers'] ?></span> Users</div>
      </div>
      <div class="icon text-center"><i class="flaticon-contract"></i></div>
    </div>
  </div>

  <!-- Book Views -->
  <div class="col-sm-6 col-xxl-3">
    <div class="d-flex align-items-center justify-content-between statistics_funfact">
      <div class="details">
        <div class="fz15">Book Views</div>
        <div class="title"><?= $analyticsData['bookViews'] ?></div>
        <div class="text fz14"><span class="text-thm"><?= $analyticsData['uniqueBookUsers'] ?></span> Users</div>
      </div>
      <div class="icon text-center"><i class="flaticon-success"></i></div>
    </div>
  </div>

  <!-- Event Views -->
  <div class="col-sm-6 col-xxl-3">
    <div class="d-flex align-items-center justify-content-between statistics_funfact">
      <div class="details">
        <div class="fz15">Events Views</div>
        <div class="title"><?= $analyticsData['eventViews'] ?></div>
        <div class="text fz14"><span class="text-thm"><?= $analyticsData['uniqueEventUsers'] ?></span> Users</div>
      </div>
      <div class="icon text-center"><i class="flaticon-review"></i></div>
    </div>
  </div>

  <!-- Profile Views (Static or from another model?) -->
  <div class="col-sm-6 col-xxl-3">
    <div class="d-flex align-items-center justify-content-between statistics_funfact">
      <div class="details">
        <div class="fz15">Profile Views</div>
        <div class="title">Coming Soon</div>
        <div class="text fz14"><span class="text-thm">0</span> Users</div>
      </div>
      <div class="icon text-center"><i class="flaticon-review-1"></i></div>
    </div>
  </div>
</div>
