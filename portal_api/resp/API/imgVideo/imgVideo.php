<?php

require '../../Config/config.php';
require '../../Controllers/imgVideoController/imgVideoController.php';

$obj = new imgVideoController();
try{

    $input = json_decode(file_get_contents('php://input'),true);
    switch ($_SERVER["REQUEST_METHOD"]) {
        case 'GET':
            $obj->todosVideoImg($_GET["cvLoc"],$_GET["cvSec"]);
        break;
        case 'POST':
        if (isset($_FILES['info'])) { 
            $obj-> subirVidImagen($_FILES,$_GET["var"]);
        } else {
            $obj-> insertarVideoImg($input,$_GET["act"]);
        }
        break;
        case 'DELETE':
            if (isset($_GET["delete"])) {
                $obj->eliminarVideoImg($_GET["delete"]);
            }

            if (isset($_GET["delete2"])) {
                $obj->elVideoFotoCarp($_GET["delete2"]);
            }
            
            if(isset($_GET["posTD"]) && !isset($_GET["posU"])&& !isset($_GET["posTU"])){
                $obj-> actualizarTDPos($_GET["posTD"],$_GET["cveLocal"],$_GET["cveSeccion"]);
            }
        break;
        case 'PATCH':
            if (isset($input["obj"])) {
                if (count($input["obj"]) > 2) {
                    $obj-> actualizarVideoImg($input);

                }
            } 

            if(isset($_GET["postTUV"]) && !isset($_GET["posTU"]) && !isset($_GET["posU"]) && !isset($_GET["posTD"]) && !isset($_GET["posUC"])){
                $obj-> actualizarTUVPos($input);
            }

            if(isset($_GET["posTU"]) && !isset($_GET["posU"]) && !isset($_GET["posTD"]) && !isset($_GET["postTUV"]) && !isset($_GET["posUC"])){ 
                $obj-> actualizarTUPos($input);
            }

            if(isset($_GET["posUC"]) && !isset($_GET["posTU"]) && !isset($_GET["posU"]) && !isset($_GET["postTUV"]) && !isset($_GET["posTD"])){
                $obj->actualizarUCPos($input);
            }
           
            if(isset($_GET["posU"]) && !isset($_GET["posTU"]) && !isset($_GET["posTD"]) && !isset($_GET["postTUV"]) && !isset($_GET["posUC"])){
                $obj-> actualizarUPos($input);
            }
            
        break;
    }
}catch(Exception $e){
    echo json_encode(array(
        'status' => '404', 
        'info' => "don't work server",
        'container' => $e));
}

