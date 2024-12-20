<?php

declare(strict_types=1);
require __DIR__ . "/bootstrap.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header("Allow: POST");
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"), true);

if (!array_key_exists('token', $data)) {
    http_response_code(400);
    echo json_encode(["message" => "Missing token"]);
    exit;
}

$codec = new JWTCodec($_ENV["JWT_SECRET_KEY"]);
try {
    $payload = $codec->decode($data["token"]);
} catch (Exception $err) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid token"]);
    exit;
}

$user_id = $payload['sub'];

// database connection
$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
$refresh_gateway = new RefreshTokenGateway($database, $_ENV["JWT_SECRET_KEY"]);
$refresh_token = $refresh_gateway->getByToken($data["token"]);

if($refresh_token === false) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid token (not in whitelist)"]);
    exit;
}
$user_gateway = new UserGateway($database);
$user = $user_gateway->getById($user_id);

if ($user === false) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid authentication"]);
    exit;
}

require __DIR__ . "/tokens.php";

$refresh_gateway->delete($data["token"]);
$refresh_gateway->create($refresh_token, $refresh_token_expiry);
?>