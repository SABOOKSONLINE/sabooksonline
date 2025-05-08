<?php
require_once __DIR__ . '/../includes/database_connections/sabooks.php';
include 'includes/header-dash-main.php';

if (!isset($_SESSION['ADMIN_USERKEY'])) {
    echo "Login required.";
    exit;
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
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #004080;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        img.cover {
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
        }

        .btn-upload {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-upload:hover {
            background-color: #0056b3;
        }

        .meta {
            font-size: 0.9em;
            color: #666;
        }

        .desc {
            font-size: 0.9em;
            max-width: 400px;
        }
    </style>
</head>
<body>

<h2>Your Uploaded Books</h2>

<?php if (mysqli_num_rows($result) === 0): ?>
    <div>No books found. <a href="dashboard-add-book">Add one</a></div>
<?php else: ?>
    <table>
        <tr>
            <th>Cover</th>
            <th>Title & Info</th>
            <th>Status</th>
            <th>PDF</th>
            <th>Upload</th>
        </tr>
        <?php while ($book = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>
                    <?php if (!empty($book['COVER'])): ?>
                        <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= $book['COVER'] ?>" class="cover" alt="Book Cover">
                    <?php else: ?>
                        <span>No Cover</span>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?= htmlspecialchars($book['TITLE']) ?></strong><br>
                    <div class="meta">
                        <strong>Author:</strong> <?= htmlspecialchars($book['AUTHORS']) ?><br>
                        <strong>Publisher:</strong> <?= htmlspecialchars($book['PUBLISHER']) ?><br>
                        <strong>ISBN:</strong> <?= htmlspecialchars($book['ISBN']) ?><br>
                    </div>
                    <div class="desc">
                        <?= htmlspecialchars($book['DESCRIPTION']) ?>
                    </div>
                </td>
                <td><?= htmlspecialchars($book['STATUS']) ?></td>
                <td>
                    <?php if (!empty($book['PDFURL'])): ?>
                        <a href="<?= $book['PDFURL'] ?>" target="_blank">View PDF</a>
                    <?php else: ?>
                        <span>No PDF</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button class="btn-upload" onclick="uploadPdf('<?= $book['CONTENTID'] ?>')">Upload PDF</button>
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
