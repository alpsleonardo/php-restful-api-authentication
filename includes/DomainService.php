<?php
/**
 *
 * @author     Minseok Kim (m1nk1m)
 * @copyright  Copyright (c) 2017 Minseok Kim. All rights reserved.
 *
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
     * Finds all the domain rows associated by the user id
     *
     * @param integer       $user_id        User's id to be used to select the row
     *
     * @return mixed
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
     * Finds a domain row associated by the domain id
     *
     * @param integer       $domain_id      User's id to be used to select the row
     *
     * @return mixed
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
     * Updates timestamp in the expire_at column associated with the domain id
     *
     * @param integer       $year           Number of year to be updated
     * @param integer       $domain_id      Domain id to select the row
     *
     * @return bool
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
     * Checks if the requested domain name is already in the database
     *
     * @param string        $domain_name    Domain name to be validated against database (if it exists)
     *
     * @return bool
     */
    public function already_exists($domain_name)
    {
        $query = $this->conn->prepare("SELECT * FROM Domains WHERE domain_name=?");
        $query->bindParam(1, $domain_name, PDO::PARAM_INT);
        $query->execute();

        return ($query->rowCount() > 0);
    }


    /**
     * Inserts a new table row in the user table
     *
     * @param integer       $user_id        User's id to associate the new domain with
     * @param string        $domain_name    Domain name for new registration
     *
     * @return mixed
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
     * Delete the user table row by user id
     *
     * @param integer       $domain_id      Domain id to select the table row
     *
     * @return bool
     */
    public function delete($domain_id){
        // delete query
        $query = $this->conn->prepare("DELETE FROM Domains WHERE id=?");
        $query->bindParam(1, $domain_id, PDO::PARAM_INT);
        $query->execute();

        return ($query->rowCount() > 0);
    }
}