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

                    // ✅ Fetch purchases for the logged-in user
                    // Assume you store logged-in user email in session
                    $userEmail = $_SESSION['ADMIN_EMAIL'] ?? null;

                    if ($userEmail) {
                        $sql = "SELECT id, book_id, format, amount, payment_status, payment_date 
                                FROM book_purchases 
                                WHERE user_email = ? 
                                ORDER BY payment_date DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $userEmail);
                        $stmt->execute();
                        $result = $stmt->get_result();

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
                                echo '<td>' . date("Y-m-d", strtotime($row['payment_date'])) . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table></div></div>';
                        } else {
                            echo '<div class="alert alert-info shadow-sm mb-4">No purchases found for your account.</div>';
                        }

                        $stmt->close();
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
