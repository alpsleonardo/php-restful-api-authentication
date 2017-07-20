<?php
/**
 * Created by PhpStorm.
 * User: m1nk1m
 * Date: 2017-07-19
 * Time: 8:52 PM
 */

class DomainService
{
    private $conn = null;


    public function __construct()
    {
        require_once "Database.php";

        $db = new Database();
        $this->conn = $db->getConnection();
    }


    public function find_by_user_id($user_id)
    {
        $query = $this->conn->prepare("SELECT * FROM Domains WHERE user_id=?");
        $query->bindParam(1, $user_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if($query->rowCount() > 0) {
            return $result;
        }
        return false;
    }

    public function find_by_domain_id($domain_id)
    {
        $query = $this->conn->prepare("SELECT * FROM Domains WHERE id=?");
        $query->bindParam(1, $domain_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if($query->rowCount() > 0) {
            return $result;
        }
        return false;
    }


    public function renew_expiry($year, $domain_id)
    {
        $time = 0;
        switch ($year) {
            case 1:
                $time = 60 * 60 * 24 * 365;
                break;
            case 2:
                $time = 60 * 60 * 24 * 365 * 2;
                break;
            case 3:
                $time = 60 * 60 * 24 * 365 * 3;
                break;
        }

        $query = $this->conn->prepare("UPDATE Domains SET expire_at = expire_at + '{$time}' WHERE id=?");
        $query->bindParam(1, $domain_id, PDO::PARAM_INT);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $query->execute();
        if($query->rowCount() > 0) {
            echo json_encode($result);
            return true;
        }
        return false;
    }

    public function already_exists($domain_name)
    {
        $query = $this->conn->prepare("SELECT * FROM Domains WHERE domain_name=?");
        $query->bindParam(1, $domain_id, PDO::PARAM_INT);
        $query->execute();
        if($query->rowCount() > 0) {
            return true;
        }

        return false;
    }
    public function create_domain($user_id, $domain_name)
    {
        if ($this->already_exists($domain_name)) {
            return false;
        }

        $created_at = time();
        $expire_at = $created_at + (60 * 60 * 24 * 365);

        $query = $this->conn->prepare("INSERT INTO domains SET 
                                      user_id=:user_id, 
                                      domain_name=:domain_name, 
                                      created_at=:created_at, 
                                      expire_at=:expire_at");

        // bind values
        $query->bindParam(":user_id", $user_id);
        $query->bindParam(":domain_name", $domain_name);
        $query->bindParam(":created_at", $created_at);
        $query->bindParam(":expire_at", $expire_at);
        $query->execute();
        if($query->rowCount() > 0) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // delete the product
    public function delete($domain_id){
        // delete query
        $query = $this->conn->prepare("DELETE FROM domains WHERE id=?");
        $query->bindParam(1, $domain_id, PDO::PARAM_INT);
        $query->execute();
        if($query->rowCount() > 0) {
            return true;
        }
        return false;
    }
}