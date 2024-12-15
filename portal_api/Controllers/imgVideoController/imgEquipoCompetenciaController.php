<?php
require '../../Application/imgVideo/imgEquipoCompetencia.php';
require '../../Models/message.php';

class imgEquipoCompetenciaController 
{
    private $obj;
    private $msg;

    function __construct (){
        $this->obj = new imgEquipoCompetencia();
        $this->msg = new message();
    }

    /* function insertarVideoImg($var){
        try {
            $this->obj->insertarVideoImg($var);
            $this->msg->exitoso("inserted");
        } catch (Exception $e) {
            $this->msg->noExitoso("imgEquipo",$e);
        }
    } */

    function eliminarVideoImg($var){
        $boolean = $this->obj->eliminarVideoImg($var);
        if($boolean){
            $this->msg->exitoso('deleted');
        }else{
            $this->msg->noExitosoInser();
        }
    }
    
    //se realiza una actualizacion y tambien un select
    function actualizarVideoImg($var){
        $this->obj = $this->obj-> actualizarVideoImg($var);
        $this->msg->exitoso("foto actualizado");
    }

    
    function subirVidImagen($var){
        $this->obj = $this->obj->subirVidImagen($var);
        $this->msg->select($this->obj,"imgVideo");
    }
    
    /*function actualizarVideoImg($var){
        try {
            $this->obj->actualizarVideoImg($var);
            $this->msg->exitoso("updated");
        } catch (Exception $e) {
            $this->msg->noExitoso("imgVideoNoticia",$e);
        }
    }*/

    /* function todosVideoImg($id,$historial,$filtroHistorial){
        $this->obj = $this->obj->todosVideoImg($id,$historial,$filtroHistorial);
        $this->msg->select($this->obj,"imgEquipo");
    } */

    function elVideoFotoCarp($v){
        $this->obj = $this->obj->elVideoFotoCarp($v);
        $this->msg->exitoso("video/foto eliminado");
    }
}
