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

            if(isset($_GET["capacitacionesRegistradas"])){
                echo $obj->capacitacionesRegistradas();
            }

            if (isset($_GET["hotel"]) && !isset($_GET['palabra'])) {
                echo $obj->todoCapac($_GET["hotel"]);
            }
            break;
        case 'POST':
            if(isset($_FILES['info'])){
                echo $obj->subirImagen($_FILES);
             }

             if (isset($input['link'])) {
                echo $obj->insertarCapac($input);
             }
             
             if (isset($input['fk_autocapacitacion'])) {
                echo $obj->actualizarAutocapacitacion_Det($input);
             }

            break;
        case 'DELETE':
            
            if (isset($_GET["eliminarCapacitacion"])){
                echo $obj->eliminarCapac($_GET["eliminarCapacitacion"]);
            }

            if (isset($_GET["eliminarCapacitacionHotel"])){
                echo $obj->eliminarCapac_det($_GET["eliminarCapacitacionHotel"]);
            }

            if (isset($_GET["eliminarImagen"])){
                echo $obj->eliminarImagen($_GET["eliminarImagen"]);
            }

            break;
        case 'PATCH':
            if (isset($_GET["hotel"])) {
                echo $obj->actualizarCapacHotel($input);
            }

            if (isset($_GET["todo"])) {
                echo $obj->actualizarCapac($input);
            }
            break;
    }
}catch(Exception $e){
    echo var_dump(array('error server' => $e ));
}