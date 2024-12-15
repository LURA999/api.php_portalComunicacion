<?php
require '../Application/autoCapac.php';
require_once '../Models/message.php';

class autoCapacController
{
    private $obj;
    private $msg;

    function __construct()
    {
        $this->obj = new autoCapac();
        $this->msg = new message();

    }

    function actualizarCapac($input){
        $this->obj = $this->obj->actualizarCapac($input);
        $this->msg->exitoso(' updated');
    }

    function actualizarCapacHotel($input){
        $this->obj = $this->obj->actualizarCapacHotel($input);
        $this->msg->exitoso(' updated');
    }

    function insertarCapac($input){
        try {
            $this->obj = $this->obj->insertarCapac($input);
            $this->msg->exitoso('inserted');

        } catch (Exception $e) {
            $this->msg->noExitoso('self-training',$e);
        }

    }

    function eliminarCapac($id){
        $this->obj = $this->obj->eliminarCapac($id);

        if($this->obj){
            $this->msg->exitoso('deleted');
        }else{
            $this->msg->noExitoso('deleted',[]);
        }

    }

    function eliminarCapac_det($id){
        $this->obj = $this->obj->eliminarCapac_det($id);
        if($this->obj){
            $this->msg->exitoso('deleted');
        }else{
            $this->msg->noExitoso('deleted',[]);
        }

    }

    function todoCapac($id){
        $this->obj = $this->obj->todoCapac($id);
        $this->msg->select($this->obj,'self-training');

    }

    function todoCapacHotel($hotel,$comida){
        $this->obj = $this->obj->todoCapacHotel($hotel,$comida);
        $this->msg->select($this->obj,'self-training');

    }

    function eliminarImagen($id){
        $this->obj = $this->obj->eliminarImagen($id);
        if($this->obj){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExitoso('inserted',[]);
        }
    }

    function subirImagen($var){
        $this->obj = $this->obj->subirImagen($var);
        $this->msg->select($this->obj,"imgVideo");
    }

    function actualizarAutocapacitacion_Det($input){
        try {
            $this->obj = $this->obj->actualizarAutocapacitacion_Det($input);
            $this->msg->exitoso('inserted');

        } catch (Exception $e) {
            $this->msg->noExitoso('self-training',$e);
        }
    }

    function capacitacionesRegistradas(){
        $this->obj = $this->obj->capacitacionesRegistradas();
        $this->msg->select($this->obj,"capacitacionesRegistradas");
    }
}
