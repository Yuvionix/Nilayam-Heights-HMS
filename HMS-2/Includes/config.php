<?php
// Database configuration with environment variable support for cloud/docker deployments
define('DB_SERVER', getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost');
define('DB_USERNAME', getenv('DB_USER') ? getenv('DB_USER') : 'root');
define('DB_PASSWORD', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_NAME', getenv('DB_NAME') ? getenv('DB_NAME') : 'hms');
define('DB_PORT', getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306);
 
/* Attempt to connect to MySQL database */
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
 
// Check connection
if($con === false){
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}
?>