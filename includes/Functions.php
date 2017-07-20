<?php
/**
 * Created by PhpStorm.
 * User: m1nk1m
 * Date: 2017-07-19
 * Time: 3:13 PM
 */


function validate_client_id()
{
    $client_id = isset($_SERVER["HTTP_CLIENT_ID"]) ? $_SERVER["HTTP_CLIENT_ID"] : null;
    if (!$client_id) {
        return false;
    }

    return ($client_id === CLIENT_SECRET);
}

function validate_token($secret_key)
{
    $auth_token = isset($_SERVER["HTTP_AUTH_TOKEN"]) ? $_SERVER["HTTP_AUTH_TOKEN"] : null;

    if (!$auth_token) {
        return false;
    }

    $authService = new AuthService();
    $verification = $authService->verify_jwt($auth_token, $secret_key);

    if (!$verification) {
        return false;
    }

    $decoded = json_decode(json_encode($verification), true);
    return $custom_data = $decoded['data'];
}

function echo_response($error_status, $output)
{
    $resp = array();

    if ($error_status) {
        $resp['error'] = true;
        $resp['message'] = $output;
    } else {
        $resp['error'] = false;
        $resp['resp'] = $output;
    }

    echo json_encode($resp);
}