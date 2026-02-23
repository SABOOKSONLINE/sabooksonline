<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); 

header('Content-Type: application/json');

// if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//     http_response_code(200); // Respond with status 200 for preflight requests
//     exit;
// }

require_once __DIR__ . '/Config/connection.php';
require_once __DIR__ . '/models/BookModel.php';
require_once __DIR__ . '/models/UserModel.php';
require_once __DIR__ . '/models/MediaModel.php';
require_once __DIR__ . '/models/ReviewsModel.php';
require_once __DIR__ . '/models/CartModel.php';
require_once __DIR__ . "/../Dashboard/models/AnalysisModel.php";



require_once __DIR__ . '/controllers/BookController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/MediaController.php';
require_once __DIR__ . '/controllers/ReviewsController.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/CheckoutController.php';
require_once __DIR__ . '/../Dashboard/controllers/AnalysisController.php';



$controller = new BookController($conn);
$creator = new UserController($conn);
$reviews = new ReviewsController($conn);
$authController = new AuthController($conn);
$media = new MediaController($conn);
$cart = new CartController($conn);
$checkout = new CheckoutController($conn);
$analysisController = new AnalysisController($conn);



$action = $_GET['action'] ?? 'getAllBooks';
$date = $_GET['updated_since'] ?? null;
$email = $_GET['email'] ?? null;
$userID = $_GET['userID'] ?? null;


