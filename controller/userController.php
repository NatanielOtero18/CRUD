<?php

include_once './user/user.php';

class userController
{

    private $database;
    private $request_method;
    private $user_Id;
    private $user;

    public function __construct($db, $method, $Id)
    {

        $this->database = $db;
        $this->request_method = $method;
        $this->user_Id = $Id;
        $this->user = new User($db);
    }

    public function processRequest()
    {
        switch ($this->request_method) {
            case 'GET':
                if ($this->user_Id) {
                    $response = $this->getOne();
                } else {
                    $response = $this->getAll();
                }

                break;
            case 'POST':
                $response = $this->createNewUser();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }

    }

    private function getAll()
    {
        $result = $this->user->getUsers();
        $count = $result->rowCount();
        //echo json_encode($count);
        if ($count > 0) {
            $userArr = array();
            $userArr["body"] = array();
            $userArr["itemCount"] = $count;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $u = array(
                    "ID" => $ID,
                    "EMAIL" => $EMAIL,
                    "UserName" => $UserName
                );
                array_push($userArr["body"], $u);
            }

            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($userArr);
            return $response;

        } else {
            http_response_code(404);
            echo json_encode(
                array("message" => "No record found.")
            );
        }
    }
    private function getOne()
    {
        $result = $this->user->getByID($this->user_Id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

    private function createNewUser()
    {
        $data = json_decode(file_get_contents("php://input"));
        
        if (isset($data)) {
            if ($this->user->createUser($data->email, $data->user, $data->pass)) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode("User Added!");

            } else {
                $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
                $response['body'] = null;
            }

        } else {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = null;
        }
        return $response;
    }

}

/*$items = new User($db);
$stmt = $items->getUsers();
$count = $stmt->rowCount();
echo json_encode($count);
if ($count > 0) {
$userArr = array();
$userArr["body"] = array();
$userArr["itemCount"] = $count;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
extract($row);
$u = array(
"ID" => $ID,
"EMAIL" => $EMAIL,
"UserName" => $UserName
);
array_push($userArr["body"], $u);
}
echo json_encode($userArr);
} else {
http_response_code(404);
echo json_encode(
array("message" => "No record found.")
);
}*/







?>