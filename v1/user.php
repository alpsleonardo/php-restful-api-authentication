<?php
/**
 * Created by PhpStorm.
 * User: m1nk1m
 * Date: 2017-07-19
 * Time: 12:28 PM
 */


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");

include_once '../includes/Config.php';
include_once '../includes/Database.php';
include_once '../includes/UserService.php';
include_once '../includes/AuthService.php';
include_once '../includes/Functions.php';

$client_id = isset($_SERVER["HTTP_CLIENT_ID"]) ? $_SERVER["HTTP_CLIENT_ID"] : null;
if (!validate_client_id()) {
    return;
}

$method = isset($_POST["method"]) ? $_POST["method"] : null;
$validity = validate_token(ACCESS_SECRET_KEY);

switch ($method) {
    case "get_user":
        $validity ?
            get_user_info($validity['id']) : echo_response(true, "Invalid token");
        break;

    case "create_user":
        $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : null;
        $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : null;
        $dob = isset($_POST["dob"]) ? $_POST["dob"] : null;
        $email = isset($_POST["email"]) ? $_POST["email"] : null;
        $password = isset($_POST["password"]) ? $_POST["password"] : null;

        ($firstname && $lastname && $dob && $email && $password) ?
            create_user($firstname, $lastname, $dob, $email, $password) : echo_response(true, "Invalid parameters");
        break;

    case "update_password":
        $validity ?
            update_password($validity['id']) : echo_response(true, "Invalid token");
        break;

    case "delete_user":
        $validity ?
            delete_user($validity['id']) : echo_response(true, "Invalid token");
        break;

    default:
        echo_response(true, "Invalid request");
        break;
}

function get_user_info($id)
{
    $userService = new UserService();
    $user = $userService->find_by_id($id);
    $user ?
        echo_response(false, $user) : echo_response(true, "User not found");
}

function create_user($firstname, $lastname, $dob, $email, $password)
{
    $userService = new UserService();
    $new_user = $userService->create_user($firstname, $lastname, $dob, $email, $password);
    $new_user ?
        echo_response(false, $userService->find_by_id($new_user)) : echo_response(true, "Failed to create a user");
}

function update_password($id)
{
    $new_password = isset($_POST["new_password"]) ? $_POST["new_password"] : null;

    if (!$new_password) {
        echo_response(true, "Missing a parameter");
        return;
    }

    $userService = new UserService();
    $user = $userService->update_user($new_password, $id);
    $user ?
        echo_response(false, $userService->find_by_id($id)) : echo_response(true, "Failed to update the password");
}

function delete_user($id)
{

}