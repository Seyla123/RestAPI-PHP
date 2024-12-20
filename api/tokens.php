<?php 
    
$payload = [
    "sub" => $user['id'],
    "name" => $user['name'],
    "exp"=> time() + 3000
];
$access_token = $codec->encode($payload);

$refresh_token_expiry = time() + 43200;
$refresh_token = $codec->encode([
    "sub"=> $user['id'],
    "exp"=> $refresh_token_expiry
]);

setcookie("access_token", $access_token, $refresh_token_expiry, "/");
setcookie("refresh_token", $refresh_token, $refresh_token_expiry, "/");

echo json_encode([
    "message"=>"Tokens created",
    "access_token" => $access_token,
    "refresh_token" => $refresh_token
]);

?>