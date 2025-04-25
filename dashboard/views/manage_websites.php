<?php
session_start();
require_once '../../includes/database_connections/sabooks_plesk.php';
require_once '../controllers/ListingsController.php';

$userKey = $_SESSION['ADMIN_USERKEY'];
$subscription = $_SESSION['ADMIN_SUBSCRIPTION'];

$controller = new ListingsController($mysqli);
$accounts = $controller->getAccounts($userKey);
$viewToInclude = $controller->getViewToInclude($userKey, $subscription);

// Include dynamic view
include $viewToInclude;
?>

<tbody>
<?php
if (count($accounts) === 0) {
    echo "<div class='alert alert-info border-none'>You currently have no content uploaded. <a href='dashboaord-add-book'>Add New Book</a>.</div>";
} else {
    foreach ($accounts as $rows) {
        // Extract variables
        $id = $rows['ID'];
        $domain = $rows['DOMAIN'];
        $created = $rows['CREATED'];
        $accountName = $rows['ACCOUNT_NAME'];
        $status = $rows['STATUS'];

        // Status class assignment
        $statusClass = match (strtolower($status)) {
            'active' => 'bg-success',
            'service locked' => 'bg-danger',
            'pending' => 'bg-warning',
            'draft' => 'bg-info',
            default => 'bg-secondary',
        };

        // Status cell
        $statusCell = '<td class="vam"><span class="pending-style style1 ' . $statusClass . ' text-white">' . ucwords($status) . '</span></td>';

        // Edit button logic
        $serviceBtn = ($status !== "Service Locked") ? 
            '<a href="edit-website?domain=' . $domain . '" class="icon me-2 text-" data-bs-toggle="tooltip" title="Edit"><span class="flaticon-pencil"></span></a>' : '';

        // Deletion type
        $type = (strpos($domain, '.sabooksonline.co.za') !== false) ? 'delete_subdomain' : 'delete_customer';

        echo <<<HTML
<tr>
  <th class="dashboard-img-service" scope="row">
    <div class="listing-style1 list-style d-block d-xl-flex align-items-start border-0 mb-0">
      <div class="list-content flex-grow-1 py-0 pl15 pl0-lg">
        <h6 class="list-title mb-0"><a href="page-services-single.html">{$accountName}</a></h6>
      </div>
    </div>
  </th>
  <td class="align-top"><span class="fz15 fw400"><a class="text-success" href="https://{$domain}/" target="_blank">{$domain} <i class="fa fa-external-link"></i></a></span></td>
  <td class="align-top"><span class="fz14 fw400">{$created}</span></td>
  {$statusCell}
  <td class="align-top">
    <div class="d-flex">
      {$serviceBtn}
      <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalWebsite{$id}" class="icon me-2"><span class="flaticon-delete"></span></a>
      <a href="http://41.76.111.78/plesk-site-preview/{$domain}/https/41.76.111.78/" target="_blank" class="icon"><span class="flaticon-website"></span></a>
    </div>
  </td>
  <td class="vam">
    <a href="https://{$domain}/" target="_blank" class="table-action fz15 fw500 text-thm2"><span class="flaticon-website me-2 vam"></span> Visit Site</a>
  </td>
</tr>

<!-- Modal HTML -->
<div class="modal fade" id="exampleModalWebsite{$id}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-none">
      <div class="modal-header">
        <h5 class="modal-title">Are You Sure You want to delete the book?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        You are about to delete <b>{$domain}</b>. This action is irreversible.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancel</button>
        <a href="#" class="btn btn-danger text-white {$type}" data-contentid="{$domain}">Continue</a>
      </div>
    </div>
  </div>
</div>
HTML;
    }
}
?>
</tbody>