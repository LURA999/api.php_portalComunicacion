<?php
require '../Application/votacionesEquipo.php';
require_once '../Models/message.php';

class votacionesEquipoController
{
    private $obj;
    private $msg;

    
    function __construct() {
        $this->obj = new votacionesEquipo();
        $this->msg = new message();
    }

    function mostrarEquipos(){
        $this->obj = $this->obj->mostrarEquipos();
        $this->msg->select($this->obj,'equipo');
    }

    function crearEquipo($input){
        $boolean = $this->obj->crearEquipo($input);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExitosoInser();
        }
    }

    function actualizarEquipo($input){
        $boolean = $this->obj->actualizarEquipo($input);
        if($boolean){
            $this->msg->exitoso('updated');
        }else{
            $this->msg->noExitosoInser();
        }
    }



}