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
            $sql = "INSERT INTO refresh_tokens (token, expiry) VALUES (:token, :expiry)";
            $stmt = $this->conn->prepare($sql);
            $stmt -> bindValue(':token', $token, PDO::PARAM_STR);
            $stmt -> bindValue(':expiry', $expiry, PDO::PARAM_INT);
            return $stmt -> execute();
        }
    }
?>