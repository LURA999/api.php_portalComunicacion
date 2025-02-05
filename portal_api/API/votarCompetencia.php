<?php
require '../Config/config.php';
require '../Controllers/votarCompetenciaController.php';

$obj = new votarCompetenciaController();

try{
    $input = json_decode(file_get_contents('php://input'), true);

    switch($_SERVER["REQUEST_METHOD"]){
        case 'GET':
            if (isset($_GET["local"]) && isset($_GET["nombre"])) {
                echo $obj->imprimirDatosCompetencia($_GET["local"],$_GET["nombre"]);
            }
            
            if (isset($_GET["dComp"])) {
                echo $obj->imprimirUsuariosCompetencia($_GET["dComp"], $_GET["local"]);
            }
            
            if(isset($_GET["gvotar"])){
                echo $obj->comprobarVotacion($_GET["user"], $_GET["local"]);
            }
            
            if(isset($_GET["imprimirEvAct"])){
                echo $obj->imprimirEventoActivada($_GET["local"]);
            }
            
            if(isset($_GET["excel"])){
                echo $obj->imprimirDatosExcel($_GET["icomp"]);
            }
            
            if(isset($_GET["votos"])){
                echo $obj->contadorDeVotos($_GET["icomp"]);
            }
            
            break;
        case 'POST':
            if(isset($_GET["iComp"])){
               echo $obj->insertarCompetencia($input); 
            }
            
            if(isset($_GET["iUsuariosComp"])){
               echo $obj->insertarUsuariosCompetencia($input); 
            }
            
            if(isset($_GET["activarCompeticion"])){
                echo $obj->actualizarActividad($input);
            }
            
            if(isset($_GET["ivotar"])){
                echo $obj->insertarVotacion($input);
            }
            
            break;
        case 'DELETE':
            if(isset($_GET["idU"]) && isset($_GET["idC"])){
               echo $obj->eliminarUsuariosCompetencia($_GET["idU"], $_GET["idC"]);
            }
            
            if(isset($_GET["idC"]) && !isset($_GET["idU"])){
               echo $obj->eliminarCompetencia($_GET["idC"]);
            }
            
            break;
        case 'PATCH':
            if(isset($_GET["editarPreguntaTexto"])){
               echo $obj->actualizarCompetencia($input);
            }
            
            if(isset($_GET["activarActividad"])){
                echo $obj->actualizarActividad($input);
            }
            
            break;
    }
}catch(Exception $e){
    echo var_dump(array('error server' => $e ));
}