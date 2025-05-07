<?php
require_once __DIR__ . '/../includes/database_connections/sabooks.php';

if (!isset($_SESSION['ADMIN_USERKEY'])) {
    echo "Login required."; exit;
}

$userkey = $_SESSION['ADMIN_USERKEY'];
$sql = "SELECT * FROM posts WHERE USERID = '$userkey' ORDER BY ID DESC;";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Your Books</title>
  <script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>
</head>
<body>
<?php include 'includes/header-dash-main.php';?>
<h2>Your Books</h2>

<?php if (mysqli_num_rows($result) === 0): ?>
    <div>No books found. <a href="dashboard-add-book">Add one</a></div>
<?php else: ?>
    <table border="1" cellpadding="10">
        <tr><th>Title</th><th>Status</th><th>PDF</th><th>Upload</th></tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['TITLE']) ?></td>
                <td><?= htmlspecialchars($row['STATUS']) ?></td>
                <td>
                    <?php if (!empty($row['PDFURL'])): ?>
                        <a href="<?= $row['PDFURL'] ?>" target="_blank">View PDF</a>
                    <?php else: ?>
                        <span>No PDF</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button onclick="uploadPdf('<?= $row['CONTENTID'] ?>')">Upload PDF</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>


<script>
function uploadPdf(contentId) {
  const widget = cloudinary.createUploadWidget({
    cloudName: 'dapufnac8',
    uploadPreset: 'bookContent',
    resourceType: 'raw',
    clientAllowedFormats: ['pdf'],
    folder: 'books'
  }, (error, result) => {
    if (!error && result && result.event === "success") {
      const pdfUrl = result.info.secure_url;

      // Send to PHP to save
      fetch('includes/save-pdf-url.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `contentid=${contentId}&pdf_url=${encodeURIComponent(pdfUrl)}`
      })
      .then(res => res.text())
      .then(response => {
        alert(response);
        location.reload();
      })
      .catch(err => alert("Failed to save PDF URL"));
    }
  });

  widget.open();
}
</script>

</body>
</html>

