<?php
// strict mode
declare(strict_types=1);
// ini_set("display_errors", "On");

// include bootstrap
require __DIR__ . "/bootstrap.php";

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

// user gateway check api key
$user_gateway = new UserGateway($database);
if($user_gateway->getByAPIKey($api_key) === false) {
   http_response_code(401);
   echo json_encode(["message" => "Invalid API Key"]);
   exit;
}

// task gateway
$task_gateway = new TaskGateway($database);
// task controller
$taskController = new TaskController($task_gateway);
// call the task controller method processRequest and pass the request method and id
$taskController->processRequest($_SERVER['REQUEST_METHOD'], $id);
