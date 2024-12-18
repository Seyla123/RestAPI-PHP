<?php
// strict mode
declare(strict_types=1);
// ini_set("display_errors", "On");

// autoloader
require dirname(__DIR__) . "/vendor/autoload.php";

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

// load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', $path);
$resource = $parts[2] ?? null;
$id = $parts[3] ?? null;

if ($resource !== 'task') {
   http_response_code(404);
   exit;
}
$api_key = $_SERVER['HTTP_X_API_KEY'] ?? null;
// database connection
$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
if (empty($api_key)) {
   http_response_code(400);
   echo json_encode(["message" => "Missing API Key"]);
}

$user_gateway = new UserGateway($database);

echo $api_key;
exit;
// set the response header to application/json
// this is necessary because the response is in json format
header("Content-type: application/json; charset=UTF-8");

// task gateway
$task_gateway = new TaskGateway($database);
// task controller
$taskController = new TaskController($task_gateway);
// call the task controller method processRequest and pass the request method and id
$taskController->processRequest($_SERVER['REQUEST_METHOD'], $id);
