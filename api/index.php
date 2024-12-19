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
// database connection
$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);


// user gateway check auth
$user_gateway = new UserGateway($database);
$auth = new Auth($user_gateway);
if(!$auth->authenticateAPIkey()) {
   exit;
};

$user_id = $auth->getUserId();

// task gateway
$task_gateway = new TaskGateway($database);
// task controller
$taskController = new TaskController($task_gateway, $user_id);
// call the task controller method processRequest and pass the request method and id
$taskController->processRequest($_SERVER['REQUEST_METHOD'], $id);
