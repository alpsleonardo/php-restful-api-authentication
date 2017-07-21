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

    /**
     * Constructor
     */
    public function __construct()
    {
        require_once "Database.php";

        $db = new Database();
        $this->conn = $db->getConnection();
    }


    /**
     * Find domains by user id
     */
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


    /**
     * Find a domain by domain id
     */
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


    /**
     * renew the expire at field
     */
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
        $query->execute();

        return ($query->rowCount() > 0);
    }


    /**
     * Check if domain name already exists
     */
    public function already_exists($domain_name)
    {
        $query = $this->conn->prepare("SELECT * FROM Domains WHERE domain_name=?");
        $query->bindParam(1, $domain_name, PDO::PARAM_INT);
        $query->execute();

        return ($query->rowCount() > 0);
    }


    /**
     * Create a new domain registration
     */
    public function create_domain($user_id, $domain_name)
    {
        if ($this->already_exists($domain_name)) {
            return false;
        }

        $created_at = time();
        $expire_at = $created_at + (60 * 60 * 24 * 365);

        $query = $this->conn->prepare("INSERT INTO Domains SET 
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


    /**
     * Delete a domain
     */
    public function delete($domain_id){
        // delete query
        $query = $this->conn->prepare("DELETE FROM Domains WHERE id=?");
        $query->bindParam(1, $domain_id, PDO::PARAM_INT);
        $query->execute();

        return ($query->rowCount() > 0);
    }
}