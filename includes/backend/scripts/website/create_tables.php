<?php
$servername = "localhost";
$username = $dbUsername;
$password = $dbUserPassword;
$dbname = $dbUsername;

$conn_tables = new mysqli($servername, $username, $password, $dbname);

if ($conn_tables->connect_error) {
    die("Connection failed: " . $conn_tables->connect_error);
}

// SQL queries to create tables
$createProductsTable = "CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_cat VARCHAR(255),
    product_brand VARCHAR(255),
    product_title VARCHAR(255),
    product_price DECIMAL(10, 2),
    product_desc TEXT,
    product_image VARCHAR(255),
    product_keywords VARCHAR(255),
    product_stock VARCHAR(255)
)";

$createProductOrderTable = "CREATE TABLE product_order (
    product_id INT,
    product_title VARCHAR(255),
    product_cat VARCHAR(255),   
    product_brand VARCHAR(255),
    product_quantity INT,
    product_image VARCHAR(255),
    product_current VARCHAR(255),
    product_total DECIMAL(10, 2),
    product_desc TEXT,
    user_id INT,
    product_price DECIMAL(10, 2),
    invoice_number VARCHAR(255)
)";

$createUserInfoTable = "CREATE TABLE user_info (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255), 
    mobile VARCHAR(20),
    address1 VARCHAR(255),
    address2 VARCHAR(255)
)";

$createUserInfoBackupTable = "CREATE TABLE user_info_backup (
    user_id INT,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    mobile VARCHAR(20),
    address1 VARCHAR(255),
    address2 VARCHAR(255)
)";

$createEmailInfoTable = "CREATE TABLE email_info (
    email_id INT PRIMARY KEY,
    email VARCHAR(255)
)";

$createInvoicesTable = "CREATE TABLE invoices (
    invoice_id INT PRIMARY KEY,
    invoice_number VARCHAR(255),
    invoice_user INT,
    invoice_date TEXT,
    invoice_items TEXT,
    invoice_status VARCHAR(255),
    invoice_total DECIMAL(10, 2)
)";

$createLogsTable = "CREATE TABLE logs (
    id INT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255),
    date DATETIME
)";

// Execute the CREATE TABLE queries
$createQueries = [
    $createProductsTable,
    $createProductOrderTable,
    $createUserInfoTable,
    $createUserInfoBackupTable,
    $createEmailInfoTable,
    $createInvoicesTable,
    $createLogsTable
];

foreach ($createQueries as $query) {
    if ($conn_tables->query($query) !== TRUE) {
        echo "Error creating table: " . $conn_tables->error;

        //$tables = false;
    }
}

//$conn_tables->close();
?>