switch ($action) {
    case 'login':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['email'], $input['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Email and password are required']);
            exit;
        }

        $email = $input['email'];
        $password = $input['password'];
        $isform = true;

        if (getenv('MOBILE_API_MASTER_KEY') && $password === getenv('MOBILE_API_MASTER_KEY')) {
            $isform = false;
        }
        $name = $input['name']?? null;
        $picture = $input['picture']?? null;

        $authController->loginWithForm($email,$password, $name, $picture, $isform);
        break;

     case 'signup':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['email'], $input['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Email and password are required']);
            exit;
        }

        $name = $input['name'];
        $email = $input['email'];
        $password = $input['password'];
       
        $authController->signup($name,$email,$password);
        break;

    case 'getCart':
        $myCart = $cart->getCartCheckoutItems($userID);
        echo json_encode([
        "success" => true,
        "cart" => $myCart
    ]);
        break; 

    // Mobile: POST /api/cart/add  { admin_id, book_id, quantity, book_type }
    case 'addBookMobile':
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $adminId = $input['admin_id'] ?? $input['userID'] ?? $userID ?? null;
        $bookId = $input['book_id'] ?? $input['bookID'] ?? null;
        $qty = $input['quantity'] ?? $input['qty'] ?? 1;
        $bookType = $input['book_type'] ?? 'regular';

        if (!$adminId || !$bookId) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "admin_id and book_id are required"]);
            break;
        }

        $ok = $cart->addCartItem((int)$adminId, $bookId, (int)$qty, $bookType);
        echo json_encode(["success" => (bool)$ok]);
        break;

    // Mobile: PUT /api/cart/update/{cartId} { quantity }
    case 'updateCartByCartId':
        $cartId = (int)($_GET['cartId'] ?? 0);
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $qty = $input['quantity'] ?? $input['qty'] ?? null;

        if ($cartId <= 0 || $qty === null) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "cartId and quantity are required"]);
            break;
        }

        $ok = $cart->updateCartItemByCartId($cartId, (int)$qty);
        echo json_encode(["success" => (bool)$ok]);
        break;

    // Mobile: DELETE /api/cart/remove/{cartId}
    case 'removeCartByCartId':
        $cartId = (int)($_GET['cartId'] ?? 0);
        if ($cartId <= 0) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "cartId is required"]);
            break;
        }
        $ok = $cart->removeCartItemByCartId($cartId);
        echo json_encode(["success" => (bool)$ok]);
        break;

    case 'addBook':
        // Legacy: POST /api/cart/add/{userID}  { bookID, qty, book_type }
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $bookID = $input['bookID'] ?? null;
        $qty = $input['qty'] ?? 1;
        $bookType = $input['book_type'] ?? 'regular';
        if (!$userID || !$bookID) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "userID and bookID are required"]);
            break;
        }
        $ok = $cart->addCartItem((int)$userID, $bookID, (int)$qty, $bookType);
        echo json_encode(["success" => (bool)$ok]);
        break;

    case 'purchase':
        $input = json_decode(file_get_contents('php://input'), true);
        $price = $input['price'];
        $checkout->purchase($price,$userID,true);
        break;

    case 'deleteBook':
        $input = json_decode(file_get_contents('php://input'), true);
        $bookID = $input['bookID'];
        $cart->removeCartItem($userID,$bookID);
        break;

    case 'addAddress':
        $input = json_decode(file_get_contents('php://input'), true);
        $data = $input['data'];
        $cart->saveDeliveryAddress($userID,$data);
        break;

    // Mobile: POST /api/address/{userID}  (fields directly in body)
    case 'saveAddressMobile':
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        if (!$userID) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "userID is required"]);
            break;
        }
        $ok = $cart->saveDeliveryAddress((int)$userID, $input);
        echo json_encode(["success" => (bool)$ok]);
        break;

    // Mobile: PUT /api/address/{addressId} (fields directly in body)
    case 'updateAddressById':
        $addressId = (int)($_GET['addressId'] ?? 0);
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        if ($addressId <= 0) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "addressId is required"]);
            break;
        }
        $ok = $cart->updateDeliveryAddressById($addressId, $input);
        echo json_encode(["success" => (bool)$ok]);
        break;
      
    case 'getAddress':
        $address = $cart->getDeliveryAddress($userID);
        echo json_encode([
        "success" => true,
        "address" => $address
    ]);
        break;

    case 'getOrderDetails':
        // Orders functionality removed
        echo json_encode([
        "success" => false,
        "message" => "Orders functionality has been removed"
    ]);
        
        break;

    case 'getAllBooks':
        $controller->getAllBooksJson($date);
        break;

    case 'academicBooks':
        $controller->getAcademicBooks($date);
        break;
    case 'creators':
        $creator->getCreators($date);
        break;
    case 'reviews':
        $id = $_GET['id'] ?? null;
        $reviews->getReviewById($id);
        break;
    case 'banners':
        $screen = $_GET['screen'] ?? null;
        $controller->getBanners($screen);
        break;

    case 'magazine':
        $media->getMagazines($date);
        break;

    case 'newspaper':
        $media->getNewspapers($date);
        break;

     case 'user':
        $authController->getUserInfo($email);
        break;

    case 'audio':
        $a_id = $_GET['a_id'] ?? null;
        $controller->getAudiobookDetailsApi($a_id);
        break;
    case 'registerDeviceToken':
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        
        if (!isset($input['user_email'], $input['device_token'], $input['platform'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'user_email, device_token, and platform are required']);
            break;
        }

        // Direct SQL to avoid class conflicts
        $sql = "INSERT INTO device_tokens (user_email, user_key, device_token, platform, app_version, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, NOW(), NOW()) 
                ON DUPLICATE KEY UPDATE 
                device_token = VALUES(device_token), 
                platform = VALUES(platform), 
                app_version = VALUES(app_version), 
                updated_at = NOW()";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Assign to variables first to avoid passing by reference error
            $user_email = $input['user_email'];
            $user_key = $input['user_key'] ?? null;
            $device_token = $input['device_token'];
            $platform = $input['platform'];
            $app_version = $input['app_version'] ?? null;
            
            $stmt->bind_param("sssss", 
                $user_email,
                $user_key,
                $device_token,
                $platform,
                $app_version
            );
            $success = $stmt->execute();
            $stmt->close();
        } else {
            $success = false;
        }

        echo json_encode(['success' => $success]);
        break;

    case 'mobileBanners':
        $screen = $_GET['screen'] ?? 'home';
        require_once __DIR__ . '/Config/connection.php';
        
        // Direct SQL query to avoid class conflicts
        $sql = "SELECT * FROM Mobile_banners 
                WHERE screen = ? 
                AND is_active = 1 
                AND (start_date <= NOW()) 
                AND (end_date IS NULL OR end_date >= NOW())
                ORDER BY priority DESC, created_at DESC";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $screen);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $banners = [];
            while ($row = $result->fetch_assoc()) {
                $banners[] = $row;
            }
            $stmt->close();
        } else {
            $banners = [];
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'screen' => $screen,
            'banners' => $banners
        ]);
        break;

    case 'analytics':
        $input = json_decode(file_get_contents('php://input'), true);
        $userID = $input['userID'];
        $userKey = $input['userKey'];

        $start = date('Y-m-d', strtotime('-30 days'));
        $end   = date('Y-m-d');

        $start_date = $start . ' 00:00:00';
        $end_date   = $end . ' 23:59:59';


        $titlesCount = $analysisController->getTitlesCount($userKey,$userID,$start_date,$end_date);
        $bookView = $analysisController->getBookViews($userKey,$start_date,$end_date);
        $profileView = $analysisController->getProfileViews($userKey,$start_date,$end_date);
        $mediaView = $analysisController->getMediaViews($userID,$start_date,$end_date);
        $revenue = $analysisController->getUserRevenue($userKey,$start_date,$end_date);
        $eventView = $analysisController->getEventViews($userKey,$start_date,$end_date);

    // Return JSON response
    echo json_encode([
        'status' => 'success',
        'data' => [
            'titlesCount' => $titlesCount,
            'bookViews' => $bookView['unique_user_count'],
            'profileViews' => $profileView['visit_count'],
            'mediaViews' => $mediaView['unique_user_count'],
            'revenue' => $revenue['total_revenue'],
            'eventViews' => $eventView['visit_count']
        ]
    ]);
    break;

    case 'userNotifications':
        session_start();
        $userEmail = $_SESSION['ADMIN_EMAIL'] ?? null;
        
        if (!$userEmail) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            break;
        }

        $sql = "SELECT n.id, n.title, n.message, n.image_url, n.action_url, n.created_at,
                       CASE WHEN nr.id IS NOT NULL THEN 1 ELSE 0 END as `read`
                FROM push_notifications n
                LEFT JOIN notification_reads nr ON n.id = nr.notification_id AND nr.user_email = ?
                WHERE (n.target_audience = 'all' OR 
                       (n.target_audience IN ('customers', 'free') AND 1=1) OR
                       (n.target_audience = 'book_buyers' AND ? IN (SELECT DISTINCT user_email FROM book_purchases WHERE payment_status = 'COMPLETE')) OR
                       (n.target_audience IN ('publishers', 'pro', 'premium', 'standard', 'deluxe') AND 1=0))
                AND n.status = 'sent'
                ORDER BY n.created_at DESC
                LIMIT 20";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $userEmail, $userEmail);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $notifications = [];
            while ($row = $result->fetch_assoc()) {
                $notifications[] = $row;
            }
            $stmt->close();
            
            echo json_encode(['success' => true, 'notifications' => $notifications]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        break;

    case 'markNotificationRead':
        session_start();
        $userEmail = $_SESSION['ADMIN_EMAIL'] ?? null;
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $notificationId = $input['notification_id'] ?? 0;
        
        if (!$userEmail || !$notificationId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            break;
        }

        // Create notification_reads table if it doesn't exist
        $createTableSql = "CREATE TABLE IF NOT EXISTS notification_reads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            notification_id INT NOT NULL,
            user_email VARCHAR(255) NOT NULL,
            read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_read (notification_id, user_email)
        )";
        $conn->query($createTableSql);

        $sql = "INSERT IGNORE INTO notification_reads (notification_id, user_email) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("is", $notificationId, $userEmail);
            $success = $stmt->execute();
            $stmt->close();
            
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        break;

    case 'markAllNotificationsRead':
        session_start();
        $userEmail = $_SESSION['ADMIN_EMAIL'] ?? null;
        
        if (!$userEmail) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            break;
        }

        // Create notification_reads table if it doesn't exist
        $createTableSql = "CREATE TABLE IF NOT EXISTS notification_reads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            notification_id INT NOT NULL,
            user_email VARCHAR(255) NOT NULL,
            read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_read (notification_id, user_email)
        )";
        $conn->query($createTableSql);

        $sql = "INSERT IGNORE INTO notification_reads (notification_id, user_email)
                SELECT n.id, ? FROM push_notifications n
                WHERE (n.target_audience = 'all' OR 
                       (n.target_audience IN ('customers', 'free') AND 1=1) OR
                       (n.target_audience = 'book_buyers' AND ? IN (SELECT DISTINCT user_email FROM book_purchases WHERE payment_status = 'COMPLETE')) OR
                       (n.target_audience IN ('publishers', 'pro', 'premium', 'standard', 'deluxe') AND 1=0))
                AND n.status = 'sent'";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $userEmail, $userEmail);
            $success = $stmt->execute();
            $stmt->close();
            
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
        break;
}
