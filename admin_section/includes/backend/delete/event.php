
<?php

//DATABASE CONNECTIONS SCRIPT
include '../../../includes/database_connections/sabooks.php';

$contentid = mysqli_real_escape_string($conn, $_GET['contentid']);


$target = "../../../../events/".strtolower($contentid);

delete_files($target);

$sql = "DELETE FROM events WHERE CONTENTID ='$contentid';";

mysqli_query($conn, $sql);


/* 
 * php delete function that deals with directories recursively
 */
function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file ){
            delete_files( $file );      
        }

        rmdir( $target );

    } elseif(is_file($target)) {
        unlink( $target ); 

    }

    $page = $_GET['page'];

    header("Location: ../../../$page.php?status=success");
}
?>
