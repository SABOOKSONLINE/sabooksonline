<?php

function renderUserSelector(array $users, int $selectedUserId): void
{
    $baseUrl = "/admin/shipping/collection-addresses";
?>
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user me-2 text-primary"></i>
                Select User
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="<?php echo $baseUrl; ?>">
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        User <span class="text-danger">*</span>
                    </label>
                    <select name="user_select" id="userSelectorSelect" class="form-select" required>
                        <option value="">-- Select a user --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo htmlspecialchars($user['user_id']); ?>"
                                data-id="<?php echo htmlspecialchars($user['user_id']); ?>"
                                <?php echo (int) $user['user_id'] === $selectedUserId ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($user['name']); ?>
                                (<?php echo htmlspecialchars($user['email']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Select a user to view and manage their collection addresses.</small>
                </div>

                <input type="hidden" name="user_id" id="selectorUserId" value="<?php echo $selectedUserId ?: ''; ?>">

                <div id="selectedSelectorUserInfo" class="alert alert-info d-none d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong id="selectorDisplayName"></strong><br>
                        <small id="selectorDisplayEmail"></small>
                    </div>
                    <i class="fas fa-check-circle text-success fs-5"></i>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="selectorSubmitBtn"
                        <?php echo !$selectedUserId ? 'disabled' : ''; ?>>
                        <i class="fas fa-search me-1"></i>Load Addresses
                    </button>
                    <?php if ($selectedUserId): ?>
                        <a href="<?php echo $baseUrl; ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        const userSelectorSelect = document.getElementById('userSelectorSelect');
        const selectorUserId = document.getElementById('selectorUserId');
        const selectorSubmitBtn = document.getElementById('selectorSubmitBtn');
        const selectedSelectorInfo = document.getElementById('selectedSelectorUserInfo');
        const selectorDisplayName = document.getElementById('selectorDisplayName');
        const selectorDisplayEmail = document.getElementById('selectorDisplayEmail');

        // Pre-fill info panel if a user is already selected (page reload)
        (function init() {
            const selected = userSelectorSelect.options[userSelectorSelect.selectedIndex];
            if (userSelectorSelect.value && selected) {
                const parts = selected.text.match(/^(.+?)\s\((.+)\)$/);
                if (parts) {
                    selectorDisplayName.textContent = parts[1];
                    selectorDisplayEmail.textContent = parts[2];
                    selectedSelectorInfo.classList.remove('d-none');
                }
            }
        })();

        userSelectorSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (this.value) {
                selectorUserId.value = this.value;

                const parts = selected.text.match(/^(.+?)\s\((.+)\)$/);
                if (parts) {
                    selectorDisplayName.textContent = parts[1];
                    selectorDisplayEmail.textContent = parts[2];
                }

                selectedSelectorInfo.classList.remove('d-none');
                selectorSubmitBtn.disabled = false;
            } else {
                selectorUserId.value = '';
                selectedSelectorInfo.classList.add('d-none');
                selectorSubmitBtn.disabled = true;
            }
        });
    </script>
<?php
}

