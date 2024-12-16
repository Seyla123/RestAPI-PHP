<?php 
    class TaskController{
        public function processRequest($method, $id){
            if($id === null){
                switch ($method){
                    case 'GET':	
                        echo "GET All Tasks";
                        break;
                    case 'POST':
                        echo "Create Task";
                        break;
                    default:
                        http_response_code(405);
                        echo "Method Not Allowed";
                        break;
                }
            }else{
                switch ($method){
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
                        http_response_code(405);
                        echo "Method Not Allowed";
                        break;
                }
            }
        }
    }
?>