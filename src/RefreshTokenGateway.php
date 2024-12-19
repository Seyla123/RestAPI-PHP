<?php 
    class RefreshTokenGateway
    {
        private PDO $conn;
        private string $secret_key;

        public function __construct(Database $database, string $secret_key)
        {
            $this->conn = $database->getConnection();
            $this->secret_key = $secret_key;
        }
        public function create(string $token, int $expiry):bool
        {
            $hash = hash_hmac('sha256', $token, $this->secret_key);
            $sql = "INSERT INTO refresh_token (token_hash, expires_at) VALUES (:token_hash, :expires_at)";
            $stmt = $this->conn->prepare($sql);
            $stmt -> bindValue(':token_hash', $hash, PDO::PARAM_STR);
            $stmt -> bindValue(':expires_at', $expiry, PDO::PARAM_INT);
            return $stmt -> execute();
        }
    }
?>