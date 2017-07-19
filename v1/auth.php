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


$method = isset($_POST["method"]) ? $_POST["method"] : "";

switch ($method) {
    case "login":
        authenticate_user();
        break;

    case "refresh":
        refresh_token();
        break;

    default:
        $resp = array();
        $resp['error'] = true;
        $resp['message'] = "Invalid request";
        echo json_encode($resp);
        break;
}


function authenticate_user() {
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    $resp = array();

    if ($email && $password) {
        $db = new Database();
        $userService = new UserService($db->getConnection());
        $loginSuccess = $userService->login($email, $password);

        if ($loginSuccess) {

            $authService = new AuthService();

            $resp['error'] = false;
            $resp['resp'] = $authService->generate_tokens($loginSuccess);
        } else {

            $resp['error'] = true;
            $resp['message'] = "Invalid username or password";
        }

    } else {
        $resp['error'] = true;
        $resp['message'] = "Invalid request";
    }

    echo json_encode($resp);
}

function refresh_token() {
    $token_value = isset($_POST["tok_val"]) ? $_POST["tok_val"] : "";
    $resp = array();

    if ($token_value) {
        $db = new Database();
        $authService = new AuthService($db->getConnection());
        $verification = $authService->verify_jwt($token_value, REFRESH_SECRET_KEY);

        if ($verification) {
            $decoded = json_decode(json_encode($verification), true);
            $custom_data = $decoded['data'];

            $resp['error'] = false;
            $resp['resp'] = $authService->generate_tokens($custom_data);

        } else {

            $resp['error'] = true;
            $resp['message'] = "Invalid token";
        }
    } else {

        $resp['error'] = true;
        $resp['message'] = "Invalid request";
    }

    echo json_encode($resp);
}
