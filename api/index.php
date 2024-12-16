<?php 
   // strict mode
   declare(strict_types=1);
   // ini_set("display_errors", "On");
   
   // autoloader
   require dirname(__DIR__) . "/vendor/autoload.php";
   set_exception_handler("ErrorHandler::handleException");

   $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
   $parts = explode('/', $path);
   $resource = $parts[2] ?? null;
   $id = $parts[3] ?? null;
   
   if($resource !== 'task'){
      http_response_code(404);
      exit;
   }

   // task controller
   $taskController = new TaskController();
   // call the task controller method processRequest and pass the request method and id
   $taskController->processRequest($_SERVER['REQUEST_METHOD'], $id);
?>