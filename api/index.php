<?php 
   require dirname(__DIR__) . "/vendor/autoload.php";

   $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
   $parts = explode('/', $path);
   $resource = $parts[2] ?? null;
   $id = $parts[3] ?? null;
   
   if($resource !== 'task'){
      http_response_code(404);
      exit;
   }


   $taskController = new TaskController();
   $taskController->processRequest($_SERVER['REQUEST_METHOD'], $id);
?>