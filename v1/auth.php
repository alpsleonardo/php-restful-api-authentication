<?php
/**
 * Created by PhpStorm.
 * User: m1nk1m
 * Date: 2017-07-19
 * Time: 12:13 PM
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

// check if method param is passed appropriately
$method = isset($_POST["method"]) ? $_POST["method"] : null;

switch ($method) {
    case "login":
        authenticate_user();
        break;

    case "refresh":
        refresh_token();
        break;

    default:
        echo_response(true, "Invalid request");
        break;
}

// authenticate the user credentials via UserService class methods
function authenticate_user() {
    // check if the credential params is passed appropriately
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;

    if ($email && $password) {
        $userService = new UserService();
        $loginSuccess = $userService->login($email, $password);

        $authService = new AuthService();
        $loginSuccess ?
            echo_response(false, $authService->generate_tokens($loginSuccess)) : echo_response(true, "Invalid email or password");

    } else {
        echo_response(true, "Invalid request");
    }
}

// issue a new token if refress token is valid via AuthService
function refresh_token() {

    $custom_data = validate_token(REFRESH_SECRET_KEY);

    if (!$custom_data) {
        echo_response(true, "Invalid token or request");
    }

    $authService = new AuthService();
    echo_response(true, ($authService->generate_tokens(json_decode($custom_data), true)));
}
