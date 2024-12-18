<?php

require __DIR__ . "/vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $database = new Database(
        $_ENV['DB_HOST'], 
        $_ENV['DB_NAME'], 
        $_ENV['DB_USER'], 
        $_ENV['DB_PASSWORD']);
    $conn = $database->getConnection();

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $api_key = bin2hex(random_bytes(16));
    $sql = "INSERT INTO user (name, username, password_hash, api_key) 
    VALUES (:name, :username, :password_hash, :api_key)";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
    $stmt->bindValue(':api_key', $api_key, PDO::PARAM_STR);
    $stmt->execute();

    echo "User registered successfully. API key: " . $api_key;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>

<body>
    <main class="container">
        <h1>Register</h1>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
        </form>
    </main>
</body>

</html>