<?php
/**
 *
 * @author     Minseok Kim (m1nk1m)
 * @copyright  Copyright (c) 2017 Minseok Kim. All rights reserved.
 *
 */


// error display config
ini_set('display_errors', 1);
error_reporting(E_ALL);

// database config
define('DB_HOST', 'localhost');
define('DB_NAME', 'cira-test');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');

// token generation & validation config
date_default_timezone_set('America/Toronto');
define('CLIENT_SECRET','Your-Secret-Key');
define('ACCESS_SECRET_KEY','This-Is-For-Access-Token');
define('REFRESH_SECRET_KEY','This-Is-For-Refresh-Token');
define('ALGORITHM','HS512');

