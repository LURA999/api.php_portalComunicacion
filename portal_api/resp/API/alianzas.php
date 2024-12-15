<?php

require '../Config/config.php';
require '../Controllers/alianzasController.php';

$obj = new autoCapacController();

try{
    $input = json_decode(file_get_contents('php://input'), true);

    switch($_SERVER["REQUEST_METHOD"]){
        case 'GET':
            if (isset($_GET["hotel"])) {
                echo $obj->todoAlianza($_GET["hotel"]);
            }
            break;
    }
}catch(Exception $e){
    echo var_dump(array('error server' => $e ));
}