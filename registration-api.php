<?php
require_once 'database.php';
require_once 'registration.php';

$registration = new registration($conn);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET,PUT,DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type:application/json");

if($_SERVER['REQUEST_METHOD']==='OPTIONS'){
    http_response_code(200);
    exit();
}
 
$method =$_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
    echo $registration->create();
    break;
    default:
     header('HTTP/1.0 405 Method Not Allowed');
    break; 
}

?>