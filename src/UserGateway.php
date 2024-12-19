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
    }
?>