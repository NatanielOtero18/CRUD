<?php
require_once "./config/db.php";
require_once "./controller/userController.php";
require_once "./controller/loginController.php";
require_once "./config/cors.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Origin, X-Auth-Token'");
cors();

$request_method = $_SERVER["REQUEST_METHOD"];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);
$data = file_get_contents("php://input");


// the user id is, of course, optional and must be a number:
$userId = null;
if (isset($uri[4])) {
    $userId = (int) $uri[4];

}

$route = null;
if (isset($uri[3])) {
    $route = $uri[3];
    

}


$database = new DB();
$db = $database->getConnection();

switch ($route) {
    case 'users':
        $controller = new userController($db, $request_method, $userId);
        $controller->processRequest();
        break;
    case 'login':
        $controller = new loginController($db);
        $controller->Login();
        break;
    default:
       echo "Route Error";
        break;
}



?>