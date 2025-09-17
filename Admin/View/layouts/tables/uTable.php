<?php

function renderUserTable($users): void
{
?>
    <div class="mb-3" style="height: calc(100vh - 405px); overflow-x: auto;">
        <table class="table table-hover table-striped align-middle border rounded shadow-sm">
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

                                <a href="admin/users/delete/<?= urlencode($user["ADMIN_ID"]) ?>"
                                    onclick="return confirm('Are you sure you want to delete <?= $user['ADMIN_NAME'] ?>?')"
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
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link">Previous</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>
<?php
}
?>