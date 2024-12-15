<?php
require '../Config/config.php';
require '../Controllers/araizaAprendeFormController.php';

$obj = new araizaAprendeFormController();

try{
    $input = json_decode(file_get_contents('php://input'), true);

    switch($_SERVER["REQUEST_METHOD"]){
        case 'GET':
            if (isset($_GET["idForm"]) && isset($_GET["datos"])) {
                echo $obj->imprimirDatosPrincipalesForm($_GET["idForm"]);
            }
            
            if (isset($_GET["idForm"]) && isset($_GET["preguntas"])) {
                echo $obj->imprimirFormularioPreguntas($_GET["idForm"]);
            }
            
            if (isset($_GET["idUsuario"]) && isset($_GET["respuestas"])) {
                echo $obj->imprimirFormularioRespuestas($_GET["idUsuario"], $_GET["form"], $_GET["local"]);
            }
            
            if (isset($_GET["titulos"])){
                echo $obj->imprimirFormularios();
            }
            
            break;
        case 'POST':
            if(isset($_GET["insertarRespuesta"])){
               echo $obj->insertarRespuesta($input); 
            }
            break;
        case 'DELETE':
            if(isset($_GET["idCate"])){
               echo $obj->eliminarCategoria($_GET["idCate"]);
            }
            break;
        case 'PATCH':
            if(isset($_GET["editarPreguntaTexto"])){
               echo $obj->editarPreguntaTexto($input);
            }
            
            if(isset($_GET["editarRespuestaTexto"])){
               echo $obj->editarRespuestaTexto($input);
            }
            
            if(isset($_GET["editarRespuesta"])){
               echo $obj->editarRespuesta($input);
            }
            
            if(isset($_GET["editarEncabezado"])){
               echo $obj->editarEncabezado($input);
            }
            
            if(isset($_GET["editarDescripcion"])){
               echo $obj->editarDescripcion($input);
            }
            
            break;
    }
}catch(Exception $e){
    echo var_dump(array('error server' => $e ));
}