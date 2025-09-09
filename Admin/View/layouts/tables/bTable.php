<?php
function renderBookTable(
    array $headers,
    array $rows,
    string $category
): string {
    $modalId = "modal_" . str_replace(' ', '_', $category);
    $buttonId = "books_table_" . str_replace(' ', '_', $category);

    $itemsPerPage = 10;
    $totalItems = count($rows);
    $totalPages = ceil($totalItems / $itemsPerPage);

    ob_start();
?>

    <div class="modal fade" id="<?= htmlspecialchars($modalId) ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><?= htmlspecialchars($category) ?> (<?= $totalItems ?> books)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="search-addon">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="searchInput_<?= htmlspecialchars($modalId) ?>"
                            class="form-control" placeholder="Search books..."
                            aria-label="Search" aria-describedby="search-addon">
                    </div>

                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-striped bordered">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <?php foreach ($headers as $header): ?>
                                        <th class=""><?= htmlspecialchars($header) ?></th>
                                    <?php endforeach; ?>
                                    <th class="">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody_<?= htmlspecialchars($modalId) ?>">
                                <?php foreach ($rows as $index => $row): ?>
                                    <tr data-index="<?= $index ?>" class="table-row">
                                        <?php foreach ($row as $cell): ?>
                                            <?php if (
                                                str_ends_with($cell, '.jpg')
                                                || str_ends_with($cell, '.png')
                                                || str_ends_with($cell, '.webp')
                                                || str_ends_with($cell, '.jpeg')
                                                || str_ends_with($cell, '.jfif')
                                            ): ?>
                                                <td class="text-center align-middle">
                                                    <img src="/cms-data/book-covers/<?= htmlspecialchars($cell) ?>"
                                                        alt="Image"
                                                        class="img-fluid rounded mx-auto d-block"
                                                        style="max-width: 60px;">
                                                </td>
                                            <?php else: ?>
                                                <td class="text-center align-middle">
                                                    <small><?= htmlspecialchars($cell) ?></small>
                                                </td>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <td class="text-center align-middle">
                                            <form action="/admin/process?book=<?= $row["CONTENTID"] ?>&type=insert&return=<?= $_SERVER["REQUEST_URI"] ?>" method="post">
                                                <input type="hidden" name="public_key" value="<?= $row["CONTENTID"] ?>">
                                                <input type="hidden" name="category" value="<?= $category ?>">
                                                <button class="btn btn-sm btn-outline-dark">
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Simple Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Page navigation" class="mt-3">
                            <ul class="pagination pagination-sm justify-content-center">
                                <li class="page-item" id="prevPage_<?= $modalId ?>">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <li class="page-item active" data-page="1">
                                    <a class="page-link" href="#">1</a>
                                </li>

                                <?php if ($totalPages >= 2): ?>
                                    <li class="page-item" data-page="2">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($totalPages >= 3): ?>
                                    <li class="page-item" data-page="3">
                                        <a class="page-link" href="#">3</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($totalPages > 3): ?>
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">...</a>
                                    </li>

                                    <li class="page-item" data-page="<?= $totalPages ?>">
                                        <a class="page-link" href="#"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>

                                <li class="page-item" id="nextPage_<?= $modalId ?>">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalId = "<?= htmlspecialchars($modalId) ?>";
            const searchInput = document.getElementById('searchInput_' + modalId);
            const tableBody = document.getElementById('tableBody_' + modalId);
            const rows = tableBody.getElementsByTagName('tr');
            const itemsPerPage = <?= $itemsPerPage ?>;
            let currentPage = 1;

            updatePagination();

            searchInput.addEventListener('keyup', function() {
                const searchText = this.value.toLowerCase();
                let visibleRows = 0;

                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let found = false;

                    for (let j = 0; j < cells.length - 1; j++) {
                        const cellText = cells[j].textContent.toLowerCase() || cells[j].innerText.toLowerCase();
                        if (cellText.indexOf(searchText) > -1) {
                            found = true;
                            break;
                        }
                    }

                    if (found) {
                        rows[i].style.display = '';
                        visibleRows++;
                    } else {
                        rows[i].style.display = 'none';
                    }
                }

                if (searchText !== '') {
                    document.querySelector('.pagination').style.display = 'none';
                } else {
                    document.querySelector('.pagination').style.display = 'flex';
                    updatePagination();
                }
            });

            document.querySelectorAll(`#${modalId} .page-link`).forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const parentLi = this.parentElement;

                    if (parentLi.id === `prevPage_${modalId}` && currentPage > 1) {
                        currentPage--;
                    } else if (parentLi.id === `nextPage_${modalId}` && currentPage < <?= $totalPages ?>) {
                        currentPage++;
                    } else if (parentLi.dataset.page) {
                        currentPage = parseInt(parentLi.dataset.page);
                    }

                    updatePagination();
                });
            });

            function updatePagination() {
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;

                for (let i = 0; i < rows.length; i++) {
                    if (i >= startIndex && i < endIndex) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }

                document.querySelectorAll(`#${modalId} .page-item`).forEach(item => {
                    item.classList.remove('active');
                    if (item.dataset.page && parseInt(item.dataset.page) === currentPage) {
                        item.classList.add('active');
                    }
                });

                document.getElementById(`prevPage_${modalId}`).classList.toggle('disabled', currentPage === 1);
                document.getElementById(`nextPage_${modalId}`).classList.toggle('disabled', currentPage === <?= $totalPages ?>);
            }
        });
    </script>

<?php
    return ob_get_clean();
}
?>