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
        public function delete(string $token):int
        {
            $hash = hash_hmac('sha256', $token, $this->secret_key);
            $sql = "DELETE FROM refresh_token WHERE token_hash = :token_hash";
            $stmt = $this->conn->prepare($sql);
            $stmt -> bindValue(':token_hash', $hash, PDO::PARAM_STR);
            $stmt -> execute();

            return $stmt->rowCount();
        }
        public function getByToken(string $token):array | false
        {
            $hash = hash_hmac('sha256', $token, $this->secret_key);
            $sql = "SELECT * FROM refresh_token WHERE token_hash = :token_hash";
            $stmt = $this->conn->prepare($sql);
            $stmt -> bindValue(':token_hash', $hash, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>