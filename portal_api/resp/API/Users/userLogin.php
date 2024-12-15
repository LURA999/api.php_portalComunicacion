<?php
include "../../Config/config.php";
include "../../Controllers/UsersController/userLoginController.php";

$obj = new userLoginController();

try{
$input = json_decode(file_get_contents('php://input'),true);
switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['contrasena'])) {
            echo $obj->userLogin($_GET['usuario'],$_GET['contrasena']);
            
        }else if(isset($_GET['palabra'])){
            echo $obj->searchUser($_GET['palabra']!=null && $_GET['palabra']!="null"?$_GET['palabra']:'',
            $_GET['hotel'] !=null && $_GET['hotel']!="null"? $_GET['hotel'] : -1,
            isset($_GET['fechaInicial']) == true? $_GET['fechaInicial'] : null,
            isset($_GET['fechaFinal']) == true? $_GET['fechaFinal'] : null ,
            $_GET['op'],
            $_GET['tipoVista'] != null && $_GET['tipoVista']!="null"? $_GET['tipoVista'] : 2);
            
        } else if (isset($_GET['birt'])) {
            echo $obj->getAllUsersBirth($_GET['birt']);
            
        } else if (isset($_GET['aniv'])) {
            echo $obj->getAllUsersAniv($_GET['aniv']);
            
        }else if (isset($_GET['mesi'])) {
            echo $obj->getAllUsersMesi($_GET['mesi']);
            
        }else if(isset($_GET["RepetidoUpdate"]) && !isset($_GET["RepetidoInsert"])) {
            echo $obj->buscarRepetidoUpdate($_GET['user'],$_GET['cve'],$_GET['id']);
            
        }else if(isset($_GET["RepetidoInsert"]) && !isset($_GET["RepetidoUpdate"])) {
            echo $obj->buscarRepetidoInsert($_GET['user'], $_GET['cve']);
            
        }else if(isset($_GET["departamento"])){
            echo $obj->todosDepartamentos();
            
        }else{
            echo $obj->getAllUsers($_GET['us_op']);
        }

        break;
    case 'PATCH':
        if(isset($_GET['departamento'])){
            echo $obj->updateDepartamento($input);
        }else{
            echo $obj->updateUser($input,$_GET["modalidad"]);
        }
        break;
    case 'DELETE':
        if(isset($_GET['departamento'])){
            echo $obj->deleteDepartamento($_GET['departamento']);
        }else{
            echo $obj->deleteUser($_GET["id"]);
        }
        break;
    case 'POST':
        if(isset($_GET['departamento']) ){
            echo $obj->insertDepartamento($input);
        } else if (isset($_GET['visita'])) {
            echo $obj->insertVisita($input);
        }else{
            echo $obj->createUser($input);
        }
        break;
}
}catch(Exception $e){
    $dbcon = null; 
    echo  json_encode(array('status'=>"error",
    'info'=>"error server",
    'container'=>$e));
}
