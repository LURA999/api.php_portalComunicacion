<?php
require '../Config/config.php';
require '../Controllers/votacionesEquipoController.php';

$obj = new votacionesEquipoController();

try{
    $input = json_decode(file_get_contents('php://input'), true);
    switch($_SERVER["REQUEST_METHOD"]){
        case 'GET':
            echo $obj->mostrarEquipos();
            break;
        case 'POST':
            echo $obj->crearEquipo($input);
            break;
        case 'PATCH':
            echo $obj->actualizarEquipo($input);
            break;
        case 'DELETE':
            break;
    }
}catch(Exception $e){
    echo var_dump(array('error server' => $e ));
}