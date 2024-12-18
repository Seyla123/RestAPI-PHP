<?php
    class TaskController
    {
        public function __construct(private TaskGateway $gateway)
        {

        }
        public function processRequest(string $method, ?string $id): void
        {
            if ($id === null) {
                switch ($method) {
                    case 'GET':
                        echo json_encode($this->gateway->getAll());
                        break;
                    case 'POST':
                        $data = (array) json_decode(file_get_contents("php://input"), true);
                        $id = $this->gateway->create($data);
                        $this->responseCreated($id);
                        break;
                    default:
                        $this->responseMethodNotAllowed("GET, POST");
                        break;
                }
            } else {
                if($this->gateway->get($id) === false) {
                    $this->responseNotFound($id);
                    return;
                }
                switch ($method) {
                    case 'GET':
                        echo json_encode($this->gateway->get($id));
                        break;
                    case 'PUT':
                        echo "Update Task : ", $id;
                        break;
                    case 'DELETE':
                        echo "Delete Task : ", $id;
                        break;
                    default:
                        $this->responseMethodNotAllowed("GET, PUT, DELETE");
                        break;
                }
            }
        }
        private function responseMethodNotAllowed(string $allowedMethods): void
        {
            http_response_code(405);
            header("Allow: {$allowedMethods}");	
            echo "Method Not Allowed";
        }
        private function responseNotFound(string $id): void
        {
            http_response_code(404);
            echo json_encode([
                "message"=>"Task with ID $id not found"
            ]);
        }
        private function responseCreated(string $id): void
        {
            http_response_code(201);
            echo json_encode([
                "message"=>"Task created with ID $id"
            ]);
        }
    }
?>