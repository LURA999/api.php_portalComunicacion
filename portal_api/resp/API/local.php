<?php
include "../Config/config.php";
include "../Controllers/localController.php";

$obj = new localController();

try{
$input = json_decode(file_get_contents('php://input'),true);
switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
            echo $obj->todoLocal($_GET["opc"]);
        break;
}
}catch(Exception $e){
    $dbcon = null; 
    echo  json_encode(array('status'=>"error",
    'info'=>"error server",
    'container'=>$e));
}