<?php
require '../Application/empleadoMes.php';
require_once '../Models/message.php';

class empleadoMesController
{
    private $obj;
    private $msg;

    function __construct()
    {
        $this->obj = new empleadoMes();
        $this->msg = new message();

    }

    function actualizarFechaCam($input){
        $this->obj = $this->obj->actualizarFechaCam($input);
        $this->msg->exitoso('updated');
    }


    function eliminaFechaCam($id){
        $this->obj = $this->obj->eliminaFechaCam($id);
        $this->msg->exitoso('deleted');

    }

    function insertarFechaCam($input){
        $this->obj = $this->obj->insertarFechaCam($input);
        $this->msg->exitoso('inserted');
    }

    function actualizarPos($input){
        $this->obj = $this->obj->actualizarPos($input);
        $this->msg->exitoso('updated');

    }
    function empleadosTotal($var){
        $this->obj = $this->obj -> empleadosTotal($var);
        $this->msg->select($this->obj,"seccion");
    }

    function actualizarDFechaCambio($input){
        $this->obj = $this->obj->actualizarDFechaCambio($input);
        $this->msg->exitoso('updated');
    }

    function actualizarTUFechaCambio($input){
        $this->obj = $this->obj->actualizarTUFechaCambio($input);
        $this->msg->exitoso('updated');
    }

    function actualizarUFechaPos($input){
        $this->obj = $this->obj->actualizarUFechaPos($input);
        $this->msg->exitoso('updated');
    }
    
}
