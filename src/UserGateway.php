<?php 
    class UserGateway{
        private PDO $conn;
        public function __construct(Database $database){
            $this->conn = $database->getConnection();
        }
        public function getByAPIKey(string $apiKey):array | false 
        {
            $sql = "SELECT * FROM user WHERE api_key = :apiKey";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':apiKey', $apiKey, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        public function getByUsername(string $username):array | false
        {
            $sql = "SELECT * FROM user WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        public function getById(int $id):array | false
        {
            $sql = "SELECT * FROM user WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>