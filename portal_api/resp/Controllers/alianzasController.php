<?php
require '../Application/alianzas.php';
require_once '../Models/message.php';

class autoCapacController
{
    private $obj;
    private $msg;

    function __construct()
    {
        $this->obj = new alianzas();
        $this->msg = new message();

    }

    function todoAlianza($id){
        $this->obj = $this->obj->todoAlianza($id);
        $this->msg->select($this->obj,'alianzas');

    }

}
