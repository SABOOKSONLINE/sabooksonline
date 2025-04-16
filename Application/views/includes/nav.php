<div style="width: 100%;height: 20px;background: url(../../../img/brand/02.jpg);background-size:contain;"></div>

<header class="navbar navbar-expand-md navbar-light bg-light py-2">
    <div class="container-fluid">
        <a class="navbar-brand me-2" href="/" aria-label="sabooksonline">
            <img src="../../../public/images/sabo_logo.png" alt="sabooksonline logo" width="120">
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>

                <li class="nav-item"><a class="nav-link" href="/library">Library</a></li>

                <!-- Community Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="communityDropdown" role="button" data-bs-toggle="dropdown">
                        Community
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/events">Events</a></li>
                        <li><a class="dropdown-item" href="/services">Service Providers</a></li>
                        <li><a class="dropdown-item" href="/membership">Membership Pricing</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="/about">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact Us</a></li>
            </ul>

            <!-- Search and Auth Buttons -->
            <div class="d-flex">
                <!-- Search Form -->
                <form class="d-flex me-3" action="library" id="myForm">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by Title, Authors or Publisher"
                            name="k" value="<?= $_GET['k'] ?? '' ?>">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Auth Buttons -->
                <a class="btn btn-outline-red me-2" href="/">REGISTER</a>
                <a class="btn btn-red" href="/">
                    <span class="me-2">LOGIN</span>
                    <i class="fas fa-sign-in-alt"></i>
                </a>
            </div>
        </div>
    </div>
</header>