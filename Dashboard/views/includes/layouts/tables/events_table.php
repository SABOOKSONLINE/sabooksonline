<?php
$eventsPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalEvents = count($events);
$totalPages = ceil($totalEvents / $eventsPerPage);
$startIndex = ($currentPage - 1) * $eventsPerPage;

$eventsToShow = array_slice($events, $startIndex, $eventsPerPage);
$startCount = $startIndex + 1;
$endCount = min($startIndex + $eventsPerPage, $totalEvents);
?>

<h5 class="mb-3">Showing <?= $startCount ?>–<?= $endCount ?> of <?= $totalEvents ?> matching events</h5>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        Events per page:
        <form method="get" class="d-inline">
            <select name="limit" class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="5" <?= $eventsPerPage == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $eventsPerPage == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $eventsPerPage == 25 ? 'selected' : '' ?>>25</option>
            </select>
            <input type="hidden" name="page" value="1">
        </form>
    </div>
    <div><?= $startCount ?>–<?= $endCount ?> of <?= $totalEvents ?> events</div>
</div>


<table class="table table-bordered table-hover align-middle table-responsive table-bordered rounded">
    <thead class="table-light">
        <tr>
            <th>Title</th>
            <th>Date & Time</th>
            <th>Venue</th>
            <th>Status</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($eventsToShow)): ?>
            <tr>
                <td colspan="6" class="text-center">No events available.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($eventsToShow as $event): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://sabooksonline.co.za/cms-data/event-covers/<?= htmlspecialchars($event['COVER'] ?? '') ?>"
                                class="me-2 rounded shadow-sm"
                                alt="<?= htmlspecialchars(($event["TITLE"] ?? '') . ' event Cover') ?>"
                                width="75" height="50">
                            <div>
                                <a href="/dashboards/events/<?= $event["ID"] ?? '' ?>">
                                    <?= htmlspecialchars($event['TITLE'] ?? 'Untitled Event') ?>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><?= htmlspecialchars($event['EVENTDATE'] ?? 'N/A') ?></div>
                        <small class="text-muted"><?= htmlspecialchars($event['EVENTTIME'] ?? 'N/A') ?></small>
                    </td>
                    <td><?= htmlspecialchars($event['VENUE'] ?? 'N/A') ?></td>
                    <td>
                        <span class="badge <?= ($event['STATUS'] ?? 'Inactive') === 'Active' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= htmlspecialchars($event['STATUS'] ?? 'Inactive') ?>
                        </span>
                    </td>
                    <td><?= $event['EVENTTYPE'] ?? 'N/A' ?></td>
                    <td>
                        <a href="/dashboards/events/delete/<?= $event['ID'] ?? '' ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure you want to delete this event?')">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $currentPage - 1 ?>&limit=<?= $eventsPerPage ?>">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&limit=<?= $eventsPerPage ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $currentPage + 1 ?>&limit=<?= $eventsPerPage ?>">Next</a>
        </li>
    </ul>
</nav>