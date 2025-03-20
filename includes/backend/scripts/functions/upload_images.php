<?php
session_start();
include '../../../database_connections/sabooks.php';

if (isset($_FILES['store_images'])) {
    $imagePaths = [];

    foreach ($_FILES['store_images']['tmp_name'] as $index => $tmpName) {
        $imageName = $_FILES['store_images']['name'][$index];
        $imageUploadPath = '../../../../cms-data/user-images/' . $imageName;
        move_uploaded_file($tmpName, $imageUploadPath);
        $imagePaths[] = $imageUploadPath;
    }

    $bookstore_id = $_POST['contentid'];
    $status = 'active';
    $contentid = 'active'; // It seems this might be redundant, replace with actual contentid if necessary
    $userkey = 'active'; // Similarly, replace this with the actual user ID or remove if not needed

    foreach ($imagePaths as $imagePath) {
        $sql_insert_image = "INSERT INTO bookstores_images (IMAGE, CONTENTID, STOREID, USERID, STATUS) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert_image = $conn->prepare($sql_insert_image);
        $stmt_insert_image->bind_param("sssss", $imagePath, $contentid, $bookstore_id, $userkey, $status);

        if ($stmt_insert_image->execute()) {
            // Image inserted successfully
        } else {
            // Handle the case where the image couldn't be inserted
            echo "Error inserting image: " . $stmt_insert_image->error;
        }

        $stmt_insert_image->close();
    }

    echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Book store images have been uploaded!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('bookstore');},3000);</script>";
} else {
    // Handle case when 'store_images' files are not provided
    echo "No images uploaded.";
}
?>
