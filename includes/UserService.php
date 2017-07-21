<?php
/**
 *
 * @author     Minseok Kim (m1nk1m)
 * @copyright  Copyright (c) 2017 Minseok Kim. All rights reserved.
 *
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
     * Validates login credentials provided against the database
     *
     * @param string        $email          User's email
     * @param array         $password       User's password
     *
     * @return mixed
     *
     * @uses update_last_logged
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

            // login success
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
     *
     * @param integer       $user_id        User's id to be used to select the row
     *
     * @return bool
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
     * Finds the user row by the user id
     *
     * @param integer       $user_id        User's id to be used to select the row
     *
     * @return mixed
     */
    public function find_by_id($user_id)
    {
        $query = $this->conn->prepare("SELECT * FROM Users WHERE id=?");
        $query->bindParam(1, $user_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($query->rowCount() > 0) {
            return $result;
        }
        return false;
    }


    /**
     * Checks if user info is already in the database
     *
     * @param string        $email          Email to be validated against database (if it exists)
     *
     * @return bool
     */
    public function already_exists($email)
    {
        $query = $this->conn->prepare("SELECT * FROM Users WHERE email=?");
        $query->bindParam(1, $email, PDO::PARAM_STR);
        $query->execute();

        return ($query->rowCount() > 0);
    }


    /**
     * Inserts a new table row in the user table
     *
     * @param string        $firstname      User's first name
     * @param string        $lastname       User's last name
     * @param string        $dob            User's date of birth
     * @param string        $email          User's email
     * @param string        $password       User's password
     *
     * @return mixed
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
     * Updates the user table row by the user id
     *
     * @param string        $password       User's password
     * @param integer       $user_id        User id to select the table row
     *
     * @return bool
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
     * Delete the user table row by user id
     *
     * @param integer       $user_id        User id to select the table row
     *
     * @return bool
     */
    protected function delete($user_id)
    {
        // delete query
        $query = $this->conn->prepare("DELETE FROM Users WHERE id=?");
        $query->bindParam(1, $user_id, PDO::PARAM_INT);
        $query->execute();

        return ($query->rowCount() > 0);
    }
}