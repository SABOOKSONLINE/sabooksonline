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

        if ($password === 'sabobo$$25') {
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

    case 'addBook':
        $input = json_decode(file_get_contents('php://input'), true);
        $bookID = $input['bookID'];
        $password = $input['qty'];
        $cart->addCartItem($userID,$bookID, $qty);
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
      
    case 'getAddress':
        $address = $cart->getDeliveryAddress($userID);
        echo json_encode([
        "success" => true,
        "address" => $address
    ]);
        break;

    case 'getOrderDetails':
        $order = $cart->getOrderDetails($userID);
        echo json_encode([
        "success" => true,
        "order" => $order
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


    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
        break;
}
