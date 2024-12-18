<?php 
    class UserGateway{
        private PDO $conn;
        public function __construct(Database $database){
            $this->conn = $database->getConnection();
        }
        public function getByAPIKey(string $key):array | false 
        {
            $sql = "SELECT * FROM user WHERE api_key = :key";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':key', $key, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>