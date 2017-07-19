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


$method = isset($_POST["method"]) ? $_POST["method"] : "";

switch ($method) {
    case "get_domains":
        get_user_info();
        break;

    case "create_domain":
        create_user();
        break;

    case "update_domain":
        update_user();
        break;

    case "delete_domain":
        delete_user();
        break;

    default:
        $resp = array();
        $resp['error'] = true;
        $resp['message'] = "Invalid request";
        echo json_encode($resp);
        break;
}

function get_domains()
{

}

function create_domain()
{

}

function update_domain()
{

}

function delete_domain()
{

}