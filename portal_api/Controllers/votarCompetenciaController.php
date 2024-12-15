<?php
require '../Application/votarCompetencia.php';
require_once '../Models/message.php';

class votarCompetenciaController
{
    private $obj;
    private $msg;

    function __construct()
    {
        $this->obj = new votarCompetencia();
        $this->msg = new message();
    }
    
    function imprimirDatosCompetencia($local,$nombre){
        $this->obj = $this->obj->imprimirDatosCompetencia($local,$nombre);
        $this->msg->select($this->obj,'tuto');
    }
    
    function imprimirUsuariosCompetencia($fkCompentencia, $local){
        $this->obj = $this->obj->imprimirUsuariosCompetencia($fkCompentencia, $local);
        $this->msg->select($this->obj,'competencias');
    }
    
    function comprobarVotacion($cveUsuario, $local){
        $this->obj = $this->obj->comprobarVotacion($cveUsuario, $local);
        $this->msg->select($this->obj,'competencias');
    }
    function imprimirEventoActivada($local){
        $this->obj = $this->obj->imprimirEventoActivada($local);
        $this->msg->select($this->obj,'competencias');
    }
    
    function imprimirDatosExcel($comp){
        $this->obj = $this->obj->imprimirDatosExcel($comp);
        $this->msg->select($this->obj,'competencias');
    }
    
    function contadorDeVotos($comp){
        $this->obj = $this->obj->contadorDeVotos($comp);
        $this->msg->select($this->obj,'competencias');
    }
    
    
    function actualizarCompetencia($input){
        $boolean = $this->obj->actualizarCompetencia($input);
        if($boolean){
            $this->msg->exitoso('updated');
        }else{
            $this->msg->noExistosoInser();
        }
    }
    
    function actualizarActividad($input){
        $boolean = $this->obj->actualizarActividad($input);
        if($boolean){
            $this->msg->exitoso('updated');
        }else{
            $this->msg->noExistosoInser();
        }
    }
    
    function insertarCompetencia($input){
        echo $this->obj->insertarCompetencia($input);
    }
    
    function insertarUsuariosCompetencia($input){
        $boolean = $this->obj->insertarUsuariosCompetencia($input);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExistosoInser();
        }
    }
    
    function insertarVotacion($input){
        $boolean = $this->obj->insertarVotacion($input);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExistosoInser();
        }
    }
    
    function eliminarUsuariosCompetencia($idU,$idC){
    $this->obj = $this->obj->eliminarUsuariosCompetencia($idU,$idC);
        if($this->obj){
        $this->msg->exitoso('deleted');
        }else{
        $this->msg->noExitoso('deleted',[]);
        }
    }

    function eliminarCompetencia($id){
    $this->obj = $this->obj->eliminarCompetencia($id);
        if($this->obj){
        $this->msg->exitoso('deleted');
        }else{
        $this->msg->noExitoso('deleted',[]);
        }
    }
    
    
}