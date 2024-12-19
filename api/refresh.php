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
var_dump($user_id);

?>