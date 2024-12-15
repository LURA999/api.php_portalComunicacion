<?php
require '../../Config/database.php';
error_reporting(0);

class img extends database{

    function subirImagen($f){
        $micarpeta = "../../API/imgVideo/fotos/";    
        $imagen_nombre =  $f["info"]['name'];
        $directorio_final = $micarpeta.$imagen_nombre; 
        $contador = 1;
        while(file_exists($directorio_final)){
            $fileNameFull = new SplFileInfo($f['info']['name']);
            $nombreBase = preg_replace("/\.[^.]+$/", "", $fileNameFull);
            $imagen_nombre = $nombreBase."(".$contador.").".$fileNameFull->getExtension();
            $directorio_final = $micarpeta.$imagen_nombre;
            $contador ++;
        }
        move_uploaded_file($f['info']['tmp_name'], $directorio_final);
        return array('directorio' => $directorio_final, 'nombre' => $imagen_nombre, 'tipo' => explode("/", $f["info"]['type'])[0]);
    }

    function eliminarImagen($id){
        unlink('../../API/imgVideo/fotos/'.$id);
    }

    function actualizarImagen($img){
        $id = explode(".", $img)[0];
        $idLocal = explode("_",$id)[1];
        $sql = $this->connect()->prepare("update usuario set img = :img where usuario = :id and cveLocal = :idLocal");
        $sql ->bindParam(":img",$img,PDO::PARAM_STR);
        $sql ->bindParam(":id",$id,PDO::PARAM_INT);
        $sql ->bindParam(":idLocal",$idLocal,PDO::PARAM_INT);
        $sql->execute();
    }

    function brrCualqArchiv($ruta, $imagen_nombre){

        $que_archivos_borrar = $imagen_nombre;
        $que_thumb_borrar = "_thumb_".$imagen_nombre;
        $a_eliminar = scandir($ruta);
    foreach ($a_eliminar as $elemento) {
        $path = pathinfo($elemento); // con esto obtengo el nombre del archivo sin extension.
        if ($path['filename'] == $que_archivos_borrar || $path['filename'] == $que_thumb_borrar) {
            $borrar = $ruta . $elemento;
            unlink($borrar);
            return $ruta;
            }
        }
    }

    function identificarRuta($h){
        switch (intval($h)) {
            case 0:
                return '../../API/imgVideo/fotos/';
            case 1:
                return '../../API/imgVideo/fotos/mxl/';
            case 2:
                return '../../API/imgVideo/fotos/cal/';
            case 3:
                return '../../API/imgVideo/fotos/sl/';
            case 4:
                return '../../API/imgVideo/fotos/pal/';
            case 5:
                return '../../API/imgVideo/fotos/hmo/';
        }
    }

    function actualizarNombre($in){
        rename("../../API/imgVideo/fotos/".$in["obj"]['imgn'], "../../API/imgVideo/fotos/".$in["obj"]['img']);

        $consulta = 'UPDATE usuario set img = :img
        where idUsuario = :idUsuario';
        $sql = $this->connect()->prepare($consulta);
        $sql->bindparam(':img', $in["obj"]['img'], PDO::PARAM_STR);
        $sql->bindparam(':idUsuario', $in["obj"]['idUsuario'], PDO::PARAM_INT);
        $sql = $sql->execute();     
        
        return $sql;

    }
}