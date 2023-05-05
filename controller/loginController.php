<?php

include_once './user/user.php';

class loginController
{
    private $user;
    private $email;
    private $pwd;
    public function __construct($db)
    {
        $this->user = new User($db);
    }
    public function Login()
    {
        $this->validateUser();

    }
    private function sanitizeData()
    {
        $email = $_POST["email"];
        $pass = $_POST["pass"];

        $this->email = htmlspecialchars(strip_tags($email));
        $this->pwd = htmlspecialchars(strip_tags($pass));
    }
    private function getCredentials()
    {

        $result = $this->user->getByUserEmail($this->email);
        if (!$result) {
            return $this->notFoundResponse();
        }
        return $result[0];
    }
    private function validateUser()
    {
        $this->sanitizeData();
        $user = $this->getCredentials();
        if(password_verify($this->pwd,$user["Password"])){
            echo json_encode($user);
        }else{
            echo "Bad";
        }

    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }


}





?>