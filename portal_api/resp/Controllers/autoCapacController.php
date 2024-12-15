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

    function insertarCapac($input){
        try {
            $this->obj = $this->obj->insertarCapac($input);
            $this->msg->exitoso('inserted');

        } catch (Exception $e) {
            $this->msg->noExitoso('self-training',$e);
        }

    }

    function eliminaCapac($id){
        $this->obj = $this->obj->eliminarCapac($id);
        $this->msg->exitoso('deleted');

    }

    function todoCapac($id){
        $this->obj = $this->obj->todoCapac($id);
        $this->msg->select($this->obj,'self-training');

    }

    function todoCapacHotel($hotel,$comida){
        $this->obj = $this->obj->todoCapacHotel($hotel,$comida);
        $this->msg->select($this->obj,'self-training');

    }
}