function renderAddressForm(int $userId, array $editAddress = []): void
{
    $isEdit     = !empty($editAddress);
    $addressId  = $editAddress['id'] ?? '';
    $processUrl = "/admin/shipping/collection-addresses/process";
    $returnUrl  = "/admin/shipping/collection-addresses?user_id={$userId}";

    $provinces = [
        'Eastern Cape',
        'Free State',
        'Gauteng',
        'KwaZulu-Natal',
        'Limpopo',
        'Mpumalanga',
        'North West',
        'Northern Cape',
        'Western Cape'
    ];

    $v = fn($key) => htmlspecialchars($editAddress[$key] ?? '');
?>
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-<?php echo $isEdit ? 'pencil-alt' : 'plus-circle'; ?> me-2 text-primary"></i>
                <?php echo $isEdit ? 'Edit Address' : 'Add New Address'; ?>
            </h5>
            <?php if ($isEdit): ?>
                <a href="<?php echo $returnUrl; ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Cancel
                </a>
            <?php endif; ?>
        </div>
        <div class="card-body p-4">
            <form method="POST"
                action="<?php echo $processUrl; ?>?type=<?php echo $isEdit ? 'update-address' : 'add-address'; ?>&return=<?php echo urlencode($returnUrl); ?>">
                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                <input type="hidden" name="address_id" value="<?php echo $addressId; ?>">

                <!-- Nickname -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nickname <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nickname" class="form-control"
                        placeholder="e.g. Home, Office, Warehouse"
                        value="<?php echo $v('nickname'); ?>" required>
                    <small class="text-muted">A label to identify this address.</small>
                </div>

                <!-- Contact Details -->
                <p class="fw-semibold border-bottom pb-2 mt-4">Contact Details</p>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="contact_name" class="form-control"
                            value="<?php echo $v('contact_name'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="contact_phone" class="form-control"
                            value="<?php echo $v('contact_phone'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="contact_email" class="form-control"
                            value="<?php echo $v('contact_email'); ?>" required>
                    </div>
                </div>

                <!-- Address -->
                <p class="fw-semibold border-bottom pb-2 mt-4">Address</p>
                <div class="row g-3 mb-3">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Unit No.</label>
                        <input type="text" name="unit_number" class="form-control"
                            value="<?php echo $v('unit_number'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Complex Name</label>
                        <input type="text" name="complex_name" class="form-control"
                            value="<?php echo $v('complex_name'); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Street No. <span class="text-danger">*</span></label>
                        <input type="text" name="street_number" class="form-control"
                            value="<?php echo $v('street_number'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Street Name <span class="text-danger">*</span></label>
                        <input type="text" name="street_name" class="form-control"
                            value="<?php echo $v('street_name'); ?>" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Suburb <span class="text-danger">*</span></label>
                        <input type="text" name="suburb" class="form-control"
                            value="<?php echo $v('suburb'); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control"
                            value="<?php echo $v('city'); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Province <span class="text-danger">*</span></label>
                        <select name="province" class="form-select" required>
                            <option value="">Select...</option>
                            <?php foreach ($provinces as $p): ?>
                                <option value="<?php echo $p; ?>"
                                    <?php echo ($v('province') === $p) ? 'selected' : ''; ?>>
                                    <?php echo $p; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Postal Code <span class="text-danger">*</span></label>
                        <input type="text" name="postal_code" class="form-control" maxlength="4"
                            value="<?php echo $v('postal_code'); ?>" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label fw-semibold">Country</label>
                        <input type="text" name="country_code" class="form-control" maxlength="2"
                            value="<?php echo $v('country_code') ?: 'ZA'; ?>" required>
                    </div>
                </div>

                <!-- Special Instructions -->
                <p class="fw-semibold border-bottom pb-2 mt-4">Additional</p>
                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Special Instructions</label>
                        <textarea name="special_instructions" class="form-control" rows="2"
                            placeholder="e.g. Gate code, leave with security..."><?php echo $v('special_instructions'); ?></textarea>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault"
                                <?php echo !empty($editAddress['is_default']) ? 'checked' : ''; ?>>
                            <label class="form-check-label fw-semibold" for="isDefault">
                                Set as default collection address
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        <?php echo $isEdit ? 'Save Changes' : 'Add Address'; ?>
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i>Reset
                    </button>
                    <?php if ($isEdit): ?>
                        <a href="<?php echo $returnUrl; ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                    <?php endif; ?>
                </div>

            </form>
        </div>
    </div>
<?php
}

