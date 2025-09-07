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


require_once __DIR__ . '/controllers/BookController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/MediaController.php';
require_once __DIR__ . '/controllers/ReviewsController.php';



$controller = new BookController($conn);
$creator = new UserController($conn);
$reviews = new ReviewsController($conn);
$authController = new AuthController($conn);
$media = new MediaController($conn);


$action = $_GET['action'] ?? 'getAllBooks';
$date = $_GET['updated_since'] ?? null;
$email = $_GET['email'] ?? null;


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

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
        break;
}
