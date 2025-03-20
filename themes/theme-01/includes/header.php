<?php session_start(); ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);?>


<!-- Header -->
<header id="wn__header" class="header__area header__absolute sticky__header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-6 col-lg-2">
                    <div class="logo">
                        <a href="index">
                        

                    <img src="https://sabooksonline.co.za/cms-data/profile-images/<?php echo $logo[$i];?>" alt="logo images" width="50px" height="50px" style="border-radius: 50px">
	
                        </a>
                    </div>
                </div>
                <div class="col-lg-7 d-none d-lg-block">
                    <nav class="mainmenu__nav">
                        <ul class="meninmenu d-flex justify-content-start">
                            <li class="drop with--one--item"><a href="https://<?php echo $domain[$i];?>">Home</a>
                            </li>
                            
                            <li><a href="https://<?php echo $domain[$i];?>/#about">About</a></li>
                            <li><a href="https://<?php echo $domain[$i];?>/#shop">Shop Books</a></li>
                            <li><a href="https://<?php echo $domain[$i];?>/#contact">Contact Us</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-6 col-6 col-lg-3">
                    <ul class="header__sidebar__right d-flex justify-content-end align-items-center">
                        
                        <li class="shopcart"><a class="" href="cart"><span
                                class="product_qun" id="reload"></span></a>
                        </li>

                        <?php
                                                                                                                
                        if(!isset($_SESSION['first_name'])){

                            echo '<li class="cart"><a href="my-account" class="btn btn-dark text-white">Login/Register</a></li>';
                        
                        } else {
                        

                        echo '
                        <li class="cart"><a href="my-orders" class="btn btn-dark text-white">'.$_SESSION['first_name'].' - My Orders</a></li>
                        ';
                        }

                        ?>
                    </ul>
                </div>
            </div>
            <!-- Start Mobile Menu -->
            <div class="row d-none">
                <div class="col-lg-12 d-none">
                    <nav class="mobilemenu__nav">
                        <ul class="meninmenu">
                            <li><a href="https://<?php echo $domain[$i];?>">Home</a>
                            </li>
                            
                            <li><a href="https://<?php echo $domain[$i];?>#about">About</a></li>
                            <li><a href="https://<?php echo $domain[$i];?>#shop">Shop</a></li>
                            <li><a href="https://<?php echo $domain[$i];?>#contact">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- End Mobile Menu -->
            <div class="mobile-menu d-block d-lg-none">
            </div>
            <!-- Mobile Menu -->
        </div>
    </header>
    <!-- //Header -->

    <hr>

