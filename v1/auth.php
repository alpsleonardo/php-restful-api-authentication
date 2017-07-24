<?php
/**
 *
 * @author     Minseok Kim (m1nk1m)
 * @copyright  Copyright (c) 2017 Minseok Kim. All rights reserved.
 *
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


if (!validate_client_id()) {
    return;
}

// get the method type from the query passed
$method = convert_url_query(get_current_url())["method"];

// select the API method to execute functions accordingly
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


/**
 * Authenticate the user credentials via UserService class methods
 *
 * @return void
 */
function authenticate_user() {

    // Check if the credential params are passed correctly
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;

    if ($email && $password) {

        $userService = new UserService();
        $authService = new AuthService();

        $loginSuccess = $userService->login($email, $password);
        $loginSuccess ? echo_response(false, $authService->generate_tokens($loginSuccess)) : echo_response(true, "Invalid email or password");

    } else {

        // params not passed
        echo_response(true, "Invalid request");
    }
}


/**
 * Issue a new token if refress token is valid via AuthService
 *
 * @return void
 */
function refresh_token() {

    $custom_data = validate_token(REFRESH_SECRET_KEY);

    if (!$custom_data) {
        echo_response(true, "Invalid token or request");
        return;
    }

    $authService = new AuthService();
    echo_response(true, ($authService->generate_tokens($custom_data, true)));
}
