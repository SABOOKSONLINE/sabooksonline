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

    case 'calculateShipping':
        // Calculate shipping fee for mobile and web
        require_once __DIR__ . '/helpers/ShippingHelper.php';
        require_once __DIR__ . '/models/CartModel.php';
        
        // Support both POST (JSON body) and GET (query params or JSON in body)
        $input = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true) ?: [];
        } else {
            // GET request - try to get from query params or JSON in body
            $rawInput = file_get_contents('php://input');
            if (!empty($rawInput)) {
                $input = json_decode($rawInput, true) ?: [];
            }
            // Also check query params
            if (empty($input) && !empty($_GET['data'])) {
                $input = json_decode($_GET['data'], true) ?: [];
            }
        }
        
        $userIdInt = (int)($input['user_id'] ?? $input['userID'] ?? 0);
        
        // Try to get user ID from userID parameter if not provided
        if ($userIdInt <= 0 && $userID) {
            require_once __DIR__ . '/models/UserModel.php';
            $userModel = new userModel($conn);
            $user = $userModel->getUserByNameOrKey($userID);
            $userIdInt = (int)($user['ADMIN_ID'] ?? 0);
        }
        
        if ($userIdInt <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid user ID', 'shipping_fee' => 60]);
            break;
        }
        
        $cartModel = new CartModel($conn);
        $address = $cartModel->getDeliveryAddress($userIdInt);
        
        // Allow address to be passed in request (for mobile)
        if (empty($address) && !empty($input['address'])) {
            $addressData = $input['address'];
            $cartModel->saveDeliveryAddress($userIdInt, $addressData);
            $address = $cartModel->getDeliveryAddress($userIdInt);
        }
        
        if (empty($address)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Delivery address required', 'shipping_fee' => 60]);
            break;
        }
        
        $cartItems = $cartModel->getCartItemsByUserId($userIdInt);
        if (empty($cartItems)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Cart is empty', 'shipping_fee' => 60]);
            break;
        }
        
        $collectionAddresses = $cartModel->getCollectionAddresses($userIdInt);
        $defaultCollectionAddress = $cartModel->getDefaultCollectionAddress($userIdInt) ?: ($collectionAddresses[0] ?? null);
        
        $shippingFee = calculateCourierGuyShipping($address, $cartItems, $cartModel, $defaultCollectionAddress);
        
        // Fallback to R60 if calculation fails
        if ($shippingFee <= 0) {
            $shippingFee = 60.0;
        }
        
        echo json_encode([
            'success' => true,
            'shipping_fee' => $shippingFee,
            'formatted' => 'R' . number_format($shippingFee, 2)
        ]);
        break;

    case 'purchaseYoco':
        // Mobile Yoco payment endpoint - now calculates shipping automatically
        require_once __DIR__ . '/helpers/ShippingHelper.php';
        
        $input = json_decode(file_get_contents('php://input'), true);
        $price = $input['price'] ?? 0;
        $deliveryType = $input['delivery_type'] ?? 'standard';
        
        if ($price <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid price']);
            break;
        }
        
        // Get user directly using UserModel
        require_once __DIR__ . '/models/UserModel.php';
        $userModel = new userModel($conn);
        $user = $userModel->getUserByNameOrKey($userID);
        
        if (empty($user)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found']);
            break;
        }
        
        $userIdInt = (int)($user['ADMIN_ID'] ?? 0);
        
        if ($userIdInt <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
            break;
        }
        
        // Get delivery address
        require_once __DIR__ . '/models/CartModel.php';
        $cartModel = new CartModel($conn);
        
        // Check if this is a retry payment (order_id provided) FIRST - before checking cart
        $existingOrderId = $input['order_id'] ?? null;
        
        if ($existingOrderId) {
            // RETRY PAYMENT: Use order's items, NOT cart items
            // Retry payment for existing order - use order's items, not cart
            $existingOrder = $cartModel->getOrderDetails((int)$existingOrderId, $userIdInt);
            
            if (empty($existingOrder)) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Order not found']);
                break;
            }
            
            if (($existingOrder['payment_status'] ?? 'pending') === 'paid') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'This order has already been paid']);
                break;
            }
            
            // Get order items from order details (not cart items) - order already exists with items
            $orderItems = $existingOrder['items'] ?? [];
            
            if (empty($orderItems)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Order has no items']);
                break;
            }
            
            // Get address from order (order already has address saved)
            $address = $cartModel->getDeliveryAddress($userIdInt);
            
            // If address doesn't exist, try to get from request
            if (empty($address)) {
                $addressData = $input['address'] ?? null;
                
                if ($addressData) {
                    $addressSaved = $cartModel->saveDeliveryAddress($userIdInt, $addressData);
                    if ($addressSaved) {
                        $address = $cartModel->getDeliveryAddress($userIdInt);
                    }
                }
                
                if (empty($address)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Delivery address is required. Please save your address first.']);
                    break;
                }
            }
            
            // Use shipping fee from order (already calculated when order was created)
            $shippingFee = floatval($existingOrder['shipping_fee'] ?? $input['shipping_fee'] ?? 0);
            
            // If shipping not set in order, use from request or fallback
            if ($shippingFee <= 0) {
                $shippingFee = floatval($input['shipping_fee'] ?? 60.0);
            }
            
            // Use order's subtotal (already calculated when order was created)
            $subtotal = floatval($existingOrder['subtotal'] ?? 0);
            
            // If subtotal not set, calculate from order items
            if ($subtotal <= 0) {
                foreach ($orderItems as $item) {
                    $subtotal += floatval($item['unit_price'] ?? $item['total_price'] ?? 0);
                }
            }
            
            // Use provided price or calculate from subtotal + shipping
            // For retry payment, use the price from request (which should match order total)
            if ($price <= 0) {
                $price = $subtotal + $shippingFee;
            }
            
            // Update order totals with calculated shipping
            $cartModel->updateOrderTotals((int)$existingOrderId, $price, $shippingFee, 'yoco');
            
            $checkout->generateYocoPaymentForMobile($price, $user, (int)$existingOrderId);
        } else {
            // NEW ORDER: Use cart items (not retry payment)
            $address = $cartModel->getDeliveryAddress($userIdInt);
            
            // If address doesn't exist, try to save it from request data
            if (empty($address)) {
                $addressData = $input['address'] ?? null;
                
                if ($addressData) {
                    $addressSaved = $cartModel->saveDeliveryAddress($userIdInt, $addressData);
                    if ($addressSaved) {
                        $address = $cartModel->getDeliveryAddress($userIdInt);
                    }
                }
                
                if (empty($address)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Delivery address is required. Please save your address first.']);
                    break;
                }
            }
            
            // Get cart items and calculate shipping fee automatically
            $cartItems = $cartModel->getCartItemsByUserId($userIdInt);
            if (empty($cartItems)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Cart is empty. Please add items to cart before checkout.']);
                break;
            }
            
            // Calculate actual shipping fee using Courier Guy API
            $collectionAddresses = $cartModel->getCollectionAddresses($userIdInt);
            $defaultCollectionAddress = $cartModel->getDefaultCollectionAddress($userIdInt) ?: ($collectionAddresses[0] ?? null);
            $shippingFee = calculateCourierGuyShipping($address, $cartItems, $cartModel, $defaultCollectionAddress);
            
            // Fallback to R60 if calculation fails
            if ($shippingFee <= 0) {
                $shippingFee = 60.0;
            }
            
            // Calculate subtotal
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += ($item['hc_price'] ?? 0) * ($item['cart_item_count'] ?? 1);
            }
            
            // Verify that price matches subtotal + shipping (or use calculated total)
            $expectedTotal = $subtotal + $shippingFee;
            if (abs($price - $expectedTotal) > 0.01) {
                // Price doesn't match, use calculated total
                $price = $expectedTotal;
            }
            
            // Create order BEFORE payment
            $orderId = $cartModel->createOrder($userIdInt);
            
            if (empty($orderId)) {
                error_log("Failed to create order for user $userIdInt. Cart items: " . count($cartItems) . ", Address: " . ($address ? 'exists' : 'missing'));
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to create order. Please try again.']);
                break;
            }
            
            // Update order totals with calculated shipping
            $cartModel->updateOrderTotals($orderId, $price, $shippingFee, 'yoco');
            
            $checkout->generateYocoPaymentForMobile($price, $user, $orderId);
        }
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
        // Get orders for a user (mobile API)
        if (!$userID) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "User ID is required"
            ]);
            break;
        }
        
        // Use CartModel directly (getOrders is a method of CartModel, not CartController)
        require_once __DIR__ . '/models/CartModel.php';
        $cartModel = new CartModel($conn);
        
        // Get all orders for the user
        $orders = $cartModel->getOrders((int)$userID);
        
        if (empty($orders)) {
            echo json_encode([
                "success" => true,
                "order" => []
            ]);
            break;
        }
        
        // Format orders for mobile app
        // CartModel->getOrders() already includes items in each order
        $formattedOrders = [];
        foreach ($orders as $order) {
            // Format items - ensure they have the right structure
            $items = [];
            if (!empty($order['items'])) {
                foreach ($order['items'] as $item) {
                    $items[] = [
                        'id' => $item['id'] ?? null,
                        'book_id' => $item['book_id'] ?? '',
                        'book_type' => $item['book_type'] ?? 'regular',
                        'item_type' => $item['book_type'] ?? 'regular', // Alias for mobile app
                        'title' => $item['title'] ?? '',
                        'author' => $item['author'] ?? '',
                        'cover' => $item['cover'] ?? '',
                        'cover_image' => $item['cover'] ?? '', // Alias
                        'quantity' => intval($item['quantity'] ?? 1),
                        'unit_price' => floatval($item['unit_price'] ?? 0),
                        'total_price' => floatval($item['total_price'] ?? 0),
                    ];
                }
            }
            
            // Calculate subtotal from items (for display)
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += floatval($item['total_price'] ?? 0);
            }
            
            $formattedOrders[] = [
                'order' => [
                    'id' => intval($order['id']),
                    'order_number' => $order['order_number'] ?? 'ORD-' . $order['id'],
                    'order_status' => $order['order_status'] ?? 'pending',
                    'payment_status' => $order['payment_status'] ?? 'pending',
                    'total_amount' => floatval($order['total_amount'] ?? 0),
                    'total' => floatval($order['total_amount'] ?? 0), // Alias for compatibility
                    'subtotal' => $subtotal,
                    'shipping_fee' => floatval($order['shipping_fee'] ?? 0),
                    'shipping_cost' => floatval($order['shipping_fee'] ?? 0), // Alias for compatibility
                    'created_at' => $order['created_at'] ?? $order['date'] ?? '',
                    // Address fields from delivery_addresses join
                    'full_name' => $order['full_name'] ?? '',
                    'company' => $order['company'] ?? '',
                    'street_address' => $order['street_address'] ?? '',
                    'street_address2' => $order['street_address2'] ?? '',
                    'local_area' => $order['local_area'] ?? '',
                    'zone' => $order['zone'] ?? '',
                    'postal_code' => $order['postal_code'] ?? '',
                    'country' => $order['country'] ?? '',
                    'phone' => $order['phone'] ?? '',
                    'email' => $order['email'] ?? '',
                    'delivery_type' => $order['delivery_type'] ?? 'residential',
                    'tracking_number' => $order['tracking_number'] ?? null,
                    'shipping_method' => $order['shipping_method'] ?? null,
                    'notes' => $order['notes'] ?? null,
                ],
                'items' => $items
            ];
        }
        
        echo json_encode([
            "success" => true,
            "order" => $formattedOrders
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
            
            // Send welcome notification if user just signed up (within last 24 hours)
            if ($success) {
                try {
                    require_once __DIR__ . '/helpers/NotificationHelper.php';
                    // Check if user registered in last 24 hours
                    $checkStmt = $conn->prepare("SELECT ADMIN_NAME, created_at FROM users WHERE ADMIN_EMAIL = ?");
                    $checkStmt->bind_param("s", $user_email);
                    $checkStmt->execute();
                    $userResult = $checkStmt->get_result();
                    $userData = $userResult->fetch_assoc();
                    $checkStmt->close();
                    
                    if ($userData && isset($userData['created_at'])) {
                        $createdAt = strtotime($userData['created_at']);
                        $hoursSinceSignup = (time() - $createdAt) / 3600;
                        
                        // If user signed up in last 24 hours, send welcome notification
                        if ($hoursSinceSignup < 24) {
                            $userName = $userData['ADMIN_NAME'] ?? 'there';
                            NotificationHelper::sendWelcomeNotification($user_email, $userName, $conn);
                        }
                    }
                } catch (Exception $e) {
                    error_log("Failed to send welcome notification on device registration: " . $e->getMessage());
                }
            }
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
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        $userEmail = $_SESSION['ADMIN_EMAIL'] ?? null;
        
        // For mobile app: try to get user email from request body or query params
        if (!$userEmail) {
            $input = json_decode(file_get_contents('php://input'), true) ?: [];
            $userEmail = $input['user_email'] ?? $_GET['user_email'] ?? null;
            
            // If still no email, try to get from userID/userKey
            if (!$userEmail && !empty($input['userID']) && !empty($input['userKey'])) {
                require_once __DIR__ . '/models/UserModel.php';
                $userModel = new userModel($conn);
                $user = $userModel->getUserByNameOrKey($input['userID']);
                if ($user && ($user['ADMIN_KEY'] ?? '') === $input['userKey']) {
                    $userEmail = $user['ADMIN_EMAIL'] ?? null;
                }
            }
        }
        
        if (!$userEmail) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            break;
        }

        // Table has target_type ('all','subscription','specific_users','publishers','customers'), not target_audience
        $sql = "SELECT n.id, n.title, n.message, n.image_url, n.action_url, n.created_at,
                       CASE WHEN nr.id IS NOT NULL THEN 1 ELSE 0 END as `read`
                FROM push_notifications n
                LEFT JOIN notification_reads nr ON n.id = nr.notification_id AND nr.user_email = ?
                WHERE (n.target_type = 'all' OR n.target_type = 'customers')
                AND n.status = 'sent'
                ORDER BY n.created_at DESC
                LIMIT 20";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $userEmail);
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
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        $userEmail = $_SESSION['ADMIN_EMAIL'] ?? null;
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $notificationId = $input['notification_id'] ?? 0;
        
        // For mobile app: try to get user email from request
        if (!$userEmail) {
            $userEmail = $input['user_email'] ?? $_GET['user_email'] ?? null;
            
            // If still no email, try to get from userID/userKey
            if (!$userEmail && !empty($input['userID']) && !empty($input['userKey'])) {
                require_once __DIR__ . '/models/UserModel.php';
                $userModel = new userModel($conn);
                $user = $userModel->getUserByNameOrKey($input['userID']);
                if ($user && ($user['ADMIN_KEY'] ?? '') === $input['userKey']) {
                    $userEmail = $user['ADMIN_EMAIL'] ?? null;
                }
            }
        }
        
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
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        $userEmail = $_SESSION['ADMIN_EMAIL'] ?? null;
        
        // For mobile app: try to get user email from request body or query params
        if (!$userEmail) {
            $input = json_decode(file_get_contents('php://input'), true) ?: [];
            $userEmail = $input['user_email'] ?? $_GET['user_email'] ?? null;
            
            // If still no email, try to get from userID/userKey
            if (!$userEmail && !empty($input['userID']) && !empty($input['userKey'])) {
                require_once __DIR__ . '/models/UserModel.php';
                $userModel = new userModel($conn);
                $user = $userModel->getUserByNameOrKey($input['userID']);
                if ($user && ($user['ADMIN_KEY'] ?? '') === $input['userKey']) {
                    $userEmail = $user['ADMIN_EMAIL'] ?? null;
                }
            }
        }
        
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
                WHERE (n.target_type = 'all' OR n.target_type = 'customers')
                AND n.status = 'sent'";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $userEmail);
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
