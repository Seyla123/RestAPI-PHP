<?php
class Auth
{
    private int $user_id;
    public function __construct(
        private UserGateway $user_gateway,
        private JWTCodec $codec) {}
    public  function authenticateAPIkey(): bool
    {
        if (empty($_SERVER['HTTP_X_API_KEY'])) {
            http_response_code(400);
            echo json_encode(["message" => "Missing API Key"]);
            return false;
        }

        $api_key = $_SERVER['HTTP_X_API_KEY'];
        $user = $this->user_gateway->getByAPIKey($api_key);
        if ($user === false) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid API Key"]);
            exit;
        }

        $this->user_id = $user['id'];
        return true;
    }
    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function authenticateAccessToken(): bool
    {
        if (!preg_match("/^Bearer\s+(.*)$/", $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            http_response_code(401);
            echo json_encode(["message" => "Incompleted authorization header"]);
            return false;
        }
      
        try {
            $data = $this->codec->decode($matches[1]);
        }catch(InvalidSignatureException){
            http_response_code(401);
            echo json_encode(["message" => "Invalid signature"]);
            return false;
        }catch(TokenExpiredException){
            http_response_code(401);
            echo json_encode(["message" => "Token has expired"]);
            return false;
        }
        catch (Exception $err) {
            http_response_code(400);
            echo json_encode(["message" => $err->getMessage()]);
            return false;
        }

        $this->user_id = $data['sub'];
        return true;
    }
}
