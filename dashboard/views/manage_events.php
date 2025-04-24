<?php
require_once '../../includes/database_connections/sabooks.php';
require_once '../../controllers/ListingsController.php';

session_start();
$controller = new ListingsController($conn);
$userkey = $_SESSION['ADMIN_USERKEY'];
$events = $controller->listEvents($userkey);

if (empty($events)) {
    echo "<div class='alert alert-info border-none'>You currently have no content uploaded. <a href='dashboaord-add-book'> Add New Book</a>.</div>";
} else {
    foreach ($events as $row) {
        $status = $row['STATUS'];
        $service_status = $row['STATUS'];
        $duration = $row['DURATION'];

        $duration_status = ($duration > 0) ? '- '.$duration.' Days Left' : '';

        if ($status == "Active" || $status == "active") {
            $statusCell = '<td class="vam"><span class="pending-style style1 bg-success text-white">'.ucwords($row['STATUS']).' '.$duration_status.'</span></td>';
        } else if ($status == "Service Locked") {
            $statusCell = '<td class="vam"><small class="pending-style style1 bg-danger text-white">'.ucwords($row['STATUS']).'</small></td>';
        } else if ($status == "Pending" || $status == "pending") {
            $statusCell = '<td class="vam"><span class="pending-style style1 bg-warning text-white">'.ucwords($row['STATUS']).'</span></td>';
        } else if ($status == "Draft") {
            $statusCell = '<td class="vam"><span class="pending-style style1 bg-info text-white">'.ucwords($row['STATUS']).'</span></td>';
        } else if ($status == "Complete") {
            $statusCell = '<td class="vam"><span class="pending-style style1 bg-secondary text-white">'.ucwords($row['STATUS']).' '.$duration_status.'</span></td>';
        }

        $editButton = ($service_status != "Service Locked" && $service_status != "Complete") 
            ? '<a href="edit-event?contentid='.$row['CONTENTID'].'" class="icon me-2 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="flaticon-pencil"></span></a>' 
            : '';

        echo '<tr>
            <th scope="row">
                <div class="freelancer-style1 p-0 mb-0 box-shadow-none">
                    <div class="d-lg-flex align-items-lg-center">
                        <div class="thumb 0 position-relative rounded-circle mb15-md">
                            <img class="mx-auto" src="https://sabooksonline.co.za/cms-data/event-covers/'.$row['COVER'].'" width="50px">
                            <span class="online-badge2"></span>
                        </div>
                        <div class="details ml15 ml0-md mb15-md">
                            <h5 class="title mb-2">'.ucwords($row['TITLE']).'</h5>
                            <p class="mb-0 fz14"><i class="flaticon-place fz16 vam text-thm2 me-1"></i> '.ucwords($row['VENUE']).'</p>
                            <p class="mb-0 fz14"><i class="flaticon-30-days fz16 vam text-thm2 me-1"></i> '.ucwords($row['EVENTDATE']).'</p>
                            <p class="mb-0 fz14"><i class="flaticon-contract fz16 vam text-thm2 me-1"></i> 0 Received</p>
                        </div>
                    </div>
                </div>
            </th>
            <td class="vam"><span class="fz15 fw400">'.ucwords($row['EVENTTYPE']).'</span></td>
            <td class="vam"><span>'.str_replace('00','20',$row['EVENTDATE']).'</span></td>
            '.$statusCell.'
            <td>
                <div class="d-flex">
                    '.$editButton.'
                    <a data-bs-toggle="modal" data-bs-target="#exampleModal'.$row['ID'].'" class="icon"><span class="flaticon-delete"></span></a>
                </div>
            </td>
        </tr>';

        echo '<div class="modal fade" id="exampleModal'.$row['ID'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered border-none">
                <div class="modal-content border-none">
                    <div class="modal-header">
                        <h5 class="modal-title">Are You Sure You want to delete the Event?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        You are about to delete <b>'.ucwords($row['TITLE']).'</b>, this action cannot be undone once complete.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancel</button>
                        <a href="#" type="button" class="btn btn-danger text-white delete_item" data-contentid="'.$row['ID'].'" data-locate="'.$row['COVER'].'">Continue <small class="icon_trash"></small></a>
                    </div>
                </div>
            </div>
        </div>';
    }
}
?>
