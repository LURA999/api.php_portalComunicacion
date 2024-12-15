<?php
include_once '../Application/local.php';
require '../Models/message.php';

class localController {
    private $obj;
    private $msg;

    function __construct() {
        $this->obj = new local();
        $this->msg = new message();

    } 

    function todoLocal ($var){
        $this->obj = $this->obj -> todoLocal($var);
        $this->msg->select($this->obj,"seccion");
    }
}