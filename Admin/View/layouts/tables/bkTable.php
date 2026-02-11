<?php

function renderBooksTable(array $books): void
{
    $categories = array_unique(array_map(fn($b) => $b['CATEGORY'], $books));
?>

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 flex-nowrap">
            <input
                type="text"
                id="bookSearch"
                class="form-control"
                placeholder="Search by title, ISBN, publisher..."
                style="width: 280px;">

            <select
                id="categoryFilter"
                class="form-select"
                style="width: 200px;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>">
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button id="downloadCSV" class="btn btn-success">
                <i class="fas fa-file-csv me-1"></i> Download CSV
            </button>

            <button onclick="window.print()" class="btn btn-dark">
                <i class="fas fa-print me-1"></i> Print PDF
            </button>
        </div>
    </div>

    <div class="mb-3" style="height: calc(100vh - 455px); overflow-x: auto;">
        <table class="table table-hover table-striped align-middle border rounded shadow-sm" id="bookTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>ISBN</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Posted</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= $book['ID'] ?></td>
                        <td><a target="_blank" href="/library/book/<?= $book['CONTENTID'] ?>" class="text-muted text-decoration-none"><?= $book['TITLE'] ?></a></td>
                        <td><?= $book['CATEGORY'] ?></td>
                        <td><?= htmlspecialchars($book['ISBN']) ?></td>
                        <td>R <?= number_format((float)$book['RETAILPRICE'], 2) ?></td>
                        <td>
                            <span class="badge bg-<?= strtolower($book['STATUS']) === 'active' ? 'primary' : 'danger' ?>">
                                <?= htmlspecialchars($book['STATUS']) ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $rawDate = $book['DATEPOSTED'] ?? $book['created_at'] ?? null;

                            if ($rawDate) {

                                $cleanDate = preg_replace('/\b(\d+)(st|nd|rd|th)\b/', '$1', $rawDate);

                                $cleanDate = str_replace(' of ', ' ', $cleanDate);

                                try {
                                    $date = new DateTime($cleanDate);

                                    $hasTime = preg_match('/\d{2}:\d{2}/', $rawDate);

                                    if ($hasTime) {
                                        echo $date->format('d M Y H:i');
                                    } else {
                                        echo $date->format('d M Y');
                                    }
                                } catch (Exception $e) {
                                    echo htmlspecialchars($rawDate);
                                }
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>

        </table>
    </div>

    <nav>
        <ul class="pagination justify-content-center" id="pagination"></ul>
    </nav>

    <script>
        const rowsPerPage = 10;
        let currentPage = 1;

        const searchInput = document.getElementById('bookSearch');
        const categoryFilter = document.getElementById('categoryFilter');
        const table = document.getElementById('bookTable');
        const tbody = table.querySelector('tbody');
        const pagination = document.getElementById('pagination');

        function displayTable() {
            const searchValue = searchInput.value.toLowerCase();
            const selectedCategory = categoryFilter.value.toLowerCase();

            const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                const category = (cells[2]?.textContent || '').toLowerCase();

                const matchesSearch = cells.some(td =>
                    td.textContent.toLowerCase().includes(searchValue)
                );

                const matchesCategory = !selectedCategory || category === selectedCategory;

                return matchesSearch && matchesCategory;
            });

            const totalPages = Math.ceil(rows.length / rowsPerPage);
            if (currentPage > totalPages) currentPage = totalPages || 1;

            tbody.querySelectorAll('tr').forEach(r => r.style.display = 'none');

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.slice(start, end).forEach(r => r.style.display = '');

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            pagination.innerHTML = '';

            const createPageItem = (page, text = page, disabled = false, active = false) => {
                const li = document.createElement('li');
                li.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;

                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = text;

                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (!disabled) {
                        currentPage = page;
                        displayTable();
                    }
                });

                li.appendChild(a);
                return li;
            };

            // Previous button
            pagination.appendChild(
                createPageItem(currentPage - 1, 'Previous', currentPage === 1)
            );

            let pageList = [];

            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) pageList.push(i);
            } else {
                pageList = [1];

                if (currentPage > 4) pageList.push('...');

                const start = Math.max(2, currentPage - 1);
                const end = Math.min(totalPages - 1, currentPage + 1);

                for (let i = start; i <= end; i++) pageList.push(i);

                if (currentPage + 1 < totalPages - 1) pageList.push('...');

                pageList.push(totalPages);
            }

            pageList.forEach(p => {
                if (p === '...') {
                    const li = document.createElement('li');
                    li.className = 'page-item disabled';
                    const a = document.createElement('a');
                    a.className = 'page-link';
                    a.textContent = '...';
                    li.appendChild(a);
                    pagination.appendChild(li);
                } else {
                    pagination.appendChild(
                        createPageItem(p, p, false, currentPage === p)
                    );
                }
            });

            // Next button
            pagination.appendChild(
                createPageItem(currentPage + 1, 'Next', currentPage === totalPages)
            );
        }


        searchInput.addEventListener('keyup', () => {
            currentPage = 1;
            displayTable();
        });

        categoryFilter.addEventListener('change', () => {
            currentPage = 1;
            displayTable();
        });

        document.getElementById('downloadCSV').addEventListener('click', () => {
            const rows = Array.from(tbody.querySelectorAll('tr'));

            let csv = [];
            const headers = Array.from(table.querySelectorAll('thead th'))
                .map(th => `"${th.textContent.trim()}"`);
            csv.push(headers.join(','));

            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cells = Array.from(row.querySelectorAll('td'))
                        .map(td => `"${td.textContent.trim().replace(/"/g, '""')}"`);
                    csv.push(cells.join(','));
                }
            });

            const blob = new Blob([csv.join('\n')], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `books_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        displayTable();
    </script>
<?php
}
?>