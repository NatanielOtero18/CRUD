<?php

class User
{

    private $conn;

    private $db_table = "users";

    public $ID;
    public $EMAIL;
    public $UserName;
    private $Pass;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUsers()
    {
        $sqlQuery = "SELECT ID, EMAIL, UserName FROM $this->db_table";
        try {
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $err) {
            exit($err->getMessage());
        }
    }

    public function getByID($id)
    {
        $sqlQuery = "SELECT ID, EMAIL, UserName FROM $this->db_table WHERE ID = ?;";
        try {
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute(array($id));
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }


    }
    public function getByUserEmail($email)
    {
        $sqlQuery = "SELECT ID, EMAIL, UserName, Password FROM $this->db_table WHERE EMAIL = ?;";
        try {
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute(array($email));
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }


    }

    private function sanitizeUser($email, $user, $pass)
    {
        $this->EMAIL = htmlspecialchars(strip_tags($email));
        $this->UserName = htmlspecialchars(strip_tags($user));
        $pwdAux = htmlspecialchars((strip_tags($pass)));
        $this->Pass = password_hash($pwdAux, PASSWORD_DEFAULT);
       
    }


    public function insertUser($email, $user, $pass)
    {
        $this->sanitizeUser($email, $user, $pass);
        $sqlQuery = "INSERT INTO $this->db_table (EMAIL, UserName, Password) VALUES(:email, :username, :pass)";
        try {
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":email", $this->EMAIL);
            $stmt->bindParam(":username", $this->UserName);
            $stmt->bindParam(":pass", $this->Pass);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $err) {
            exit($err->getMessage());
        }

    }

    
    public function delete()
    {
        $sqlQuery = "DELETE FROM $this->db_table WHERE ID = :id";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":id", $this->ID);
        try {
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $err) {
            exit($err->getMessage());
        }
    }
    public function update($id,$email, $user, $pass){
        $this->sanitizeUser($email, $user, $pass);
        $sqlQuery = "UPDATE $this->db_table SET EMAIL = :email, UserName = :us, Password = :pass WHERE ID = :id";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":email",$this->EMAIL);
        $stmt->bindParam(":us",$this->UserName);
        $stmt->bindParam(":pass",$this->Pass);
        $stmt->bindParam(":id",$id);
        try {
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $err) {
            exit($err->getMessage());
        }
    }











}















?>