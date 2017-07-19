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
//require '../vendor/autoload.php';
//
$method = isset($_POST["method"]) ? $_POST["method"] : "";


//?$isValid = filter_var($user, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")));


switch ($method) {
    case "login":
        authenticate_user();
        break;

    case "refresh":
        refresh_token();
        break;

    default:
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
        echo "dd";
        if ($loginSuccess) {

            $authService = new AuthService();
            echo "dd";
            $resp['error'] = false;
            $resp['resp'] = $authService->generate_tokens($loginSuccess);
        } else {
            echo "dd";
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
    $token_value = isset($_POST["email"]) ? $_POST["email"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";


}
