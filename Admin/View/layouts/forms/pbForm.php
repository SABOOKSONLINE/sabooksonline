<?php

function renderAddPublisherForm(array $users = []): void
{
?>
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-plus me-2 text-primary"></i>
                Add New Publisher
            </h5>
        </div>

        <div class="card-body p-4">

            <form action="/admin/books/upload-management/process?type=add-publisher&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>" method="POST">

                <!-- User Search/Selection -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Select User <span class="text-danger">*</span>
                    </label>

                    <div class="position-relative">
                        <input type="text"
                            id="userSearchInput"
                            class="form-control"
                            placeholder="Search by name, email, or user ID..."
                            autocomplete="off"
                            required>

                        <!-- Dropdown options -->
                        <div id="userDropdown" class="position-absolute w-100 bg-white border rounded mt-1 shadow-sm" style="display: none; max-height: 300px; overflow-y: auto; z-index: 1000; top: 100%;">
                            <div id="userOptions"></div>
                        </div>
                    </div>

                    <!-- Hidden select for form submission -->
                    <select name="user_select"
                        id="userSelect"
                        style="display: none;">
                        <option value="">-- Select a user --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= htmlspecialchars($user['user_id']) ?>"
                                data-email="<?= htmlspecialchars($user['email']) ?>"
                                data-name="<?= htmlspecialchars($user['name']) ?>">
                                <?= htmlspecialchars($user['name']) ?>
                                (<?= htmlspecialchars($user['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <small class="text-muted d-block mt-2">
                        Only Premium, Pro, or Royalties users are eligible.
                    </small>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="user_id" id="userId">
                <input type="hidden" name="email" id="userEmail">
                <input type="hidden" name="name" id="userName">

                <!-- Selected User Preview -->
                <div id="selectedUserInfo"
                    class="alert alert-info d-none d-flex justify-content-between align-items-center">

                    <div>
                        <strong id="displayName"></strong><br>
                        <small id="displayEmail"></small>
                    </div>

                    <i class="fas fa-check-circle text-success fs-5"></i>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mt-3">
                    <button type="submit"
                        class="btn btn-primary"
                        id="submitBtn"
                        disabled>
                        <i class="fas fa-plus me-1"></i>
                        Add Publisher
                    </button>

                    <button type="reset"
                        class="btn btn-outline-secondary"
                        onclick="resetForm()">
                        <i class="fas fa-redo me-1"></i>
                        Reset
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        const userSelect = document.getElementById('userSelect');
        const userSearchInput = document.getElementById('userSearchInput');
        const userDropdown = document.getElementById('userDropdown');
        const userOptions = document.getElementById('userOptions');
        const userId = document.getElementById('userId');
        const userEmail = document.getElementById('userEmail');
        const userName = document.getElementById('userName');
        const submitBtn = document.getElementById('submitBtn');
        const selectedUserInfo = document.getElementById('selectedUserInfo');
        const displayName = document.getElementById('displayName');
        const displayEmail = document.getElementById('displayEmail');

        // Store all users data
        const allUsers = Array.from(userSelect.options).slice(1).map(opt => ({
            id: opt.value,
            name: opt.dataset.name,
            email: opt.dataset.email,
            text: opt.textContent
        }));

        function displayDropdown(searchValue = '') {
            let filtered = allUsers;

            if (searchValue) {
                const search = searchValue.toLowerCase();
                filtered = allUsers.filter(user =>
                    user.name.toLowerCase().includes(search) ||
                    user.email.toLowerCase().includes(search) ||
                    user.id.toLowerCase().includes(search)
                );
            }

            userOptions.innerHTML = '';

            if (filtered.length === 0) {
                userOptions.innerHTML = '<div class="p-3 text-muted text-center">No users found</div>';
            } else {
                filtered.forEach(user => {
                    const div = document.createElement('div');
                    div.className = 'p-2 cursor-pointer hover-bg';
                    div.style.cursor = 'pointer';
                    div.style.transition = 'background-color 0.2s';
                    div.innerHTML = `<strong>${user.name}</strong><br><small class="text-muted">${user.email}</small>`;

                    div.addEventListener('mouseenter', () => {
                        div.style.backgroundColor = '#f0f0f0';
                    });
                    div.addEventListener('mouseleave', () => {
                        div.style.backgroundColor = 'transparent';
                    });

                    div.addEventListener('click', () => {
                        selectUser(user);
                    });

                    userOptions.appendChild(div);
                });
            }

            userDropdown.style.display = filtered.length > 0 ? 'block' : 'none';
        }

        function selectUser(user) {
            userSearchInput.value = user.name;
            userId.value = user.id;
            userEmail.value = user.email;
            userName.value = user.name;

            displayName.textContent = user.name;
            displayEmail.textContent = user.email;

            selectedUserInfo.classList.remove('d-none');
            submitBtn.disabled = false;
            userDropdown.style.display = 'none';

            // Update hidden select
            userSelect.value = user.id;
        }

        // Search input listener
        userSearchInput.addEventListener('input', function() {
            displayDropdown(this.value);
        });

        // Focus listener to show dropdown
        userSearchInput.addEventListener('focus', function() {
            if (this.value || allUsers.length > 0) {
                displayDropdown(this.value);
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.position-relative')) {
                userDropdown.style.display = 'none';
            }
        });

        function resetForm() {
            userSearchInput.value = '';
            userId.value = '';
            userEmail.value = '';
            userName.value = '';
            selectedUserInfo.classList.add('d-none');
            submitBtn.disabled = true;
            userDropdown.style.display = 'none';
        }
    </script>

<?php
}

function renderPublishersTable(array $publishers): void
{
?>

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 flex-nowrap">
            <input
                type="text"
                id="publisherSearch"
                class="form-control"
                placeholder="Search by name, email, or user ID..."
                style="width: 280px;">
        </div>

        <div class="d-flex gap-2">
            <button id="downloadPublisherCSV" class="btn btn-success">
                <i class="fas fa-file-csv me-1"></i> Download CSV
            </button>
        </div>
    </div>

    <div class="mb-3" style="height: calc(100vh - 455px); overflow-x: auto;">
        <table class="table table-hover table-striped align-middle border rounded shadow-sm" id="publisherTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User ID</th>
                    <th>Status</th>
                    <th>Added</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($publishers as $index => $publisher): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($publisher['name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($publisher['email']) ?></td>
                        <td><?= htmlspecialchars($publisher['user_id']) ?></td>
                        <td>
                            <?php if ($publisher['can_publish']): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i> Active
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i> Disabled
                                </span>
                            <?php endif; ?>
                        </td>

                        <td><?= date('M d, Y', strtotime($publisher['created_at'])) ?></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm action-col" role="group">

                                <!-- Toggle -->
                                <form action="/admin/books/upload-management/process?type=toggle-publisher&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                    method="POST"
                                    class="d-inline">
                                    <input type="hidden" name="email" value="<?= htmlspecialchars($publisher['email']) ?>">
                                    <input type="hidden" name="can_publish" value="<?= $publisher['can_publish'] ? 0 : 1 ?>">
                                    <button type="submit"
                                        class="btn btn-outline-<?= $publisher['can_publish'] ? 'warning' : 'success' ?>">
                                        <i class="fas fa-<?= $publisher['can_publish'] ? 'ban' : 'check' ?>"></i>
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="/admin/books/upload-management/process?type=remove-publisher&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm(
                                        'Are you sure you want to remove <?= addslashes($publisher['email']) ?>?\n\nThis action cannot be undone.'
                                    );">
                                    <input type="hidden" name="email" value="<?= htmlspecialchars($publisher['email']) ?>">
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <nav>
        <ul class="pagination justify-content-center" id="publisherPagination"></ul>
    </nav>

    <script>
        const rowsPerPage = 10;
        let currentPublisherPage = 1;

        const searchInput = document.getElementById('publisherSearch');
        const table = document.getElementById('publisherTable');
        const tbody = table.querySelector('tbody');
        const pagination = document.getElementById('publisherPagination');

        function displayPublisherTable() {
            const searchValue = searchInput.value.toLowerCase();

            const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => {
                return Array.from(row.querySelectorAll('td'))
                    .some(td => td.textContent.toLowerCase().includes(searchValue));
            });

            const totalPages = Math.ceil(rows.length / rowsPerPage);
            if (currentPublisherPage > totalPages)
                currentPublisherPage = totalPages || 1;

            tbody.querySelectorAll('tr').forEach(r => r.style.display = 'none');

            const start = (currentPublisherPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            rows.slice(start, end).forEach(r => r.style.display = '');

            renderPublisherPagination(totalPages);
        }

        function renderPublisherPagination(totalPages) {
            pagination.innerHTML = '';

            const createPageItem = (page, text = page, disabled = false, active = false) => {
                const li = document.createElement('li');
                li.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;
                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = text;
                a.onclick = (e) => {
                    e.preventDefault();
                    if (!disabled) {
                        currentPublisherPage = page;
                        displayPublisherTable();
                    }
                };
                li.appendChild(a);
                return li;
            };

            pagination.appendChild(createPageItem(currentPublisherPage - 1, 'Previous', currentPublisherPage === 1));

            for (let i = 1; i <= totalPages; i++) {
                pagination.appendChild(createPageItem(i, i, false, currentPublisherPage === i));
            }

            pagination.appendChild(createPageItem(currentPublisherPage + 1, 'Next', currentPublisherPage === totalPages));
        }

        // Search listener
        searchInput.addEventListener('keyup', () => {
            currentPublisherPage = 1;
            displayPublisherTable();
        });

        // CSV export
        document.getElementById('downloadPublisherCSV').addEventListener('click', () => {
            const rows = Array.from(tbody.querySelectorAll('tr'));

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
            link.download = `publishers_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        // Init
        displayPublisherTable();
    </script>

<?php
}
?>