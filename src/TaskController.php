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
                        echo "GET All Tasks";
                        break;
                    case 'POST':
                        echo "Create Task";
                        break;
                    default:
                        $this->responseMethodNotAllowed("GET, POST");
                        break;
                }
            } else {
                switch ($method) {
                    case 'GET':
                        echo "GET One Task : ", $id;
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
    }
?>