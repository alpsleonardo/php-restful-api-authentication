<?php
/**
 * Created by PhpStorm.
 * User: m1nk1m
 * Date: 2017-07-19
 * Time: 12:12 PM
 */


class UserService
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
     * Login with credentials provided
     */
    public function login($email, $password)
    {
        $hashAndSalt = password_hash($password, PASSWORD_BCRYPT);

        $query = $this->conn->prepare("SELECT * FROM Users WHERE email=? AND password=?");
        $query->bindParam(1, $email, PDO::PARAM_STR);
        $query->bindParam(2, $password, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // result found && password verified?
        if($query->rowCount() > 0 && password_verify($result['password'], $hashAndSalt)) {

            if(!$this->update_last_logged($result['id'])) {

                // failure to update the login log
                return false;
            }

            // success
            return [
                'id' => $result['id'],
                'firstname' => $result['firstname'],
                'lastname' => $result['lastname']
            ];
        }

        // login failure
        return false;
    }


    /**
     * Updates timestamp in the last_logged column when logging in successfully
     */
    protected function update_last_logged($user_id)
    {
        $date = date('Y-m-d H:i:s');
        $query = $this->conn->prepare("UPDATE Users SET last_logged=? WHERE id =?");
        $query->bindParam(1, $date, PDO::PARAM_STR);
        $query->bindParam(2, $user_id, PDO::PARAM_INT);

        return $query->execute();
    }


    /**
     * Finds a user row by the user id
     */
    public function find_by_id($id)
    {
        $query = $this->conn->prepare("SELECT * FROM Users WHERE id=?");
        $query->bindParam(1, $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($query->rowCount() > 0) {
            return $result;
        }
        return false;
    }


    /**
     * Checks if user info is already in the database
     */
    public function already_exists($email)
    {
        $query = $this->conn->prepare("SELECT * FROM Users WHERE email=?");
        $query->bindParam(1, $email, PDO::PARAM_STR);
        $query->execute();

        return ($query->rowCount() > 0);
    }

    /**
     * Insert a new user row
     */
    public function create_user($firstname, $lastname, $dob, $email, $password)
    {
        // check if the credential sent already exist
        if ($this->already_exists($email)) {
            return false;
        }

        $created_at = date('Y-m-d H:i:s');
        $query = $this->conn->prepare("INSERT INTO Users SET 
                                                firstname=:firstname, 
                                                lastname=:lastname, 
                                                dob=:dob, 
                                                email=:email, 
                                                password=:password, 
                                                created_at=:created_at");

        $query->bindParam(":firstname", $firstname);
        $query->bindParam(":lastname", $lastname);
        $query->bindParam(":dob", $dob);
        $query->bindParam(":email", $email);
        $query->bindParam(":password", $password);
        $query->bindParam(":created_at", $created_at);

        if ($query->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Updates the user table row
     */
    public function update_user($password, $user_id)
    {
        $query = $this->conn->prepare("UPDATE Users SET password=? WHERE id=?");
        $query->bindParam(1, $password, PDO::PARAM_STR);
        $query->bindParam(2, $user_id, PDO::PARAM_INT);
        $query->execute();

        return ($query->rowCount() > 0);
    }

    /**
     * Delete the user table row
     */
    protected function delete($id)
    {
        // delete query
        $query = $this->conn->prepare("DELETE FROM Users WHERE id=?");
        $query->bindParam(1, $id, PDO::PARAM_INT);
        $query->execute();

        return ($query->rowCount() > 0);
    }
}