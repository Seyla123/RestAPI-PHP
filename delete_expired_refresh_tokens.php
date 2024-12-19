<?php 
    declare(strict_types=1);
    // autoloader
    require __DIR__ . "/vendor/autoload.php";

    // load the .env file
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // database connection
    $database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $refresh_gateway = new RefreshTokenGateway($database, $_ENV["JWT_SECRET_KEY"]);
    echo $refresh_gateway->deleteExpired(), "\n";
?>