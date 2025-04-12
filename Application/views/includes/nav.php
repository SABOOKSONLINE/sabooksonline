<header class="navbar navbar-expand navbar-light flex-md-row bd-navbar">
    <a class="navbar-brand mr-0 mr-md-2" href="/" aria-label="sabooksonline">
        <img src="../../../public/images/sabo_logo.png" alt="sabooksonline logo" width="120">
    </a>

    <ul class="navbar-nav pl-5">
        <li class="nav-item"><a class="nav-link" href="/sabooksonline/">Home</a></li>
        <li class="nav-item dropdown">
            <a class="nav-link" href="#">Books</a>
            <ul class="dropdown-content">
                <li><a href="/sabooksonline/catalogue">Book Catalogue</a></li>
                <li><a href="/sabooksonline/stores">Book Stores</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" href="#">Community</a>
            <ul class="dropdown-content">
                <li><a href="/sabooksonline/events">Events</a></li>
                <li><a href="/sabooksonline/services">Service Providers</a></li>
                <li><a href="/sabooksonline/membership">Membership Pricing</a></li>
            </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="/sabooksonline/about">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="/sabooksonline/contact">Contact Us</a></li>
    </ul>


    <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
        <li class="nav-item search-pop">
            <ul class="search-pop-content">
                <form action="library.php" id="myForm">
                    <input type="text" class="form-control input" id="main-search" placeholder="Search by Title, Authors or Publisher" name="k" value="<?php if (isset($_GET['k'])) {
                                                                                                                                                            echo $_GET['k'];
                                                                                                                                                        } ?>">
                    <button type="submit" class="submit"><i class="lni lni-search-1"></i></button>
                </form>
            </ul>
            <a class=" nav-link" href="#">
                <i class="fas fa-search"></i>
            </a>
        </li>
    </ul>

    <a class="btn btn-outline-red ml-3" href="/">REGISTER</a>
    <a class="btn btn-red ml-3" href="/">
        <span class="mr-2">LOGIN</span>
        <i class="fas fa-sign-in-alt"></i>
    </a>

</header>