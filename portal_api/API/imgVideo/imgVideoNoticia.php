<?php
require '../../Config/config.php';
require '../../Controllers/imgVideoController/imgVideoNoticiaController.php';

$obj = new imgVideoNoticiaController();

try{
    $input = json_decode(file_get_contents('php://input'),true);
    switch ($_SERVER["REQUEST_METHOD"]) {

        case 'GET':
                $obj->todosVideoImg($_GET["cvLoc"],$_GET["historial"],$_GET["filtroHistorial"]);
            break;
        case 'POST':
            if (isset($_FILES['info'])) {
                $obj->subirVidImagen($_FILES);
            } else {
                $obj-> insertarVideoImg($input);
            }
            break;
        case 'DELETE':
            if (isset($_GET["delete"])) {
                $obj->eliminarVideoImg($_GET["delete"]);
            } else {
                $obj->elVideoFotoCarp($_GET["delete2"]);
            }  
            break;
        case 'PATCH':
                $obj->actualizarVideoImg($input);
            break;
    }
}catch(Exception $e){
    echo json_encode(array(
        'status' => '404', 
        'info' => "don't work server",
        'container' => $e));
}
