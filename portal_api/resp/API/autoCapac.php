<?php

require '../Config/config.php';
require '../Controllers/autoCapacController.php';

$obj = new autoCapacController();

try{
    $input = json_decode(file_get_contents('php://input'), true);

    switch($_SERVER["REQUEST_METHOD"]){
        case 'GET':

            if (isset($_GET["hotel"]) && isset($_GET['palabra'])) {
                echo $obj->todoCapacHotel($_GET["hotel"],$_GET["palabra"]);
            }

            if (isset($_GET["hotel"]) && !isset($_GET['palabra'])) {
                echo $obj->todoCapac($_GET["hotel"]);
            }
            break;
        case 'POST':
            echo $obj->insertarCapac($input);
            break;
        case 'DELETE':
            echo $obj->eliminaCapac($_GET["idAutoCap"]);
            break;
        case 'PATCH':
            echo $obj->actualizarCapac($input);
            break;
    }
}catch(Exception $e){
    echo var_dump(array('error server' => $e ));
}