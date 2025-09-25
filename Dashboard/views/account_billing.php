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
                            $sqlRoyalty = "SELECT subscription_status FROM users WHERE admin_email = ? AND subscription_status = 'royalties'";
                            $stmtRoyalty = $conn->prepare($sqlRoyalty);
                            $stmtRoyalty->bind_param("s", $userEmail);
                            $stmtRoyalty->execute();
                            $resultRoyalty = $stmtRoyalty->get_result();

                            if ($resultRoyalty->num_rows > 0) {
                                echo '<div class="card shadow-sm border-primary mt-4 mb-4">';
                                echo '<div class="card-header bg-primary text-white d-flex align-items-center">';
                                echo '<img src="/public/images/sabo_logo.png" alt="SA Books Online" style="height:40px; margin-right:10px;">';
                                echo '<h5 class="mb-0">Royalty Plan Information</h5>';
                                echo '</div>';
                                echo '<div class="card-body">';
                                echo '<p class="mb-3"><strong>Dear Author/Publisher,</strong><br>';
                                echo 'You are currently enrolled in the <strong>Royalty Plan — Pay Later option</strong>. Upon signing up, you agreed to the terms and conditions outlined below:</p>';
                                echo '<ul class="mb-3" style="line-height:1.6;">';
                                echo '<li>Authors and publishers earn <strong>65% royalties</strong> from all sales through the SA Books Online platform.</li>';
                                echo '<li>Royalties are calculated based on <strong>completed and cleared sales</strong>.</li>';
                                echo '<li>Royalty payouts are made on a <strong>quarterly basis</strong> (March, June, September, December).</li>';
                                echo '<li>Clients on the Pay Later option will have applicable <strong>Trade Levies</strong> deducted before payout.</li>';
                                echo '<li>Payment requests outside of the quarterly payout cycle may be considered and are subject to an <strong>administrative fee</strong>.</li>';
                                echo '<li>All royalties are paid via the author/publisher\'s designated <strong>bank account</strong>.</li>';
                                echo '</ul>';
                                echo '<p class="mb-0">';
                                echo 'For more details or to <strong>upgrade to a full paid plan</strong>, please ';
                                echo '<a href="/membership" >Click Here</a>.';
                                echo '</p>';
                                echo '</div>';
                                echo '</div>';
                                
                            } else {
                                // 3️⃣ Default reader message
                                echo '<div class="alert alert-secondary shadow-sm">';
                                echo '<h5>Welcome Reader!</h5>';
                                echo '<p>You can browse and enjoy our collection of books, magazines, newspapers, academic textbooks, and audiobooks.</p>';
                                echo '</div>';
                            }

                            $stmtRoyalty->close();
                        }

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
