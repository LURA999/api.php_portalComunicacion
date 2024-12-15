<?php

require '../Config/config.php';
require '../Controllers/cuestionariosController.php';

$obj = new CuestionariosController();

try{
    $input = json_decode(file_get_contents('php://input'),true);
    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            // Todos los cuestionarios
            if($_GET['llave'] == 1){
                echo $obj->todoCuestionarios();
            }
            // Cuestionario titulo por id
            if($_GET['llave'] == 2){
                $id = $_GET['id'];
                echo $obj->traerCuestionarioTitulo($id);
            }
            if($_GET['llave'] == 3){
                $id = $_GET['id'];
                echo $obj->traerCuestionarioPreguntas($id);
            }
        break;
        case 'POST':
            if($_GET['llave'] == 4){
                echo $obj->enviarCuestionario($input);
            }
            
            if($_GET['llave'] == 5){
                echo $obj->actualizarModDesc($input);
            }

            if($_GET['llave'] == 6){
                echo $obj->insertarCuestionario($input);
            }
	    
	    if($_GET['llave'] == 7){
                echo $obj->insertarModDesc($input);
            }
            
        break;
        case 'DELETE':
            if(isset($_GET["idP"])){
                echo $obj -> eliminarPregunta($_GET['idP']);
            }

            if(isset($_GET["id"])){
                echo $obj -> eliminarCuestionario($_GET['id']);
            }

            if(isset($_GET["idA"])){
                echo $obj -> eliminarRespuesta($_GET['idA']);
            }

    }
}catch(Exception $e){
    $dbcon = null; 
    echo  json_encode(array('status'=>"error",
    'info'=>"error server",
    'container'=>$e));
}