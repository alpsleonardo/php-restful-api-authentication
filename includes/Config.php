<?php
/**
 * Created by PhpStorm.
 * User: m1nk1m
 * Date: 2017-07-19
 * Time: 12:12 PM
 */

ini_set('display_errors', 1);
date_default_timezone_set('America/Toronto');
error_reporting(E_ALL);

define('DB_HOST', 'localhost');
define('DB_NAME', 'cira-test');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');

define('CLIENT_SECRET','Your-Secret-Key');
define('ACCESS_SECRET_KEY','This-Is-For-Access-Token');
define('REFRESH_SECRET_KEY','This-Is-For-Refresh-Token');
define('ALGORITHM','HS512');
