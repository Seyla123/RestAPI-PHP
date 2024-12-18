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
                        $errors = $this->getValidationErrors($data);
                        if(!empty($errors)) {
                            $this->respondUnprocessableEnity($errors);
                            return;
                        }
                        $id = $this->gateway->create($data);
                        $this->respondCreated($id);
                        break;
                    default:
                        $this->respondMethodNotAllowed("GET, POST");
                        break;
                }
            } else {
                if($this->gateway->get($id) === false) {
                    $this->respondNotFound($id);
                    return;
                }
                switch ($method) {
                    case 'GET':
                        echo json_encode($this->gateway->get($id));
                        break;
                    case 'PUT':
                        $data = (array) json_decode(file_get_contents("php://input"), true);
                        $errors = $this->getValidationErrors($data, false);
                        if(!empty($errors)) {
                            $this->respondUnprocessableEnity($errors);
                            return;
                        }
                        $row = $this->gateway->update($id, $data);
                        echo json_encode([
                            "message" => "Task updated with ID $id",
                            "row" => $row
                        ]);
                        break;
                    case 'DELETE':
                        $row = $this->gateway->delete($id);
                        echo json_encode([
                            "message" => "Task deleted with ID $id",
                            "row" => $row
                        ]);
                        break;
                    default:
                        $this->respondMethodNotAllowed("GET, PUT, DELETE");
                        break;
                }
            }
        }
        private function respondUnprocessableEnity(array $errors): void
        {
            http_response_code(422);
            echo json_encode([
                "errors" => $errors
            ]);
        }
        private function respondMethodNotAllowed(string $allowedMethods): void
        {
            http_response_code(405);
            header("Allow: {$allowedMethods}");	
            echo "Method Not Allowed";
        }
        private function respondNotFound(string $id): void
        {
            http_response_code(404);
            echo json_encode([
                "message"=>"Task with ID $id not found"
            ]);
        }
        private function respondCreated(string $id): void
        {
            http_response_code(201);
            echo json_encode([
                "message"=>"Task created with ID $id"
            ]);
        }
        private function getValidationErrors(array $data, bool $is_new = true): array
        {
            $errors = [];

            if($is_new && empty($data['name'])) {
                $errors[] = "Name is required";
            }

            if(!empty($data['priority']) && filter_var($data['priority'], FILTER_VALIDATE_INT) === false) {
                $errors[] = "Priority must be an integer";
            }
            return $errors;
        }
    }
?>