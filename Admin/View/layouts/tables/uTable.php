<?php

function renderUserTable($users): void
{
?>
    <div class="mb-3">
        <input type="text" id="userSearch" class="form-control" placeholder="Search by name, email, or subscription...">
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
                        <td class="text-center">
                            <div class="btn-group btn-group-sm action-col" role="group">
                                <a href="/creators/creator/<?= urlencode($user["ADMIN_USERKEY"]) ?>"
                                    target="_blank"
                                    class="btn btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="/admin/users/impersonate/<?= urlencode($user["ADMIN_ID"]) ?>"
                                    onclick="return confirm('Are you sure you want to login as <?= $user['ADMIN_NAME'] ?>? (Note: You will be logged out of your current session.)')"
                                    class="btn btn-outline-primary">
                                    <i class="fas fa-sign-in-alt"></i>
                                </a>

                                <!-- <a href="admin/users/delete/<?= urlencode($user["ADMIN_ID"]) ?>"
                                    onclick="return confirm('Are you sure you want to delete <?= $user['ADMIN_NAME'] ?>?')"
                                    class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </a> -->
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
        const table = document.getElementById('userTable');
        const tbody = table.querySelector('tbody');
        const pagination = document.getElementById('pagination');

        function displayTable() {
            const filter = searchInput.value.toLowerCase();
            const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => {
                return Array.from(row.querySelectorAll('td')).some(td =>
                    td.textContent.toLowerCase().includes(filter)
                );
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
            pagination.appendChild(createPageItem(currentPage - 1, 'Previous', currentPage === 1));

            let pageList = [];
            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) pageList.push(i);
            } else {
                // Always show first and last
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

            // Next button
            pagination.appendChild(createPageItem(currentPage + 1, 'Next', currentPage === totalPages));
        }

        searchInput.addEventListener('keyup', () => {
            currentPage = 1;
            displayTable();
        });

        displayTable();
    </script>
<?php
}
?>