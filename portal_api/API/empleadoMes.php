<?php
require '../Config/config.php';
require '../Controllers/empleadoMesController.php';

$obj = new empleadoMesController();

try{
$input = json_decode(file_get_contents('php://input'),true);
switch($_SERVER['REQUEST_METHOD']) {
    case 'DELETE':
        echo $obj->eliminaFechaCam($_GET["idUsuario"]);
        break;
    case 'PATCH':

        //Actualizaciones normales
        if (isset($input["update"]) ) {
            
            if ($input["update"] == true) {
                echo $obj->actualizarPos($input);
            }
    
            if ($input["update"] == false) {
                echo $obj->actualizarFechaCam($input);
            }        
        }

        //Actualizacion de muchos
        if (isset($_GET["todosD"]) && !isset($input["update"]) && !isset($_GET["fechaCamU"]) && !isset($_GET["todosTU"])) {
            echo $obj ->actualizarDFechaCambio($input);
        }

        if (isset($_GET["todosTU"]) && !isset($input["update"]) && !isset($_GET["todosD"]) && !isset($_GET["fechaCamU"])) {
            echo $obj ->actualizarTUFechaCambio($input);
        }

        if (isset($_GET["fechaCamU"]) && !isset($_GET["todosTU"]) && !isset($input["update"]) && !isset($_GET["todosD"])) {
            echo $obj ->actualizarUFechaPos($input);
        }
        
    break;
    case 'POST':
        echo $obj->insertarFechaCam($input);
    break;
    case 'GET':
        echo $obj->empleadosTotal($_GET["cveLocal"]);
    break;
}
}catch(Exception $e){
    $dbcon = null; 
    echo  json_encode(array('status'=>"error",
    'info'=>"error server",
    'container'=>$e));
}