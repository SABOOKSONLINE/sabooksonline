<?php

function renderOrdersTable($orders, $itemsByOrder = []): void
{
?>
    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 flex-nowrap">
            <input
                type="text"
                id="orderSearch"
                class="form-control"
                placeholder="Search by order number, user ID, status..."
                style="width: 280px;">

            <select
                id="statusFilter"
                class="form-select"
                style="width: 200px;">
                <option value="">All Statuses</option>
                <?php
                $statuses = array_unique(array_map(fn($o) => $o['order_status'], $orders));
                foreach ($statuses as $status): ?>
                    <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars(ucfirst($status)) ?></option>
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
        <table class="table table-hover table-striped align-middle border rounded shadow-sm" id="ordersTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Order Number</th>
                    <th>User ID</th>
                    <th>Items</th>
                    <th>Total Amount</th>
                    <th>Shipping Fee</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): 
                    $orderId = $order['id'];
                    $items = $itemsByOrder[$orderId] ?? [];
                    
                    // Count book types in this order
                    $hasAcademic = false;
                    $hasRegular = false;
                    $itemCount = count($items);
                    
                    foreach ($items as $item) {
                        $bookType = $item['book_type'] ?? 'regular';
                        if ($bookType === 'academic') {
                            $hasAcademic = true;
                        } else {
                            $hasRegular = true;
                        }
                    }
                ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['order_number'] ?></td>
                        <td><?= $order['user_id'] ?></td>
                        <td>
                            <?php if ($itemCount > 0): ?>
                                <div class="d-flex flex-column gap-1">
                                    <small class="text-muted"><?= $itemCount ?> item<?= $itemCount > 1 ? 's' : '' ?></small>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <?php if ($hasAcademic): ?>
                                            <span class="badge bg-info" style="font-size: 0.7rem;">Academic</span>
                                        <?php endif; ?>
                                        <?php if ($hasRegular): ?>
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;">Regular</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">No items</span>
                            <?php endif; ?>
                        </td>
                        <td>R<?= number_format($order['total_amount'], 2) ?></td>
                        <td>R<?= number_format($order['shipping_fee'], 2) ?></td>
                        <td><?= ucfirst($order['payment_method']) ?></td>
                        <td><?= ucfirst($order['payment_status']) ?></td>
                        <td>
                            <select class="form-select order-status" data-order-id="<?= $order['id'] ?>">
                                <?php
                                $allStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                                foreach ($allStatuses as $s):
                                ?>
                                    <option value="<?= $s ?>" <?= $s === $order['order_status'] ? 'selected' : '' ?>>
                                        <?= ucfirst($s) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><?= $order['created_at'] ?></td>
                        <td><?= $order['updated_at'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary view-order-details"
                                data-bs-toggle="modal"
                                data-bs-target="#orderModal"
                                data-order='<?= htmlspecialchars(json_encode($order), ENT_QUOTES) ?>'>
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetailsContent">
                        <!-- Filled dynamically via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const rowsPerPage = 10;
        let currentPage = 1;

        const searchInput = document.getElementById('orderSearch');
        const statusFilter = document.getElementById('statusFilter');
        const table = document.getElementById('ordersTable');
        const tbody = table.querySelector('tbody');
        const pagination = document.getElementById('pagination');

        function displayTable() {
            const searchValue = searchInput.value.toLowerCase();
            const selectedStatus = statusFilter.value.toLowerCase();

            const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                const status = (cells[7]?.querySelector('select')?.value || '').toLowerCase();
                const matchesSearch = cells.some(td => td.textContent.toLowerCase().includes(searchValue));
                const matchesStatus = !selectedStatus || status === selectedStatus;
                return matchesSearch && matchesStatus;
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
                a.addEventListener('click', e => {
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
            for (let i = 1; i <= totalPages; i++) {
                pagination.appendChild(createPageItem(i, i, false, currentPage === i));
            }
            pagination.appendChild(createPageItem(currentPage + 1, 'Next', currentPage === totalPages));
        }

        searchInput.addEventListener('keyup', () => {
            currentPage = 1;
            displayTable();
        });
        statusFilter.addEventListener('change', () => {
            currentPage = 1;
            displayTable();
        });

        document.querySelectorAll('.order-status').forEach(select => {
            select.addEventListener('change', e => {
                const orderId = e.target.dataset.orderId;
                const newStatus = e.target.value;

                fetch('/admin/orders/update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        order_status: newStatus
                    })
                }).then(res => res.json()).then(data => {
                    if (!data.success) alert('Failed to update status');
                }).catch(() => alert('Failed to update status'));
            });
        });

        document.querySelectorAll('.view-order-details').forEach(btn => {
            btn.addEventListener('click', e => {
                const order = JSON.parse(btn.dataset.order);
                const contentDiv = document.getElementById('orderDetailsContent');

                const items = <?= json_encode($itemsByOrder ?? []) ?>;
                const orderData = items[order.id] || [];

                let itemsHTML = '';
                if (orderData.length > 0) {
                    itemsHTML = '<h6>Items:</h6><table class="table table-sm table-bordered">';
                    itemsHTML += '<thead><tr><th>Type</th><th>Book ID</th><th>Title</th><th>Authors</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead><tbody>';
                    orderData.forEach(it => {
                        const bookType = it.book_type || 'regular';
                        const typeLabel = bookType === 'academic' ? '<span class="badge bg-info">Academic</span>' : '<span class="badge bg-secondary">Regular</span>';
                        itemsHTML += `<tr>
                            <td>${typeLabel}</td>
                            <td>${it.book_id}</td>
                            <td>${it.TITLE || 'N/A'}</td>
                            <td>${it.AUTHORS || 'N/A'}</td>
                            <td>${it.quantity}</td>
                            <td>R${parseFloat(it.unit_price).toFixed(2)}</td>
                            <td>R${parseFloat(it.total_price).toFixed(2)}</td>
                        </tr>`;
                    });
                    itemsHTML += '</tbody></table>';
                } else {
                    itemsHTML = '<p>No items found for this order.</p>';
                }

                let addressHTML = '';
                if (orderData.length > 0) {
                    const addr = orderData[0];
                    addressHTML = `
                        <hr>
                        <p><strong>Delivery details:</strong> ${addr.full_name}${addr.company ? ' (' + addr.company + ')' : ''}, ${addr.street_address}${addr.street_address2 ? ', ' + addr.street_address2 : ''}, ${addr.local_area}, ${addr.zone}, ${addr.postal_code} — ${addr.delivery_type}</p>
                        <p><strong>Phone:</strong> ${addr.phone}</p>
                        <p><strong>Email:</strong> ${addr.email}</p>
                    `;
                }

                contentDiv.innerHTML = `
                    <p><strong>Order Number:</strong> ${order.order_number}</p>
                    <p><strong>User ID:</strong> ${order.user_id}</p>
                    <p><strong>Total Amount:</strong> R${parseFloat(order.total_amount).toFixed(2)}</p>
                    <p><strong>Shipping Fee:</strong> R${parseFloat(order.shipping_fee).toFixed(2)}</p>
                    <p><strong>Payment Method:</strong> ${order.payment_method}</p>
                    <p><strong>Payment Status:</strong> ${order.payment_status}</p>
                    <p><strong>Order Status:</strong> ${order.order_status}</p>
                    <p><strong>Created At:</strong> ${order.created_at}</p>
                    <p><strong>Updated At:</strong> ${order.updated_at}</p>
                    ${addressHTML}
                    ${itemsHTML}
                `;
            });
        });


        displayTable();
    </script>
<?php
}
?>