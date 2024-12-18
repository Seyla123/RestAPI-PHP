<?php 
    // autoloader
    require dirname(__DIR__) . "/vendor/autoload.php";

    set_error_handler("ErrorHandler::handleError");
    set_exception_handler("ErrorHandler::handleException");

    // load the .env file
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    // set the response header to application/json
    // this is necessary because the response is in json format
    header("Content-type: application/json; charset=UTF-8");
?>