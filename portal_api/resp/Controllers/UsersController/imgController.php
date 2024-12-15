<?php
require '../../Application/Users/img.php';
require '../../Models/message.php';

class imgController {

    private $obj;
    private $msg;

    function __construct(){
         $this->obj = new img();
         $this->msg = new message();

    }

    function subirImagen($f){
        $this->obj = $this->obj->subirImagen($f);
        $this->msg->select($this->obj,"img usuario");
    }

    function actualizarImagen($id){
        try {
            $this->obj = $this->obj->actualizarImagen($id);
            $this->msg->exitoso("deleted"); 
        } catch (Exception $e) {
            $this->msg->noExitoso("deleted",$e); 
        }
    }

    function actualizarNombre($id){
        try {
            $this->obj = $this->obj->actualizarNombre($id);
            $this->msg->exitoso("updated"); 
        } catch (Exception $e) {
            $this->msg->noExitoso("deleted",$e); 
        }
    }


    function eliminarImagen($id){
        $this->obj->eliminarImagen($id);
        $this->msg->exitoso("deleted");
    }
}