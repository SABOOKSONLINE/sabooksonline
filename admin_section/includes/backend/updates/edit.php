<?php
    
      
            //DATABASE CONNECTIONS SCRIPT
        include '../../dbh.inc.php';

        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $sub = mysqli_real_escape_string($conn, $_POST['sub']);
        $desc = mysqli_real_escape_string($conn, $_POST['desc']);
        $brand = mysqli_real_escape_string($conn, $_POST['brand']);
        $products = mysqli_real_escape_string($conn, $_POST['products']);
        $price_current = mysqli_real_escape_string($conn, $_POST['price_current']);
        $price_old = mysqli_real_escape_string($conn, $_POST['price_old']);
        $keywords = mysqli_real_escape_string($conn, $_POST['keywords']);
        $sku = mysqli_real_escape_string($conn, $_POST['sku']);
        $contentid = mysqli_real_escape_string($conn, $_POST['contentid']);
    
            $sourcePath = $_FILES['eng_cover']['tmp_name'];
    
            $title_show = $title;
            $title = str_replace("'", "`", $title);
           
            //$date = date("l jS \of F Y h:i:s A");
            $date = date("l, jS \of F Y");
    
            $sourcePath = $_FILES['eng_cover']['tmp_name'];

            $folder = strtolower(str_replace(' ', '-', $title));

            //IMAGE UPLOAD CODE START

            $bio_sorted = str_replace('\r\n', '', $desc);

            $page_overview = "../../../../products/".$contentid."/includes/overview.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $bio_sorted);

            $page_overview = "../../../../products/".$contentid."/includes/title.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $title);

            $page_overview = "../../../../products/".$contentid."/includes/brand.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $brand);

            $page_overview = "../../../../products/".$contentid."/includes/sku.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $sku);

            $page_overview = "../../../../products/".$contentid."/includes/currentprice.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $price_current);

            $page_overview = "../../../../products/".$contentid."/includes/oldprice.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $price_old);

            $page_overview = "../../../../products/".$contentid."/includes/category.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $category);

            $page_overview = "../../../../products/".$contentid."/includes/stock.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $products);
            

            $sourcePath = $_FILES['eng_cover']['tmp_name'];

                $targetPath = "../../../../products/".$contentid."/".$_FILES['eng_cover']['name'];
                $targetPath1 = "../".$_FILES['eng_cover']['name'];
                if(move_uploaded_file($sourcePath,$targetPath)){
                    $targetPath1 = $_FILES['eng_cover']['name'];

                        $page_profile = "../../../../products/".$contentid."/includes/cover.php";
                        $myfile = fopen($page_profile , "w");
                        fwrite($myfile, $targetPath1);
                   
                }else {

                    $sql = "SELECT * FROM products WHERE PRODUCTKEY = '$contentid' ORDER BY ID DESC;";
							$result = mysqli_query($conn, $sql);


							if(mysqli_num_rows($result) == false){
							echo "Page not found in the database!";

							}else{
							while($row = mysqli_fetch_assoc($result)) {
								
                                $targetPath1 = $row['PRODUCTIMAGE'];
							}
							}
                    
                }
                    
                    //excluding image and password
                    $sql = "UPDATE products SET PRODUCTIMAGE = '$targetPath1', TITLE = '$title', DETAILS = '$desc', SKU = '$sku', BRAND = '$brand', PRICE = '$price_current', OLDPRICE = '$price_old', KEYWORDS = '$keywords', QUANTITY = '$products', CATEGORY = '$category', SUBCATEGORY = '$sub' WHERE PRODUCTKEY='$contentid'";

                        if ($conn->query($sql) === TRUE) {
                        echo '<p class="alert alert-success">Page successfully updated!</p>';

                        } else {
                        echo '<p class="alert alert-warning">Something went wrong!</p>' . $conn->error;
                        }

                        $conn->close();

 

?>