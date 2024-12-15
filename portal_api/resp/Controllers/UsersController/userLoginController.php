<?php
include_once '../../Application/Users/userLogin.php';
include_once '../../Models/message.php';

class userLoginController {
    private $obj;
    private $msg;

    function __construct() {
        $this->obj = new userLogin();
        $this->msg = new message();

    } 

    function userLogin ($usuario, $contrasena){
        $this->obj = $this->obj -> getLogin($usuario, $contrasena);
      if($this->obj['error'] == 1){
            echo json_encode(array(
                'status' => 'not found',
                'info' => 'Usuario no encontrado',
            ));
        }else{
            echo json_encode(array(
                'status' => 'ok',
                'info' => 'User found',
                'container' => $this->createToken($this->obj)
            ));
        }
    }
    function userLoginPass ($input){
        try{
        $this->obj = $this->obj -> updateLoginPass($input);
        echo json_encode(array(
            'status' => 'ok',
            'info' => 'password updated',
            'container' => null
        ));
    }catch(Exception $e){
        return  json_encode(array('status'=>"error",
        'info'=>$e->getMessage(),
        'container'=>null));
    }
    }

    function userLoginLevel ($input){
        try{
        $this->obj = $this->obj -> updatedLoginLevel($input);
        echo json_encode(array(
            'status' => 'ok',
            'info' => 'password updated',
            'container' => null
        ));
    }catch(Exception $e){
        return  json_encode(array('status'=>"error",
        'info'=>$e->getMessage(),
        'container'=>null));
    }
    }
    
    function createToken($info)
    {
        // echo var_dump($info);
        $headers = ['alg' => 'HS256', 'typ' => 'JWT'];
        $headers_encoded = base64_encode(json_encode($headers));
        $payload = [
        'nombres'=> $info['nombres'],
        'correo'=> $info['correo'],
        'id' => $info["id"],
        'cveRol' => $info["cveRol"],
        'cveLocal' => $info["cveLocal"],
        'expire'=>microtime(true)];
        $payload_encoded = base64_encode(json_encode($payload));
        $key = 'secret';
        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $key, true);
        $signature_encoded = base64_encode($signature);
        $token = "$headers_encoded.$payload_encoded.$signature_encoded";
        return $token;

    }

    function updateUser($input,$modalidad){
        $this->obj->updateUser($input,$modalidad);
        $this->msg->exitoso("usuario actualizado");
    }

    function deleteUser($id){
        $this->obj->deleteUser($id);
        $this->msg->exitoso("user deleted");
    }

    function createUser($input){
        try { 
            $this->obj->createUser($input);
            $this->msg->exitoso("inserted");
        } catch (Exception $e) {
            $this->msg->noExitoso("user",$e);
        }
    }

    function searchUser($user,$hotel,$fechaInicial,$fechaFinal,$var,$tipoVista){
        $this->obj = $this->obj->searchUser($user, $hotel,$fechaInicial,$fechaFinal,$var,$tipoVista);
        $this->msg->select($this->obj, "users");
    }


    function buscarRepetidoInsert($user, $cve){
        $this->obj = $this->obj->buscarRepetidoInsert($user, $cve);
        $this->msg->select($this->obj, "users");
    }

    function buscarRepetidoUpdate($user,$cve,$id){
        $this->obj = $this->obj->buscarRepetidoUpdate($user,$cve,$id);
        $this->msg->select($this->obj, "users");
    }

    function getAllUsers($var){
        $this->obj = $this->obj->getAllUsers($var);
        $this->msg->select($this->obj, "users");
    }
    
    function todosDepartamentos(){
        $this->obj = $this->obj->todosDepartamentos();
        $this->msg->select($this->obj, "users");
    }


    function getAllUsersBirth($hotel){
        $this->obj =  $this->obj->getAllUsersBirth($hotel);
        $this->msg->select($this->obj, "users");
    }

    function getAllUsersAniv($aniv){
        $this->obj =  $this->obj->getAllUsersAniv($aniv);
        $this->msg->select($this->obj, "users");
    }

    function getAllUsersMesi($aniv){
        $this->obj =  $this->obj->getAllUsersMesi($aniv);
        $this->msg->select($this->obj, "users");
    }

    function insertVisita($aniv){
        try { 
            $this->obj->insertVisita($aniv);
            $this->msg->exitoso("inserted");
        } catch (Exception $e) {
            $this->msg->noExitoso("user",$e);
        }
    }
    
    function insertDepartamento($input){
        $this->obj = $this->obj->insertDepartamento($input);
        if($this->obj){
            $this->msg->exitoso('departamento');
        }else{
            $this->msg->noExitoso('departamento',[]);
        }
    }
    
    function updateDepartamento($input){
        $this->obj = $this->obj->updateDepartamento($input);
        if($this->obj){
            $this->msg->exitoso('departamento');
        }else{
            $this->msg->noExitoso('departamento',[]);
        }
    }
    
    function deleteDepartamento($input){
        $this->obj = $this->obj->deleteDepartamento($input);
        if($this->obj){
            $this->msg->exitoso('departamento');
        }else{
            $this->msg->noExitoso('departamento',[]);
        }
    }


}