<?php
$servicesPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalServices = count($services);
$totalPages = ceil($totalServices / $servicesPerPage);
$startIndex = ($currentPage - 1) * $servicesPerPage;

$servicesToShow = array_slice($services, $startIndex, $servicesPerPage);
$startCount = $startIndex + 1;
$endCount = min($startIndex + $servicesPerPage, $totalServices);
?>

<h5 class="mb-3">Showing <?= $startCount ?>–<?= $endCount ?> of <?= $totalServices ?> matching services</h5>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        Services per page:
        <form method="get" class="d-inline">
            <select name="limit" class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="5" <?= $servicesPerPage == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $servicesPerPage == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $servicesPerPage == 25 ? 'selected' : '' ?>>25</option>
            </select>
            <input type="hidden" name="page" value="1">
        </form>
    </div>
    <div><?= $startCount ?>–<?= $endCount ?> of <?= $totalServices ?> services</div>
</div>

<table class="table table-bordered table-hover align-middle table-responsive table-bordered rounded">
    <thead class="table-light">
        <tr>
            <th>Service Name</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($servicesToShow)): ?>
            <tr>
                <td colspan="6" class="text-center">No services available.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($servicesToShow as $service): ?>
                <tr>
                    <td>
                        <a href="/dashboards/services/<?= $service["ID"] ?>">
                            <?= htmlspecialchars($service['SERVICE']) ?>
                        </a>
                    </td>
                    <td>R<?= number_format($service['MINIMUM'], 2) ?> - <?= number_format($service['MAXIMUM'], 2) ?></td>
                    <td>
                        <span class="badge <?= $service['STATUS'] === 'Active' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= htmlspecialchars($service['STATUS']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="/dashboards/services/delete/<?= $service['ID'] ?>"
                            class="btn btn-sm btn-danger">
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
            <a class="page-link" href="?page=<?= $currentPage - 1 ?>&limit=<?= $servicesPerPage ?>">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&limit=<?= $servicesPerPage ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $currentPage + 1 ?>&limit=<?= $servicesPerPage ?>">Next</a>
        </li>
    </ul>
</nav>