<?php
require_once '../../includes/database_connections/sabooks.php';
require_once '../../controllers/ListingsController.php';

session_start();
$bookController = new ListingsController($conn);
$userkey = $_SESSION['ADMIN_USERKEY'];
$books = $bookController->listBooks($userkey);
?>

<?php include 'includes/header-dash-main.php'; ?>
<?php include 'includes/header-dash.php'; ?>

<div class="dashboard__content hover-bgc-color">
  <div class="row pb40">
    <?php include 'includes/mobile-guide.php'; ?>

    <?php if (isset($_GET['bookaddedsuccessfully'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Book uploaded successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['bookdeleted'])): ?>
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        Book deleted successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="col-lg-6">
      <div class="dashboard_title_area">
        <h2>Manage Book Listings</h2>
        <p class="text">You can manage, add or delete your book listings.</p>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3 ps-sm-0">
      <a href="uploadbook" class="ud-btn btn-white2"><i class="fal fa-plus-circle me-2"></i>Add Book</a>
    </div>
    <div class="col-sm-6 col-lg-3 ps-sm-0">
      <a href="uploadaudio" class="ud-btn btn-white2"><i class="fal fa-plus-circle me-2"></i>Add Audio</a>
    </div>

    <div class="col-xl-12">
      <div class="ps-widget bgc-white bdrs12 p30 mb30 overflow-hidden position-relative">
        <div class="packages_table table-responsive">
          <table class="table-style3 table at-savesearch">
            <thead class="t-head">
              <tr>
                <th scope="col">Title</th>
                <th scope="col">Price</th>
                <th scope="col">Created</th>
                <th scope="col">Stock</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody class="t-body">
              <?php while ($row = $books->fetch_assoc()): ?>
                <tr>
                  <th class="title" scope="row"><?= $row['TITLE'] ?></th>
                  <td class="price">R<?= $row['PRICE'] ?></td>
                  <td class="status"><?= $row['DATE'] ?></td>
                  <td class="status"><?= $row['STOCK'] ?></td>
                  <td class="status">
                    <?php if ($row['STATUS'] == "PENDING"): ?>
                      <span class="pending-style style2"><?= $row['STATUS'] ?></span>
                    <?php elseif ($row['STATUS'] == "ACTIVE"): ?>
                      <span class="active-style style2"><?= $row['STATUS'] ?></span>
                    <?php else: ?>
                      <span class="expired-style style2"><?= $row['STATUS'] ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="action">
                    <div class="d-flex">
                      <a href="editbook.php?postid=<?= $row['ID'] ?>" class="icon me-2" title="Edit"><span class="fas fa-pen"></span></a>
                      <a href="deletebook.php?postid=<?= $row['ID'] ?>" class="icon" title="Delete" onclick="return confirm('Are you sure you want to delete this book?');"><span class="fas fa-trash"></span></a>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer-dash.php'; ?>

//call it like this 
<?php
// Just include the actual view here
include 'views/dashboard/manage_books.php';
