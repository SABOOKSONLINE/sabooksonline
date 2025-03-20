<!DOCTYPE html>
<!-- saved from url=(0057)http://www.ansonika.com/fooyes/admin_section/invoice.html -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Ansonika">
    <title>Invoice #11900</title>
    <!-- Favicons-->
    <link rel="shortcut icon" href="http://www.ansonika.com/fooyes/admin_section/img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="http://www.ansonika.com/fooyes/admin_section/img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="http://www.ansonika.com/fooyes/admin_section/img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="http://www.ansonika.com/fooyes/admin_section/img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="http://www.ansonika.com/fooyes/admin_section/img/apple-touch-icon-144x144-precomposed.png">
    <!-- Bootstrap core CSS-->
    <link href="./invoice_files/bootstrap.min.css" rel="stylesheet">
    <!-- Main styles -->
    <link href="./invoice_files/admin.css" rel="stylesheet">
    <!-- Icon fonts-->
    <link href="./invoice_files/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Plugin styles -->
    <link href="./invoice_files/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Your custom styles -->
    <link href="./invoice_files/custom.css" rel="stylesheet">
    <style>
    .invoice-title h2, .invoice-title h3 {
        display: inline-block;
    }
    
    .table > tbody > tr > .no-line {
        border-top: none;
    }
    
    .table > thead > tr > .no-line {
        border-bottom: none;
    }
    
    .table > tbody > tr > .thick-line {
        border-top: 2px solid;
    }
    @media print {
        a.btn_1 {
            display: none;
            opacity: 0;
            visibility:hidden;
            height: 0;
        }
    }
    </style>
</head>

<body style="background-color: #fff">
  <div class="container">
    <p class="text-center pt-5"><a href="javascript:window.print()" class="btn_1 gray"><i class="fa fa-fw fa-print"></i> Print this invoice</a></p>

    <?php

        include '../../includes/database_connections/sabooks.php';

        if(!isset($_GET['invoice'])){
            header("Location: ../../subscriptiopn.php?accessdenied");
        } else {

            $invoice_number = $_GET['invoice'];

            $sql = "SELECT * FROM invoices WHERE INVOICE_NUMBER = '$invoice_number'";

            $data = mysqli_fetch_assoc(mysqli_query($conn, $sql));

            $status_invoice = $data['INVOICE_STATUS'];
            $user_invoice = $data['INVOICE_USER'];


            $user_query = "SELECT * FROM users WHERE ADMIN_USERKEY = '$user_invoice';";
            $user_data = mysqli_fetch_assoc(mysqli_query($conn, $user_query));

            $name = $user_data['ADMIN_NAME'];
            $email = $user_data['ADMIN_EMAIL'];
            $number = $user_data['ADMIN_NUMBER'];
            
            if($status_invoice != 'Paid'){
                $status_invoice =  '<span class="btn btn-danger">Pay Now</span>';
            } else {
                $status_invoice = '<span class="btn btn-success">Account Paid</span>';
            }

            echo '

            <div class="row">
                <div class="col-12">
                    <div class="invoice-title pt-4">
                        <h3>Invoice </h3><h3 class="float-right">Order # '.$invoice_number.'</h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <address>
                            <strong>Billed To:</strong><br>
                                '.$name.'<br>
                                '.$email.'<br>
                                '.$number.'<br>
                                
                            </address>
                        </div>
                        <div class="col-6 text-right">
                            <address>
                            <strong>Receipeint:</strong><br>
                                <b>Bank Name:</b> Absa Bank<br>
                                <b>Acc Name:</b> South African Books Online<br>
                               	<b>Reg:</b> 2021/606043/07<br>
								<b>Acc No:</b> 4101284719<br>
 								<b>Acc Type:</b> Current/Business<br>
								<b>Branch Code:</b> 632005<br>
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <address>
                                <strong>Payment Method: Online</strong><br><br>
                                '.$status_invoice.'
                            </address>
                        </div>
                        <div class="col-6 text-right">
                            <address>
                                <strong>Order Date:</strong><br>
                                '.$data['INVOICE_DATE'].'<br><br>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="pt-4">
                        <h5><strong>Order summary</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <td><strong>Item</strong></td>
                                            <td class="text-center"><strong>Price</strong></td>
                                            <td class="text-center"><strong>Quantity</strong></td>
                                            <td class="text-right"><strong>Totals</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Subscription - '.$data['INVOICE_SUBSCRIPTION'].' @ R'.$data['INVOICE_AMOUNT'].'/M</td>
                                            <td class="text-center">R'.$data['INVOICE_AMOUNT'].'</td>
                                            <td class="text-center">1</td>
                                            <td class="text-right">R'.$data['INVOICE_AMOUNT'].'</td>
                                        </tr>
                                      
                                        <tr>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line text-center"><strong>Total</strong></td>
                                            <td class="thick-line text-right">R'.$data['INVOICE_AMOUNT'].'</td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ';

        }



    ?>
    

  

</body></html>