function renderAddressesTable(array $addresses, int $userId): void
{
    $processUrl = "/admin/shipping/collection-addresses/process";
    $returnUrl  = "/admin/shipping/collection-addresses" . ($userId ? "?user_id={$userId}" : '');
    $showingAll = $userId === 0;

    $provinces = [
        'Eastern Cape',
        'Free State',
        'Gauteng',
        'KwaZulu-Natal',
        'Limpopo',
        'Mpumalanga',
        'North West',
        'Northern Cape',
        'Western Cape'
    ];
?>
    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 flex-nowrap">
            <input type="text" id="addressSearch" class="form-control"
                placeholder="Search by name, address, suburb..."
                style="width: 280px;">
            <select id="provinceFilter" class="form-select" style="width: 200px;">
                <option value="">All Provinces</option>
                <?php foreach ($provinces as $p): ?>
                    <option value="<?php echo $p; ?>"><?php echo $p; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button id="downloadCSV" class="btn btn-success">
            <i class="fas fa-file-csv me-1"></i> Download CSV
        </button>
    </div>

    <div class="mb-3" style="height: calc(100vh - 455px); overflow-x: auto;">
        <table class="table table-hover table-striped align-middle border rounded shadow-sm" id="addressTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <?php if ($showingAll): ?>
                        <th>User</th>
                    <?php endif; ?>
                    <th>Nickname</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Province</th>
                    <th>Postal</th>
                    <th class="text-center">Default</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($addresses as $addr): ?>
                    <tr>
                        <td><?php echo $addr['id']; ?></td>
                        <?php if ($showingAll): ?>
                            <td>
                                <div class="small fw-semibold"><?php echo htmlspecialchars($addr['user_name'] ?? '—'); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($addr['user_email'] ?? ''); ?></div>
                            </td>
                        <?php endif; ?>
                        <td>
                            <span class="fw-semibold"><?php echo htmlspecialchars($addr['nickname']); ?></span>
                            <?php if ($addr['is_default']): ?>
                                <span class="badge bg-success ms-1">Default</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="small"><?php echo htmlspecialchars($addr['contact_name']); ?></div>
                            <div class="small text-muted"><?php echo htmlspecialchars($addr['contact_phone']); ?></div>
                        </td>
                        <td>
                            <div class="small">
                                <?php
                                $parts = array_filter([
                                    $addr['unit_number'] ? 'Unit ' . $addr['unit_number'] : '',
                                    $addr['complex_name'],
                                    $addr['street_number'] . ' ' . $addr['street_name'],
                                    $addr['suburb'],
                                    $addr['city'],
                                ]);
                                echo htmlspecialchars(implode(', ', $parts));
                                ?>
                            </div>
                        </td>
                        <td class="small"><?php echo htmlspecialchars($addr['province']); ?></td>
                        <td class="small"><?php echo htmlspecialchars($addr['postal_code']); ?></td>
                        <td class="text-center">
                            <?php if ($addr['is_default']): ?>
                                <i class="fas fa-check-circle text-success"></i>
                            <?php else: ?>
                                <form method="POST"
                                    action="<?php echo $processUrl; ?>?type=set-default-address&return=<?php echo urlencode($returnUrl); ?>">
                                    <input type="hidden" name="address_id" value="<?php echo $addr['id']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $addr['user_id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Set as default">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm action-col" role="group">
                                <a href="/admin/shipping/collection-addresses?user_id=<?php echo $addr['user_id']; ?>&edit=<?php echo $addr['id']; ?>"
                                    class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form method="POST"
                                    action="<?php echo $processUrl; ?>?type=delete-address&return=<?php echo urlencode($returnUrl); ?>"
                                    class="d-inline"
                                    onsubmit="return confirm(
                                        'Are you sure you want to delete this address?\n\n' +
                                        'Nickname: <?php echo addslashes($addr['nickname']); ?>\n' +
                                        'Address: <?php echo addslashes($addr['street_number'] . ' ' . $addr['street_name'] . ', ' . $addr['suburb']); ?>\n\n' +
                                        'This action cannot be undone.'
                                    )">
                                    <input type="hidden" name="address_id" value="<?php echo $addr['id']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $addr['user_id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <nav>
        <ul class="pagination justify-content-center" id="addressPagination"></ul>
    </nav>

    <script>
        const addressRowsPerPage = 10;
        let addressCurrentPage = 1;

        const addressSearch = document.getElementById('addressSearch');
        const provinceFilter = document.getElementById('provinceFilter');
        const addressTable = document.getElementById('addressTable');
        const addressTbody = addressTable.querySelector('tbody');
        const addressPagination = document.getElementById('addressPagination');

        function getFilteredAddressRows() {
            const searchValue = addressSearch.value.toLowerCase();
            const selectedProvince = provinceFilter.value.toLowerCase();

            return Array.from(addressTbody.querySelectorAll('tr')).filter(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                const matchesSearch = cells.some(td => td.textContent.toLowerCase().includes(searchValue));
                const matchesProvince = !selectedProvince || cells.some(td =>
                    td.textContent.toLowerCase().includes(selectedProvince)
                );
                return matchesSearch && matchesProvince;
            });
        }

        function displayAddressTable() {
            const rows = getFilteredAddressRows();
            const totalPages = Math.ceil(rows.length / addressRowsPerPage);

            if (addressCurrentPage > totalPages) addressCurrentPage = totalPages || 1;

            addressTbody.querySelectorAll('tr').forEach(r => r.style.display = 'none');

            const start = (addressCurrentPage - 1) * addressRowsPerPage;
            rows.slice(start, start + addressRowsPerPage).forEach(r => r.style.display = '');

            renderAddressPagination(totalPages);
        }

        function renderAddressPagination(totalPages) {
            addressPagination.innerHTML = '';

            const createItem = (page, text = page, disabled = false, active = false) => {
                const li = document.createElement('li');
                li.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;
                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = text;
                a.onclick = (e) => {
                    e.preventDefault();
                    if (!disabled) {
                        addressCurrentPage = page;
                        displayAddressTable();
                    }
                };
                li.appendChild(a);
                return li;
            };

            addressPagination.appendChild(createItem(addressCurrentPage - 1, 'Previous', addressCurrentPage === 1));

            let pageList = [];
            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) pageList.push(i);
            } else {
                pageList = [1];
                if (addressCurrentPage > 4) pageList.push('...');
                const start = Math.max(2, addressCurrentPage - 1);
                const end = Math.min(totalPages - 1, addressCurrentPage + 1);
                for (let i = start; i <= end; i++) pageList.push(i);
                if (addressCurrentPage + 1 < totalPages - 1) pageList.push('...');
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
                    addressPagination.appendChild(li);
                } else {
                    addressPagination.appendChild(createItem(p, p, false, addressCurrentPage === p));
                }
            });

            addressPagination.appendChild(createItem(addressCurrentPage + 1, 'Next', addressCurrentPage === totalPages));
        }

        addressSearch.addEventListener('keyup', () => {
            addressCurrentPage = 1;
            displayAddressTable();
        });
        provinceFilter.addEventListener('change', () => {
            addressCurrentPage = 1;
            displayAddressTable();
        });

        document.getElementById('downloadCSV').addEventListener('click', () => {
            const rows = getFilteredAddressRows();
            const headers = Array.from(addressTable.querySelectorAll('thead th'))
                .map(th => `"${th.textContent.trim()}"`);

            const csv = [headers.join(',')];
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
            link.download = `collection_addresses_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        displayAddressTable();
    </script>
<?php
}
