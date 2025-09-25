<?php
require_once __DIR__ . "/../database/connection.php";

include __DIR__ . "/includes/header.php";
include __DIR__ . "/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/includes/nav.php"; ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/includes/layouts/side-bar.php" ?>

                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">
                    <?php
                    renderHeading("Account Billing", "Manage your billing information, invoices, and payment methods here.");

                    $userEmail = $_SESSION['ADMIN_EMAIL'] ?? null;

                    if ($userEmail) {
                        // ---------------------------
                        // Purchases
                        // ---------------------------
                        $sql = "SELECT id, book_id, format, amount, payment_status, payment_date 
                                FROM book_purchases 
                                WHERE user_email = ? 
                                ORDER BY payment_date DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $userEmail);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // ---------------------------
                        // Subscriptions
                        // ---------------------------
                        $sqlPlans = "SELECT payment_id, plan_name, amount_paid, payment_date, end_date, status, renewal_status, is_recurring
                                    FROM payment_plans
                                    WHERE user_email = ?
                                    ORDER BY payment_date DESC";
                        $stmtPlans = $conn->prepare($sqlPlans);
                        $stmtPlans->bind_param("s", $userEmail);
                        $stmtPlans->execute();
                        $resultPlans = $stmtPlans->get_result();

                        // Purchases table
                        if ($result->num_rows > 0) {
                            echo '<div class="card shadow-sm">';
                            echo '<div class="card-header bg-light"><strong>Purchase History</strong></div>';
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped table-hover mb-0">';
                            echo '<thead><tr>
                                    <th>#</th>
                                    <th>Title ID</th>
                                    <th>Format</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr></thead><tbody>';

                            $i = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $i++ . '</td>';
                                echo '<td>' . htmlspecialchars($row['book_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['format']) . '</td>';
                                echo '<td>R' . number_format($row['amount'], 2) . '</td>';
                                echo '<td><span class="badge bg-' .
                                    ($row['payment_status'] === 'COMPLETE' ? 'success' : 'warning') . '">' .
                                    htmlspecialchars($row['payment_status']) . '</span></td>';
                                echo '<td>' . ($row['payment_date'] ? date("Y-m-d", strtotime($row['payment_date'])) : '-') . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table></div></div>';
                        } else {
                            echo '<div class="alert alert-info shadow-sm mb-4">No purchases found for your account.</div>';
                        }

                        // Subscriptions table
                        if ($resultPlans->num_rows > 0) {
                            echo '<div class="card shadow-sm mt-4">';
                            echo '<div class="card-header bg-light"><strong>Subscription History</strong></div>';
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped table-hover mb-0">';
                            echo '<thead><tr>
                                    <th>#</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Renewal</th>
                                    <th>Recurring</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr></thead><tbody>';

                            $i = 1;
                            while ($plan = $resultPlans->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $i++ . '</td>';
                                echo '<td>' . htmlspecialchars($plan['plan_name']) . '</td>';
                                echo '<td>R' . number_format($plan['amount_paid'], 2) . '</td>';
                                echo '<td><span class="badge bg-' .
                                    ($plan['status'] === 'COMPLETE' ? 'success' : 'warning') . '">' .
                                    htmlspecialchars($plan['status']) . '</span></td>';
                                echo '<td>' . htmlspecialchars($plan['renewal_status']) . '</td>';
                                echo '<td>' . ($plan['is_recurring'] ? "Yes" : "No") . '</td>';
                                echo '<td>' . ($plan['payment_date'] ? date("Y-m-d", strtotime($plan['payment_date'])) : '-') . '</td>';
                                echo '<td>' . ($plan['end_date'] ? date("Y-m-d", strtotime($plan['end_date'])) : '-') . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table></div></div>';
                        } else {
                            echo '<div class="alert alert-info shadow-sm mt-4">No subscription history found.</div>';
                        }

                        $stmt->close();
                        $stmtPlans->close();

                    } else {
                        echo '<div class="alert alert-warning shadow-sm mb-4">You need to be logged in to view billing information.</div>';
                    }
                    ?>

                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php"; ?>
</body>
</html>
