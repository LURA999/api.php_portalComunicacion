<?php
require '../Application/menu.php';
require_once '../Models/message.php';

class menuController
{
    private $obj;
    private $msg;

    function __construct()
    {
        $this->obj = new menu();
        $this->msg = new message();

    }

    function actualizarComida($input){
        $this->obj = $this->obj->actualizarComida($input);
        $this->msg->exitoso(' updated');
    }

    function insertarComida($input){
        try {
            $this->obj = $this->obj->insertarComida($input);
            $this->msg->exitoso('inserted');

        } catch (Exception $e) {
            $this->msg->noExitoso('food',$e);
        }

    }

    function eliminaComida($id){
        $this->obj = $this->obj->eliminarComida($id);
        $this->msg->exitoso('deleted');

    }

    function todoComida($id, $opc){
        $this->obj = $this->obj->todoComida($id, $opc);
        $this->msg->select($this->obj,'food');

    }

    function todoComidaHotel($hotel,$comida){
        $this->obj = $this->obj->todoComidaHotel($hotel,$comida);
        $this->msg->select($this->obj,'food');

    }
}
