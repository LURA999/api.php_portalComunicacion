<?php
require '../Application/araizaAprendeForm.php';
require_once '../Models/message.php';

class araizaAprendeFormController
{
    private $obj;
    private $msg;

    function __construct()
    {
        $this->obj = new araizaAprendeForm();
        $this->msg = new message();

    }
    
    function imprimirDatosPrincipalesForm($id){
        $this->obj = $this->obj->imprimirDatosPrincipalesForm($id);
        $this->msg->select($this->obj,'tuto');
    }
    
    function imprimirFormularioPreguntas($id){
        $this->obj = $this->obj->imprimirFormularioPreguntas($id);
        $this->msg->select($this->obj,'tuto');
    }
    
    function imprimirFormularioRespuestas($id, $f, $local){
        $this->obj = $this->obj->imprimirFormularioRespuestas($id, $f, $local);
        $this->msg->select($this->obj,'tuto');
    }
    
    function imprimirFormularios(){
        $this->obj = $this->obj->imprimirFormularios();
        $this->msg->select($this->obj,'tuto');
    }
    
    
    function editarPreguntaTexto($input){
        $boolean = $this->obj->editarPreguntaTexto($input);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExistosoInser();
        }
    }
    
    function editarRespuestaTexto($input){
        $boolean = $this->obj->editarRespuestaTexto($input);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExistosoInser();
        }
    }
    
    function editarRespuesta($input){
        $boolean = $this->obj->editarRespuesta($input);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExistosoInser();
        }
    }
    
    function editarEncabezado($input){
        $boolean = $this->obj->editarEncabezado($input);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExistosoInser();
        }
    }
    
    function editarDescripcion($input){
        $boolean = $this->obj->editarDescripcion($input);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExistosoInser();
        }
    }
    
    
    function insertarRespuesta($i){
        $boolean = $this->obj->insertarRespuesta($i);
        if($boolean){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExistosoInser();
        }
    }
    
    
    
    
    
    
    
    function eliminarCategoria($id){
    $this->obj = $this->obj->eliminarCategoria($id);
        if($this->obj){
        $this->msg->exitoso('inserted');
        }else{
        $this->msg->noExitoso('inserted',[]);
        }
    }
    
    
    
}