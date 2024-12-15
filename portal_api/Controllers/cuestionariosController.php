<?php

require '../Application/cuestionarios.php';
require_once '../Models/message.php';

class CuestionariosController
{
    private $obj;
    private $msg;

    function __construct(){
        $this->obj = new cuestionarios();
        $this->msg = new message();
    }

    function todoCuestionarios() {
        $this->obj = $this->obj->todoCuestionarios();
        $this->msg->select($this->obj,"seccion");
    }

    function traerCuestionarioTitulo($input) {
        $this->obj = $this->obj->traerCuestionarioTitulo($input);
        $this->msg->select($this->obj,"cuestionarioTitulo");
    }

    function traerCuestionarioPreguntas($input) {
        $this->obj = $this->obj->traerCuestionarioPreguntas($input);
        $this->msg->select($this->obj,"cuestionarioPreguntas");
    }
    
    function enviarCuestionario($input) {
        $this->obj = $this->obj->enviarCuestionario($input);
        $this->msg->exitoso("cuestionarioPreguntas");
    }

    function actualizarModDesc($input) {
        $boolean = $this->obj->actualizarModDesc($input);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExitosoInser();
        }
    }

    function insertarModDesc($input) {
       echo $this->obj->insertarModDesc($input);
    }

    function insertarCuestionario($input) {
 	$boolean = $this->obj->insertarCuestionario($input);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExitosoInser();
        }
    }

    function eliminarCuestionario($id) {
        $boolean = $this->obj->eliminarCuestionario($id);
        if($boolean){
            $this->msg->exitoso('deleted');
        }else{
            $this->msg->noExitosoInser();
        }
    }

    function eliminarPregunta($id) {
        $boolean = $this->obj->eliminarPregunta($id);
        if($boolean){
            $this->msg->exitoso('deleted');
        }else{
            $this->msg->noExitosoInser();
        }
    }

    function eliminarRespuesta($id) {
        $boolean = $this->obj->eliminarRespuesta($id);
        if($boolean){
            $this->msg->exitoso('deleted');
        }else{
            $this->msg->noExitosoInser();
        }
    }

}
