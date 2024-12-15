<?php

require '../Config/config.php';
require '../Controllers/araizaAprendeController.php';

$obj = new araizaAprendeController();

try{
    $input = json_decode(file_get_contents('php://input'), true);

    switch($_SERVER["REQUEST_METHOD"]){
        case 'GET':
            
            if (isset($_GET["todoCategorias"])) {
                echo $obj->todoCategorias($_GET["todoCategorias"]);
            }
            
            if (isset($_GET["todoTemasCategoria"])) {
                echo $obj->todoTemasCategoria($_GET["idTodosTemasCategoria"]);
            }

            if (isset($_GET["todoTemas"])) {
                echo $obj->todoTemas($_GET["todoTemas"]);
            }
            
            if (isset($_GET["idCategoria"])) {
                echo $obj->selectVideo($_GET["idCategoria"]);
            }

            if (isset($_GET["todoVideo"])) {
                echo $obj->todoVideo($_GET["todoVideo"]);
            }
            
            if (isset($_GET["ArApr"])) {
                echo $obj->segundaPageArAp($_GET["ArApr"], $_GET["cat"]);
            }
            
            if (isset($_GET["cat"])) {
                echo $obj->totalVideoIds($_GET["cat"]);
            }
            
            break;
        case 'POST':
            if(isset($_GET["insCateg"])){
                echo $obj->insertarCategoria($input);
            }
            
            if(isset($_GET["insTema"])){
               echo $obj->insertarTema($input); 
            }
            
            if(isset($_GET["insVideo"])){
               echo $obj->insertarVideo($input); 
            }

            if(isset($_FILES['info'])){
               echo $obj->subirImagen($_FILES);
            }
            
            break;
        case 'DELETE':
             if(isset($_GET["idTema"])){
               echo $obj->eliminarTema($_GET["idTema"]); 
            }
             if(isset($_GET["idVideo"])){
               echo $obj->eliminarVideo($_GET["idVideo"]);
            }
            
            if(isset($_GET["idCate"])){
               echo $obj->eliminarCategoria($_GET["idCate"]);
            }
            
            if(isset($_GET["delete2"])){
                echo $obj->elVideoFotoCarp($_GET["delete2"]);
            }
           
            break;
        case 'PATCH':
            echo $obj->editarVideo($input);
            break;
    }
}catch(Exception $e){
    echo var_dump(array('error server' => $e ));
}