<?php
require '../../Application/imgVideo/imgVideo.php';
require '../../Models/message.php';

class imgVideoController 
{
    private $obj;
    private $msg;

    function __construct (){
        $this->obj = new imgVideo();
        $this->msg = new message();
    }

    function insertarVideoImg($var,$act){
        try { 
            $this->obj = $this->obj->insertarVideoImg($var,$act);
            $this->msg->select($this->obj,"imgVideo");
        } catch (Exception $e) {
            $this->msg->noExitoso("imgVideo",$e);
        }
    }

    function subirVidImagen($var, $x){
        $this->obj = $this->obj->subirVidImagen($var, $x);
        $this->msg->select($this->obj,"imgVideo");
    }

    function eliminarVideoImg($var){
        $this->obj->eliminarVideoImg($var);
        $this->msg->exitoso("deleted");
    }

    //se realiza una actualizacion y tambien un select
    function actualizarVideoImg($var){
        $this->obj = $this->obj-> actualizarVideoImg($var);
        $this->msg->exitoso("video/foto actualizado");
    }

    function actualizarTUPos($in) {
        $this->obj = $this->obj-> actualizarTUPos($in);
        $this->msg->exitoso("video/foto actualizado");
    }

    function actualizarTUVPos($in) {
        $this->obj = $this->obj-> actualizarTUVPos($in);
        $this->msg->exitoso("video/foto actualizado");
    }

    function actualizarTDPos($id,$local,$seccion) {
        $this->obj = $this->obj-> actualizarTDPos($id,$local,$seccion);
        $this->msg->exitoso("video/foto actualizado");
    }
    
    function actualizarUPos($in){
        $this->obj = $this->obj-> actualizarUPos($in);
        $this->msg->exitoso("video/foto actualizado");
    }

    function actualizarUCPos($in){
        $this->obj = $this->obj-> actualizarUCPos($in);
        $this->msg->exitoso("video/foto actualizado");
    }

    function todosVideoImg($id,$sec){
        $this->obj = $this->obj->todosVideoImg($id,$sec);
        $this->msg->select($this->obj,"imgVideo");
    }

    function elVideoFotoCarp($v){
        $this->obj = $this->obj->elVideoFotoCarp($v);
        $this->msg->exitoso("video/foto eliminado");
    }

}
