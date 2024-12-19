<?php 
    
$payload = [
    "sub" => $user['id'],
    "name" => $user['name'],
    "exp"=> time() + 20
];
$access_token = $codec->encode($payload);
$refresh_token = $codec->encode([
    "sub"=> $user['id'],
    "exp"=> time() + 43200
]);
echo json_encode([
    "access_token" => $access_token,
    "refresh_token" => $refresh_token
]);

?>