
<?php

//DATABASE CONNECTIONS SCRIPT
include '../../database_connections/sabooks.php';

$contentid = mysqli_real_escape_string($conn, $_GET['contentid']);

$page = $_GET['page'];
//$target = "../../../../products/".strtolower($contentid);

//delete_files($target);

$contentid = $_GET['contentid'];
$veri_link = $contentid.time();

//TIME VARIABLE
$current_time = date('l jS \of F Y');

include '../../database_connections/sabooks.php';

$sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$contentid';";

$result = mysqli_query($conn, $sql);


if(mysqli_num_rows($result) == false){
    
    echo "<h5>Mmh something went completely wrong!</h5>";
    
} else {

    while ($row = mysqli_fetch_assoc($result)) {
        $reg_name = $row['ADMIN_NAME'];
        $reg_email = $row['ADMIN_EMAIL'];
    }


    $message = "Thank you for taking the time to apply for membership. Unfortunately your application was declined and removed , should you wish to apply again click the button below. During your application use the correct information required.";

    $button_link = "https://my.sabooksonline.co.za/membership.php";
    $link_text = "Apply For Membership";
	
	$subject = "Membership decline for ".$reg_name;

    include '../templates/emails/multiuse.php';

    $sql = "DELETE FROM users WHERE ADMIN_USERKEY = '$contentid';";
        
        if(!mysqli_query($conn, $sql)){
            
            header("Location: ../../../$page.php?status=failed");
            
        }else{

            header("Location: ../../../$page.php?status=success");

            //echo $message2;

            mail($reg_email,$subject,$message2,$headers);

        }
}

/* 
 * php delete function that deals with directories recursively
 
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

    
}*/
?>
