<?php
/**
 * Created by PhpStorm.
 * User: m1nk1m
 * Date: 2017-07-19
 * Time: 3:13 PM
 */



function validate_token()
{
    $token_value = isset($_POST["tok_val"]) ? $_POST["tok_val"] : "";

    if (!$token_value) {
        return false;
    }

    $authService = new AuthService();
    $verification = $authService->verify_jwt($token_value, ACCESS_SECRET_KEY);

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