
<?php
require '../../Config/config.php';
require '../../Controllers/UsersController/imgController.php';

$obj = new imgController();

try{
    $input = json_decode(file_get_contents('php://input'),true);
    switch ($_SERVER["REQUEST_METHOD"]) {
        case 'POST':
            echo $obj->subirImagen($_FILES);
            break;
        case 'DELETE':
            echo $obj->eliminarImagen($_GET["delete"]);
            break;
        case 'PATCH':
            if (isset($_GET["imgn"]) && !isset($_GET["id"])) {
                echo $obj->actualizarNombre($input);
            }else{
                echo $obj->actualizarImagen($_GET["id"]);
            }
            break;
        default:
            # code...
            break;
    }
}catch(Exception $e){
    $dbcon = null; 
    echo  json_encode(array('status'=>"error",
    'info'=>"error server",
    'container'=>$e));
}
