<?php

require __DIR__ . "/vendor/autoload.php";
// load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $request_body = json_encode(["username" => $username, "password" => $password]);

    // API endpoint
    $apiUrl = $_ENV["DOMAIN_URL"]; // Replace with your API URL

    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if ($response === false) {
        die('Error: ' . curl_error($ch));
    }

    // Close cURL
    curl_close($ch);

    // Decode API response
    $result = json_decode($response, true);

    // Handle API response
    if (isset($result['access_token'])) {
        // Login successful
        echo "Login successful";
        $access_token = $result['access_token'];
        $refresh_token = $result['refresh_token'];
        $refresh_token_expiry = time() + 43200;
        setcookie("access_token", $access_token, $refresh_token_expiry, "/");
        setcookie("refresh_token", $refresh_token, $refresh_token_expiry, "/");
    } else {
        // Login failed
        echo 'Login failed: ' . $result['message'];
    }
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
        <h1>Login</h1>
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
        </form>
    </main>
</body>

</html>