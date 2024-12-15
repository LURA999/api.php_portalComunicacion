<?php
require '../Application/araizaAprende.php';
require_once '../Models/message.php';

class araizaAprendeController
{
    private $obj;
    private $msg;

    function __construct()
    {
        $this->obj = new araizaAprende();
        $this->msg = new message();

    }
    
    //categoria
    
    function eliminarCategoria($id){
        $this->obj = $this->obj->eliminarCategoria($id);
        if($this->obj){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExitoso('inserted',[]);
        }

    }
    
    function insertarCategoria($input){
        $this->obj = $this->obj->insertarCategoria($input);
        if($this->obj){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExitoso('inserted',[]);
        }
    }
    
    
    function todoCategorias($id){
        $this->obj = $this->obj->todoCategorias($id);
        $this->msg->select($this->obj,'categorias');
    }

    //tema
    function eliminarTema($id){
        $this->obj = $this->obj->eliminarTema($id);
        if($this->obj){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExitoso('inserted',[]);
        }

    }
    
    function insertarTema($tema){
        $this->obj = $this->obj->insertarTema($tema);
        if($this->obj){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExitoso('inserted',[]);
        }
    }
    
    function todoTemas($id){
        $this->obj = $this->obj->todoTemas($id);
        $this->msg->select($this->obj,'categorias');
    }

  //video
    function eliminarVideo($id){
        $this->obj = $this->obj->eliminarVideo($id);
        if($this->obj){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExitoso('inserted',[]);
        }

    }
    
    function insertarVideo($input){
        $this->obj = $this->obj->insertarVideo($input);
        if($this->obj){
            $this->msg->exitoso('inserted');
        }else{
            $this->msg->noExitoso('inserted',[]);
        }
    }
    
    function selectVideo($id){
        $this->obj = $this->obj->selectVideo($id);
        $this->msg->select($this->obj,'categorias');
    }
    
    function todoVideo($id){
        $this->obj = $this->obj->todoVideo($id);
        $this->msg->select($this->obj,'categorias');
    }
    
    function segundaPageArAp($id,$cat){
        $this->obj = $this->obj->segundaPageArAp($id,$cat);
        $this->msg->select($this->obj,'tuto');
    }
    
    function totalVideoIds($cat){
        $this->obj = $this->obj->totalVideoIds($cat);
        $this->msg->select($this->obj,'tuto');
    }
    
    function todoTemasCategoria($id){
        $this->obj = $this->obj->todoTemasCategoria($id);
        $this->msg->select($this->obj,'temas');
    }
    
    function editarVideo($input){
        $this->obj = $this->obj->editarVideo($input);
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
    
    function elVideoFotoCarp($v){
        $this->obj = $this->obj->elVideoFotoCarp($v);
        $this->msg->exitoso("video eliminado");
    }
    
}
