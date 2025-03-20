<?php

        $apiUrl = "https://sabooksonline.co.za/includes/api/read_remote_data.php?api_key=nola1234&userkey=".$fileContent;
        $data = file_get_contents($apiUrl);
        $decodedData = json_decode($data, true);
        

        $created = array_column($decodedData, 'SITE_CREATED');
        $modified = array_column($decodedData, 'SITE_MODIFIED');
        $merchant_id = array_column($decodedData, 'SITE_MERCHANT_ID');	
        $merchant_key = array_column($decodedData, 'SITE_MERCHANT_KEY');	
        $number = array_column($decodedData, 'SITE_NUMBER');
        $email = array_column($decodedData, 'SITE_EMAIL');	
        $title = array_column($decodedData, 'SITE_TITLE');	
        $address = array_column($decodedData, 'SITE_ADDRESS');	
        $latitude = array_column($decodedData, 'SITE_LATITUDE');
        $longitude = array_column($decodedData, 'SITE_LONGITUDE');
        $books = array_column($decodedData, 'SITE_BOOKS');
        $desc = array_column($decodedData, 'DESCRIPTION');
        $logo = array_column($decodedData, 'LOGO');
        $domain = array_column($decodedData, 'DOMAIN');
        $passphrase = array_column($decodedData, 'SITE_LONGITUDE');

		

?>