<?php
/**
 *
 * @author     Minseok Kim (m1nk1m)
 * @copyright  Copyright (c) 2017 Minseok Kim. All rights reserved.
 *
 */


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Credentials: true");

include_once '../includes/Config.php';
include_once '../includes/Database.php';
include_once '../includes/UserService.php';
include_once '../includes/AuthService.php';
include_once '../includes/Functions.php';


if (!validate_client_id()) {
    return;
}

// get the method type from the query passed
$method = convert_url_query(get_current_url())["method"];

// get the token and validate it
$validity = validate_token(ACCESS_SECRET_KEY);

// select the API method to execute functions accordingly
switch ($method) {
    case "getUserInfo":
        $validity ? get_user_info($validity['id']) : echo_response(true, "Invalid token");
        break;

    case "createUser":
        $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : null;
        $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : null;
        $dob = isset($_POST["dob"]) ? $_POST["dob"] : null;
        $email = isset($_POST["email"]) ? $_POST["email"] : null;
        $password = isset($_POST["password"]) ? $_POST["password"] : null;

        ($firstname && $lastname && $dob && $email && $password)
            ? create_user($firstname, $lastname, $dob, $email, $password) : echo_response(true, "Invalid parameters");
        break;

    case "updatePassword":
        $validity ? update_password($validity['id']) : echo_response(true, "Invalid token");
        break;

    case "deleteUser":
        $validity ? delete_user($validity['id']) : echo_response(true, "Invalid token");
        break;

    default:
        echo_response(true, "Invalid request");
        break;
}


/**
 * Fetches the user info with the id passed through the encrypted custom data
 *
 * @param integer       $id             The user id to select the table row
 *
 * @return void
 */
function get_user_info($id)
{
    $userService = new UserService();
    $user = $userService->find_by_id($id);
    $user ? echo_response(false, $user) : echo_response(true, "User not found");
}


/**
 * Creates a new user in the database
 *
 * @param string        $firstname      User's first name
 * @param string        $lastname       User's last name
 * @param string        $dob            User's date of birth
 * @param string        $email          User's email
 * @param string        $password       User's password
 *
 * @return void
 */
function create_user($firstname, $lastname, $dob, $email, $password)
{
    $userService = new UserService();
    $new_user = $userService->create_user($firstname, $lastname, $dob, $email, $password);
    $new_user ? echo_response(false, $userService->find_by_id($new_user)) : echo_response(true, "Failed to create a user");
}


/**
 * Updates a password associated with the custom data encrypted in the token
 *
 * @param integer       $id             The user id to select the table row
 *
 * @return void
 */
function update_password($id)
{
    $new_password = isset($_POST["new_password"]) ? $_POST["new_password"] : null;

    if (!$new_password) {
        echo_response(true, "Missing a parameter");
        return;
    }

    $userService = new UserService();
    $user = $userService->update_user($new_password, $id);
    $user ? echo_response(false, $userService->find_by_id($id)) : echo_response(true, "Failed to update the password");
}


/**
 * Deletes a userassociated with the custom data encrypted in the token
 *
 * @param integer       $id             The user id to select the table row
 *
 * @return void
 */
function delete_user($id)
{

}