<?php

function renderUserTable($users): void
{
    // Collect unique subscription types for the dropdown
    $subscriptions = array_unique(array_map(fn($u) => $u['subscription_status'], $users));
?>
    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 flex-nowrap">
            <input
                type="text"
                id="userSearch"
                class="form-control"
                placeholder="Search by name, email, or subscription..."
                style="width: 280px;">

            <select
                id="subscriptionFilter"
                class="form-select"
                style="width: 200px;">
                <option value="">All Subscriptions</option>
                <?php foreach ($subscriptions as $sub): ?>
                    <option value="<?= htmlspecialchars($sub) ?>"><?= htmlspecialchars($sub) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button id="downloadCSV" class="btn btn-success">
                <i class="fas fa-file-csv me-1"></i> Download CSV
            </button>

            <button id="printPDF" class="btn btn-dark">
                <i class="fas fa-print me-1"></i> Print PDF
            </button>
        </div>
    </div>



    <div class="mb-3" style="height: calc(100vh - 455px); overflow-x: auto;">
        <table class="table table-hover table-striped align-middle border rounded shadow-sm" id="userTable">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Subscription</th>
                    <th scope="col">Status</th>
                    <th scope="col">Joined</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user["ADMIN_ID"] ?></td>
                        <td><?= $user["ADMIN_NAME"] ?></td>
                        <td><?= $user["ADMIN_EMAIL"] ?></td>
                        <td><?= $user["subscription_status"] ?></td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                        <td><?= $user["ADMIN_DATE"] ?></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm action-col" role="group">
                                <a href="/creators/creator/<?= urlencode($user["ADMIN_USERKEY"]) ?>"
                                    target="_blank"
                                    class="btn btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="/admin/users/impersonate/<?= urlencode($user["ADMIN_ID"]) ?>"
                                    onclick="return confirm('Are you sure you want to login as <?= $user['ADMIN_NAME'] ?>?')"
                                    class="btn btn-outline-primary">
                                    <i class="fas fa-sign-in-alt"></i>
                                </a>

                                <a href="/admin/users/delete/<?= urlencode($user['ADMIN_ID']) ?>"
                                    onclick="return confirm(
                                       'Are you sure you want to delete the following user?\n\n' +
                                       'Name: <?= addslashes($user['ADMIN_NAME']) ?>\n' +
                                       'Email: <?= addslashes($user['ADMIN_EMAIL']) ?>\n' +
                                       'Subscription: <?= addslashes($user['subscription_status']) ?>\n\n' +
                                       'This action cannot be undone.'
                                   );"
                                    class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="pagination"></ul>
    </nav>

    <script>
        const rowsPerPage = 10;
        let currentPage = 1;

        const searchInput = document.getElementById('userSearch');
        const subscriptionFilter = document.getElementById('subscriptionFilter');
        const table = document.getElementById('userTable');
        const tbody = table.querySelector('tbody');
        const pagination = document.getElementById('pagination');

        function displayTable() {
            const searchValue = searchInput.value.toLowerCase();
            const selectedSubscription = subscriptionFilter.value.toLowerCase();

            const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                const subscription = (cells[3]?.textContent || '').toLowerCase();

                const matchesSearch = cells.some(td =>
                    td.textContent.toLowerCase().includes(searchValue)
                );
                const matchesSubscription = !selectedSubscription || subscription === selectedSubscription;

                return matchesSearch && matchesSubscription;
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

            pagination.appendChild(createPageItem(currentPage - 1, 'Previous', currentPage === 1));

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
                    pagination.appendChild(createPageItem(p, p, false, currentPage === p));
                }
            });

            pagination.appendChild(createPageItem(currentPage + 1, 'Next', currentPage === totalPages));
        }

        // Listeners
        searchInput.addEventListener('keyup', () => {
            currentPage = 1;
            displayTable();
        });

        subscriptionFilter.addEventListener('change', () => {
            currentPage = 1;
            displayTable();
        });

        // CSV export
        document.getElementById('downloadCSV').addEventListener('click', () => {
            const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.style.display !== 'none');
            let csv = [];

            const headers = Array.from(table.querySelectorAll('thead th'))
                .map(th => `"${th.textContent.trim()}"`);
            csv.push(headers.join(','));

            rows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td'))
                    .map(td => `"${td.textContent.trim().replace(/"/g, '""')}"`);
                csv.push(cells.join(','));
            });

            const blob = new Blob([csv.join('\n')], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `users_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        document.getElementById('printPDF').addEventListener('click', () => {
            // Clone table to include only visible rows (filtered)
            const tableClone = table.cloneNode(true);
            const cloneTbody = tableClone.querySelector('tbody');
            Array.from(cloneTbody.querySelectorAll('tr')).forEach(row => {
                if (row.style.display === 'none') {
                    row.remove();
                }
            });

            // Open a new window and print
            const printWindow = window.open('', '', 'width=900,height=700');
            printWindow.document.write('<html><head><title>Users Table</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">');
            printWindow.document.write('<style>table {width: 100%; border-collapse: collapse;} th, td {border: 1px solid #ddd; padding: 8px;} th {background-color: #f8f9fa;}</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(tableClone.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        });


        // Init
        displayTable();
    </script>
<?php
}
?>