<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use FastRoute\RouteCollector;

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {

    // =================== Public Site Routes ===================
    $r->addRoute('GET', '/', function () {
        require "Application/views/home.php";
    });
    $r->addRoute('GET', '/home', function () {
        require "Application/views/home.php";
    });
    $r->addRoute('GET', '/about', function () {
        require "Application/views/about.php";
    });
    $r->addRoute('GET', '/contact', function () {
        require "Application/views/contact.php";
    });
    $r->addRoute('GET', '/library', function () {
        require "Application/views/library.php";
    });
    $r->addRoute('GET', '/membership', function () {
        require "Application/views/membership.php";
    });
    $r->addRoute('GET', '/library/book/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/bookpage.php";
    });
    $r->addRoute('GET', '/library/audiobook/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/books/audio/audiobook_view.php";
    });
    $r->addRoute('GET', '/read/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/readBook.php";
    });
    $r->addRoute('GET', '/media', function () {
        require "Application/views/media.php";
    });
    $r->addRoute('GET', '/email', function () {
        require "Application/views/auth/email.php";
    });
    $r->addRoute('GET', '/library/academic', function () {
        require "Application/views/academic.php";
    });
    $r->addRoute('GET', '/library/academic/{publicKey}', function ($publicKey) {
        $_GET['publicKey'] = $publicKey;
        require "Application/views/academicBookView.php";
    });
    $r->addRoute('GET', '/sell', function () {
        require "Application/views/sell.php";
    });

    // =================== Payment Routes ===================
    $r->addRoute('POST', '/payment/notify', function () {
        require "Application/views/payment/notify.php";
    });
    $r->addRoute('POST', '/checkout', function () {
        require "Application/checkout.php";
    });
    $r->addRoute('GET', '/payment/return', function () {
        require "Application/views/payment/return.php";
    });
    $r->addRoute('GET', '/payment/cancel', function () {
        require "Application/views/payment/cancel.php";
    });

    // =================== API Routes ===================
    $r->addRoute('GET', '/api/onix', function () {
        require "Application/onix.php";
    });
    $r->addRoute('GET', '/api/books', function () {
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/magazines', function () {
        $_GET['action'] = 'magazine';
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/newspapers', function () {
        $_GET['action'] = 'newspaper';
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/academicBooks', function () {
        $_GET['action'] = 'academicBooks';
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/userinfo', function () {
        $_GET['action'] = 'user';
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/creators', function () {
        $_GET['action'] = 'creators';
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/banners', function () {
        $_GET['action'] = 'banners';
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/reviews/{id}', function ($id) {
        $_GET['action'] = 'reviews';
        $_GET['id'] = $id;
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/cart/{userID}', function ($userID) {
        $_GET['action'] = 'getCart';
        $_GET['userID'] = $userID;
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/address/{userID}', function ($userID) {
        $_GET['action'] = 'getAddress';
        $_GET['userID'] = $userID;
        require "Application/api.php";
    });

    // =================== Mobile-compatible Cart & Address endpoints ===================
    // Mobile: add to cart (expects JSON body with admin_id, book_id, quantity, etc.)
    $r->addRoute('POST', '/api/cart/add', function () {
        $_GET['action'] = 'addBookMobile';
        require "Application/api.php";
    });

    // Mobile: update quantity by cart row id
    $r->addRoute('PUT', '/api/cart/update/{cartId}', function ($cartId) {
        $_GET['action'] = 'updateCartByCartId';
        $_GET['cartId'] = $cartId;
        require "Application/api.php";
    });

    // Mobile: remove item by cart row id
    $r->addRoute('DELETE', '/api/cart/remove/{cartId}', function ($cartId) {
        $_GET['action'] = 'removeCartByCartId';
        $_GET['cartId'] = $cartId;
        require "Application/api.php";
    });

    // Mobile: save address directly to /api/address/{userID} with address fields in JSON
    $r->addRoute('POST', '/api/address/{userID}', function ($userID) {
        $_GET['action'] = 'saveAddressMobile';
        $_GET['userID'] = $userID;
        require "Application/api.php";
    });

    // Mobile: update address by address row id
    $r->addRoute('PUT', '/api/address/{addressId}', function ($addressId) {
        $_GET['action'] = 'updateAddressById';
        $_GET['addressId'] = $addressId;
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/order/{userID}', function ($userID) {
        $_GET['action'] = 'getOrderDetails';
        $_GET['userID'] = $userID;
        require "Application/api.php";
    });

    $r->addRoute('POST', '/api/analytics', function () {
        $_GET['action'] = 'analytics';
        require "Application/api.php";
    });

    // Mobile device token registration
    $r->addRoute('POST', '/api/mobile/register-token', function () {
        $_GET['action'] = 'register_token';
        require "mobile_api.php";
    });

    // User notification API endpoints
    $r->addRoute('GET', '/api/user/notifications', function () {
        $_GET['action'] = 'userNotifications';
        require "Application/api.php";
    });

    $r->addRoute('POST', '/api/user/notifications/read', function () {
        $_GET['action'] = 'markNotificationRead';
        require "Application/api.php";
    });

    $r->addRoute('POST', '/api/user/notifications/read-all', function () {
        $_GET['action'] = 'markAllNotificationsRead';
        require "Application/api.php";
    });

    // Enhanced mobile banners API with better filtering - removed to avoid class conflicts
    // Access directly via /API/mobile_banners.php?screen={screen}

    $r->addRoute('GET', '/api/audio/chapters/{a_id}', function ($a_id) {
        $_GET['action'] = 'audio';
        $_GET['a_id'] = $a_id;
        require "Application/api.php";
    });

    $r->addRoute('POST', '/api/cart/add/{userID}', function ($userID) {
        $_GET['action'] = 'addBook';
        require "Application/api.php";
    });

    $r->addRoute('POST', '/api/address/add/{userID}', function ($userID) {
        $_GET['action'] = 'addAddress';
        require "Application/api.php";
    });

    $r->addRoute('POST', '/api/cart/delete/{userID}', function ($userID) {
        $_GET['action'] = 'deleteBook';
        require "Application/api.php";
    });

    $r->addRoute('POST', '/api/purchase/{userID}', function ($userID) {
        $_GET['action'] = 'purchase';
        $_GET['userID'] = $userID;

        require "Application/api.php";
    });



    $r->addRoute('POST', '/api/login', function () {
        $_GET['action'] = 'login';
        require "Application/api.php";
    });

    $r->addRoute('POST', '/api/signup', function () {
        $_GET['action'] = 'signup';
        require "Application/api.php";
    });


    // =================== Dashboard Routes ===================
    // --- Main Dashboard ---
    $r->addRoute('GET', '/dashboards', function () {
        require "Dashboard/index.php";
    });

    // --- cloudinary save content ---
    $r->addRoute('POST', '/includes/save-pdf-url', function () {
        require __DIR__ . "/Dashboard/views/includes/save-pdf-url.php";
    });

    // --- Book Listings ---
    $r->addRoute('GET', '/dashboards/add/listings', function () {
        require "Dashboard/views/add/add_book.php";
    });
    $r->addRoute('GET', '/dashboards/listings', function () {
        require "Dashboard/views/book_listings.php";
    });
    $r->addRoute('POST', '/dashboards/listings/insert', function () {
        $_GET['action'] = 'insert';
        require "Dashboard/handlers/book_handler.php";
    });
    $r->addRoute('POST', '/dashboards/listings/update/{id}', function ($id) {
        $_GET['action'] = 'update';
        $_GET['id'] = $id;
        require "Dashboard/handlers/book_handler.php";
    });
    $r->addRoute('GET', '/dashboards/listings/{id}', function ($id) {
        $_GET['id'] = $id;
        require "Dashboard/views/add/add_book.php";
    });
    $r->addRoute('GET', '/dashboards/listings/delete/{id}', function ($id) {
        $_GET['action'] = 'delete';
        $_GET['id'] = $id;
        require "Dashboard/handlers/book_handler.php";
    });

    // --- Media ---
    $r->addRoute('GET', '/dashboards/media', function () {
        require "Dashboard/views/manage_media.php";
    });
    $r->addRoute('GET', '/dashboards/onix', function () {
        require "Dashboard/views/add/add_onix.php";
    });
    $r->addRoute('GET', '/dashboards/onixx', function () {
        require "Dashboard/views/onix.php";
    });
    $r->addRoute('GET', '/dashboards/add/media', function () {
        require "Dashboard/views/add/add_media.php";
    });

    $r->addRoute('POST', '/dashboards/media/magazine/insert', function () {
        $_GET['action'] = 'insert';
        $_GET['type'] = 'magazine';
        require "Dashboard/handlers/media_handler.php";
    });
    $r->addRoute('POST', '/dashboards/media/newspaper/insert', function () {
        $_GET['action'] = 'insert';
        $_GET['type'] = 'newspaper';
        require "Dashboard/handlers/media_handler.php";
    });

    $r->addRoute('POST', '/dashboards/media/magazine/update/{id}', function ($id) {
        $_GET['action'] = 'update';
        $_GET['type'] = 'magazine';
        $_GET['id'] = $id;
        require "Dashboard/handlers/media_handler.php";
    });
    $r->addRoute('POST', '/dashboards/media/newspaper/update/{id}', function ($id) {
        $_GET['action'] = 'update';
        $_GET['type'] = 'newspaper';
        $_GET['id'] = $id;
        require "Dashboard/handlers/media_handler.php";
    });

    $r->addRoute('GET', '/dashboards/media/magazine/delete/{id}', function ($id) {
        $_GET['action'] = 'delete';
        $_GET['type'] = 'magazine';
        $_GET['id'] = $id;
        require "Dashboard/handlers/media_handler.php";
    });
    $r->addRoute('GET', '/dashboards/media/newspaper/delete/{id}', function ($id) {
        $_GET['action'] = 'delete';
        $_GET['type'] = 'newspaper';
        $_GET['id'] = $id;
        require "Dashboard/handlers/media_handler.php";
    });

    $r->addRoute('GET', '/media/magazines/{publicKey}', function ($publicKey) {
        $_GET['publicKey'] = $publicKey;
        require "Application/views/media/magazineView.php";
    });
    $r->addRoute('GET', '/media/newspapers/{publicKey}', function ($publicKey) {
        $_GET['publicKey'] = $publicKey;
        require "Application/views/media/newspaperView.php";
    });

    // --- Academic ---
    $r->addRoute('GET', '/dashboards/academic/books', function () {
        require "Dashboard/views/manage_academic.php";
    });
    $r->addRoute('GET', '/dashboards/add/academic', function () {
        require "Dashboard/views/add/add_academic.php";
    });

    $r->addRoute('GET', '/dashboards/update/academic/{id}', function ($id) {
        $_GET["id"] = $id;
        require "Dashboard/views/add/add_academic.php";
    });

    $r->addRoute('POST', '/dashboards/academic/book/insert', function () {
        $_GET['action'] = 'insert';
        require "Dashboard/handlers/academic_book_handler.php";
    });
    $r->addRoute('POST', '/dashboards/academic/book/update/{id}', function ($id) {
        $_GET['action'] = 'update';
        $_GET['id'] = $id;
        require "Dashboard/handlers/academic_book_handler.php";
    });
    $r->addRoute('GET', '/dashboards/academic/book/delete/{id}', function ($id) {
        $_GET['action'] = 'delete';
        $_GET['id'] = $id;
        require "Dashboard/handlers/academic_book_handler.php";
    });

    // --- Events ---
    $r->addRoute('GET', '/dashboards/add/event', function () {
        require "Dashboard/views/add/add_event.php";
    });
    $r->addRoute('GET', '/dashboards/events', function () {
        require "Dashboard/views/manage_events.php";
    });
    $r->addRoute('POST', '/dashboards/events/insert', function () {
        $_GET['action'] = 'insert';
        require "Dashboard/handlers/event_handler.php";
    });
    $r->addRoute('POST', '/dashboards/events/update/{id}', function ($id) {
        $_GET['action'] = 'update';
        $_GET['id'] = $id;
        require "Dashboard/handlers/event_handler.php";
    });
    $r->addRoute('GET', '/dashboards/events/delete/{id}', function ($id) {
        $_GET['action'] = 'delete';
        $_GET['id'] = $id;
        require "Dashboard/handlers/event_handler.php";
    });
    $r->addRoute('GET', '/dashboards/events/{id}', function ($id) {
        $_GET['id'] = $id;
        require "Dashboard/views/add/add_event.php";
    });

    // --- Services --- 
    // $r->addRoute('GET', '/dashboards/add/service', function () {
    //     require "Dashboard/views/add/add_services.php";
    // });
    // $r->addRoute('GET', '/dashboards/services', function () {
    //     require "Dashboard/views/manage_services.php";
    // });
    // $r->addRoute('POST', '/dashboards/services/insert', function () {
    //     $_GET['action'] = 'insert';
    //     require "Dashboard/handlers/service_handler.php";
    // });
    // $r->addRoute('POST', '/dashboards/services/update/{id}', function ($id) {
    //     $_GET['action'] = 'update';
    //     $_GET['id'] = $id;
    //     require "Dashboard/handlers/service_handler.php";
    // });
    // $r->addRoute('GET', '/dashboards/services/delete/{id}', function ($id) {
    //     $_GET['action'] = 'delete';
    //     $_GET['id'] = $id;
    //     require "Dashboard/handlers/service_handler.php";
    // });
    // $r->addRoute('GET', '/dashboards/services/{id}', function ($id) {
    //     $_GET['id'] = $id;
    //     require "Dashboard/views/add/add_services.php";
    // });

    // --- Reviews, Profile, Billing ---
    $r->addRoute('GET', '/dashboards/reviews', function () {
        require "Dashboard/views/manage_reviews.php";
    });
    $r->addRoute('GET', '/dashboards/profile', function () {
        require "Dashboard/views/manage_profile.php";
    });
    $r->addRoute('POST', '/dashboards/profile/update/{id}', function ($id) {
        $_GET['action'] = 'update';
        $_GET['id'] = $id;
        require "Dashboard/handlers/user_handler.php";
    });
    $r->addRoute('GET', '/dashboards/billing', function () {
        require "Dashboard/views/account_billing.php";
    });

    // --- Bookshelf & Audiobooks ---
    $r->addRoute('GET', '/dashboards/bookshelf', function () {
        require "Dashboard/views/bookshelf.php";
    });
    $r->addRoute('GET', '/dashboards/audiobooks', function () {
        require "Dashboard/views/audiobooks.php";
    });

    // --- Audiobook Handling Routes ---
    $r->addRoute('POST', '/dashboards/listings/insertAudio', function () {
        require "Dashboard/handlers/audiobook_handler.php";
    });
    $r->addRoute('POST', '/dashboards/listings/updateAudio/{id}', function ($id) {
        $_GET['id'] = $id;
        $_GET['action'] = 'updateAudio';
        require "Dashboard/handlers/audiobook_handler.php";
    });
    $r->addRoute('GET', '/dashboards/listings/deleteAudio/{id}', function ($id) {
        $_GET['id'] = $id;
        $_GET['action'] = 'deleteAudio';
        require "Dashboard/handlers/audiobook_handler.php";
    });
    $r->addRoute('GET', '/dashboards/add/audiobook/{id}', function ($id) {
        $_GET['id'] = $id;
        require "Dashboard/views/add/add_audiobook.php";
    });

    // --- Audiobook chapter Handling Routes ---
    $r->addRoute('POST', '/dashboards/listings/insertAudioChapter', function () {
        require "Dashboard/handlers/audiobook_chapter_handler.php";
    });
    $r->addRoute('POST', '/dashboards/listings/updateAudioChapter/{id}', function ($id) {
        $_GET['id'] = $id;
        $_GET['action'] = 'updateAudioChapter';
        require "Dashboard/handlers/audiobook_chapter_handler.php";
    });
    $r->addRoute('GET', '/dashboards/listings/deleteAudioChapter/{chapterId}', function ($chapterId) {
        $_GET['id'] = $chapterId;
        $_GET['action'] = 'deleteAudioChapter';
        require "Dashboard/handlers/audiobook_chapter_handler.php";
    });


    // --- Reset Users Password Routes ---> note this is temp and will be deleted
    $r->addRoute('GET', '/dashboards/reset_password', function () {
        require "Dashboard/views/reset_user_password.php";
    });
    $r->addRoute('POST', '/dashboards/reset_password/handler/{adminId}', function ($adminId) {
        $_GET['adminId'] = $adminId;
        require "Dashboard/handlers/reset_users_pass.php";
    });

    // --- Audiobook chapter Handling Routes ---
    $r->addRoute('POST', '/dashboards/listings/insertSampleAudio', function () {
        require "Dashboard/handlers/audiobook_sample_handler.php";
    });
    $r->addRoute('POST', '/dashboards/listings/updateSampleAudio/{id}', function ($id) {
        $_GET['id'] = $id;
        $_GET['action'] = 'update';
        require "Dashboard/handlers/audiobook_sample_handler.php";
    });
    $r->addRoute('GET', '/dashboards/listings/deleteSampleAudio/{id}', function ($id) {
        $_GET['id'] = $id;
        $_GET['action'] = 'delete';
        require "Dashboard/handlers/audiobook_sample_handler.php";
    });

    // =================== Creator, Provider, Gallery, Services, Events (Public) ===================
    $r->addRoute('GET', '/creators/creator/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/creatorpage.php";
    });
    // $r->addRoute('GET', '/providers', function () {
    //     require "Application/views/providers.php";
    // });
    $r->addRoute('GET', '/gallery', function () {
        require "Application/views/gallery.php";
    });
    $r->addRoute('GET', '/services', function () {
        require "Application/views/ourServices.php";
    });
    $r->addRoute('GET', '/events', function () {
        require "Application/views/events.php";
    });
    $r->addRoute('GET', '/events/event/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/eventpage.php";
    });

    // =================== Documentation Routes ===================
    $r->addRoute('GET', '/content-removal', function () {
        require __DIR__ . "/Application/views/documentations/content-removal.php";
    });
    $r->addRoute('GET', '/popia-compliance', function () {
        require __DIR__ . "/Application/views/documentations/popia-compliance.php";
    });
    $r->addRoute('GET', '/privacy-policy', function () {
        require __DIR__ . "/Application/views/documentations/privacy-policy.php";
    });
    $r->addRoute('GET', '/termination-policy', function () {
        require __DIR__ . "/Application/views/documentations/termination-policy.php";
    });
    $r->addRoute('GET', '/terms-and-conditions', function () {
        require __DIR__ . "/Application/views/documentations/terms-and-conditions.php";
    });
    $r->addRoute('GET', '/refund-policy', function () {
        require __DIR__ . "/Application/views/documentations/refund-policy.php";
    });

    // =================== Auth Routes ===================
    $r->addRoute('GET', '/login', function () {
        require __DIR__ . "/Application/views/auth/login.php";
    });
    $r->addRoute('GET', '/logout', function () {
        require __DIR__ . "/Application/views/auth/logout.php";
    });
    $r->addRoute('POST', '/formLogin', function () {
        require __DIR__ . "/Application/views/includes/loginWithForm.php";
    });
    $r->addRoute('GET', '/signup', function () {
        require __DIR__ . "/Application/views/auth/signup.php";
    });
    $r->addRoute('GET', '/forgot-password', function () {
        require __DIR__ . "/Application/views/auth/forgot_password.php";
    });
    $r->addRoute('GET', '/reset-password/{token}', function ($token) {
        $_GET['token'] = $token;
        require __DIR__ . "/Application/views/auth/reset_password.php";
    });
    $r->addRoute('POST', '/auth/signup-handler', function () {
        require __DIR__ . "/Application/views/auth/signup_handler.php";
    });
    $r->addRoute('POST', '/auth/login-handler', function () {
        require __DIR__ . "/Application/views/auth/login_handler.php";
    });
    $r->addRoute('POST', '/auth/reset-password-handler', function () {
        require __DIR__ . "/Application/views/auth/reset_password_handler.php";
    });
    $r->addRoute('POST', '/auth/forgot-password-handler', function () {
        require __DIR__ . "/Application/views/auth/forgot_password_handler.php";
    });
    $r->addRoute('GET', '/verify/{token}', function ($token) {
        $_GET['token'] = $token;
        require __DIR__ . "/Application/views/auth/verify.php";
    });
    $r->addRoute('GET', '/registration_success', function () {
        require __DIR__ . "/Application/views/auth/registration_success.php";
    });

    // =================== Google OAuth Callback ===================
    $r->addRoute('GET', '/google/callback', function () {
        require  "Application/google/callback.php";
    });

    // =================== File Download & Views ===================
    $r->addRoute('GET', '/view/pdfs/{filename}', function ($filename) {
        $safeFilename = basename($filename); // prevent directory traversal
        $path = __DIR__ . '/cms-data/book-pdfs/' . $safeFilename;

        if (!file_exists($path)) {
            http_response_code(404);
            echo "PDF not found.";
            return;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $safeFilename . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    });

    // =================== Tanci API ===================
    $r->addRoute('GET', '/api/read_remote_data/{apiKey}/{userKey}', function ($apiKey, $userKey) {
        $_GET['api_key'] = $apiKey;
        $_GET['userkey'] = $userKey;
        require __DIR__ . "/API/read_remote_data.php";
    });

    // =================== NewsLetter Handler ===================
    $r->addRoute('POST', '/newsletter-handler', function () {
        require __DIR__ . "/Application/handlers/newsletterHandler.php";
    });

    // =================== Review Handler ===================
    $r->addRoute('POST', '/reviews-handler', function () {
        require __DIR__ . "/Application/handlers/reviewsHandler.php";
    });


    // =================== Admin Dashboard ===================
    $r->addRoute('POST', '/admin/process', function () {
        require  "Admin/Helpers/process.php";
    });

    $r->addRoute('GET', '/admin/process', function () {
        require  "Admin/Helpers/process.php";
    });

    $r->addRoute('GET', '/admin', function () {
        require  "Admin/index.php";
    });

    $r->addRoute('GET', '/admin/analytics', function () {
        require  "Admin/index.php";
    });

    $r->addRoute('GET', '/admin/pages/home', function () {
        require  "Admin/index.php";
    });

    $r->addRoute('GET', '/admin/users', function () {
        require  "Admin/index.php";
    });

    $r->addRoute('GET', '/admin/orders', function () {
        require  "Admin/index.php";
    });

    $r->addRoute('GET', '/admin/purchases', function () {
        require  "Admin/index.php";
    });

    // Mobile Management Routes
    $r->addRoute('GET', '/admin/mobile/banners', function () {
        require  "Admin/index.php";
    });

    $r->addRoute('GET', '/admin/mobile/notifications', function () {
        require  "Admin/index.php";
    });

    $r->addRoute('GET', '/admin/mobile/notifications/send', function () {
        require  "Admin/index.php";
    });

    $r->addRoute('POST', '/admin/mobile/notifications/send', function () {
        require  "Admin/index.php";
    });

    // Mobile Banner Management Actions
    $r->addRoute('POST', '/admin/mobile/banners/add', function () {
        require  "Admin/Helpers/process_mobile.php";
    });

    $r->addRoute('POST', '/admin/mobile/banners/edit/{id}', function ($id) {
        $_GET["id"] = $id;
        $_GET["action"] = "edit";
        require  "Admin/Helpers/process_mobile.php";
    });

    $r->addRoute('GET', '/admin/mobile/banners/delete/{id}', function ($id) {
        $_GET["id"] = $id;
        $_GET["action"] = "delete";
        $_GET["type"] = "banner";
        require  "Admin/Helpers/process_mobile.php";
    });

    $r->addRoute('GET', '/admin/mobile/banners/toggle/{id}', function ($id) {
        $_GET["id"] = $id;
        $_GET["action"] = "toggle";
        $_GET["type"] = "banner";
        require  "Admin/Helpers/process_mobile.php";
    });

    // Mobile Notification Management Actions  
    $r->addRoute('GET', '/admin/mobile/notifications/delete/{id}', function ($id) {
        $_GET["id"] = $id;
        $_GET["action"] = "delete";
        $_GET["type"] = "notification";
        require  "Admin/Helpers/process_mobile.php";
    });

    $r->addRoute('GET', '/admin/users/impersonate/{id}', function ($id) {
        $_GET["id"] = $id;
        require  "Admin/Helpers/impersonate.php";
    });

    $r->addRoute('GET', '/admin/users/delete/{id}', function ($id) {
        $_GET["id"] = $id;
        require  "Admin/Helpers/remove.php";
    });

    $r->addRoute('POST', '/admin/pages/home/banners', function () {
        require  "Admin/Helpers/process_banners.php";
    });

    $r->addRoute('GET', '/admin/pages/home/banners/{id}', function ($id) {
        $_GET["id"] = $id;
        require  "Admin/Helpers/process_banners.php";
    });

    $r->addRoute('POST', '/admin/orders/update-status', function () {
        require "Admin/Helpers/process_order.php";
    });

    $r->addRoute('GET', '/401', function () {
        require  "Application/views/401.php";
    });
    $r->addRoute('GET', '/405', function () {
        require  "Application/views/405.php";
    });

    // =================== Cart ===================
    $r->addRoute('GET', '/cart', function () {
        require "Application/views/cart.php";
    });
    $r->addRoute('POST', '/cart/add', function () {
        require "Application/handlers/cartHandler.php";
    });
    $r->addRoute('POST', '/cart/update', function () {
        require "Application/handlers/cartHandler.php";
    });
    $r->addRoute('POST', '/cart/remove', function () {
        require "Application/handlers/cartHandler.php";
    });
    $r->addRoute('GET', '/cart/checkout', function () {
        require "Application/views/cartCheckout.php";
    });
    $r->addRoute('POST', '/cart-checkout/process', function () {
        require "Application/handlers/checkoutHandler.php";
    });
    $r->addRoute('POST', '/cart-checkout/address', function () {
        require "Application/handlers/saveDeliveryAddressHandler.php";
    });
    $r->addRoute('POST', '/checkout-book', function () {
        require "Application/handlers/checkoutPayfast.php";
    });
});

// Fetch method and URI from server
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Strip trailing slash (except for root)
if ($uri !== '/' && substr($uri, -1) === '/') {
    $uri = rtrim($uri, '/');
}

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        require "Application/views/404.php";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        require "Application/views/405.php";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        call_user_func_array($handler, $vars);
        break;
}
