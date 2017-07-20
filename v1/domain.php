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


$method = isset($_POST["method"]) ? $_POST["method"] : null;

switch ($method) {
    case "get_domains":
        get_domain_info();
        break;

    case "create_domain":
        create_domain();
        break;

    case "update_domain":
        renew_domain();
        break;

    case "delete_domain":
        delete_domain();
        break;

    default:
        echo_response(true, "Invalid request");
        break;
}

function get_domain_info()
{

}

function create_domain()
{

}

function renew_domain()
{

}

function delete_domain()
{

}