<?php

require '../Config/config.php';
require '../Controllers/menuController.php';

$obj = new menuController();

try{
    $input = json_decode(file_get_contents('php://input'), true);

    switch($_SERVER["REQUEST_METHOD"]){
        case 'GET':
            if (isset($_GET["hotel"]) && isset($_GET['buscar'])) {
                echo $obj->todoComidaHotel($_GET["hotel"],$_GET["buscar"]);
            }

            if (isset($_GET["hotel"]) && !isset($_GET['buscar'])) {
                echo $obj->todoComida($_GET["hotel"], $_GET["opc"]);
            }
            break;
        case 'POST':
            echo $obj->insertarComida($input);
            break;
        case 'DELETE':
            echo $obj->eliminaComida($_GET["idComida"]);
            break;
        case 'PATCH':
            echo $obj->actualizarComida($input);
            break;
    }
}catch(Exception $e){
    echo var_dump(array('error server' => $e ));
}