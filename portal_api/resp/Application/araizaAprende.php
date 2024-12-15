<?php
require '../Config/database.php';

class araizaAprende extends database {
    
    //Categoria
    function eliminarCategoria($id){
        $sql = $this->connect()->prepare("delete from categoria_ar_apr where idCatArApr = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return  $sql ->execute();
    }

    function insertarCategoria($input){
        $sql = $this->connect()->prepare("INSERT INTO categoria_ar_apr(categoria) VALUES (:categoria)");
        $sql->bindParam(":categoria", $input["categoria"], PDO::PARAM_STR, 45);
        return $sql->execute();
    }

    function todoCategorias($id){
        $sql = $this->connect()->prepare("select idCatArApr, categoria from categoria_ar_apr ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //Tema
    function eliminarTema($id){
        $sql = $this->connect()->prepare("delete from tema_ar_apr where idTemaArApr = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql ->execute();
    }

    function insertarTema($input){
        $sql = $this->connect()->prepare("INSERT INTO tema_ar_apr(tema) VALUES (:tema)");
        $sql->bindParam(":tema", $input["tema"], PDO::PARAM_STR, 45);
        
        return $sql->execute();
    }
    
    function todoTemas($id){
        $sql = $this->connect()->prepare("select idTemaArApr ,tema from tema_ar_apr ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
      
    
    //video
    function eliminarVideo($id){
        $sql = $this->connect()->prepare("select img, link from araiza_aprende where idArApr = :id;");
        $sql -> bindParam(":id",$id,PDO::PARAM_INT);
        $sql -> execute();
        $sql = $sql-> fetch(PDO::FETCH_NUM);
        
        unlink('../API/imgVideo/galeria-video-araiza-aprende/fotos/'.$sql[0]);
               
        $sql = $this->connect()->prepare("delete from araiza_aprende where idArApr = :idArApr");
        $sql->bindParam(":idArApr", $id, PDO::PARAM_INT);
        
        return $sql->execute();
    }
    
    
    function insertarVideo($input){
        $sql = $this->connect()->prepare("INSERT INTO araiza_aprende(fk_idCategoria, fk_idTema, nombre, img, link,formato) VALUES ( :idCategoria, :idTema, :nombre, :img, :link, :formato)");
        $sql->bindParam(":idCategoria", $input["idCategoria"], PDO::PARAM_INT);
        $sql->bindParam(":idTema", $input["idTema"], PDO::PARAM_INT);
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR);
        $sql->bindParam(":img", $input["img"], PDO::PARAM_STR);
        $sql->bindParam(":link", $input["link"], PDO::PARAM_STR);
        $sql->bindParam(":formato", $input["formato"], PDO::PARAM_STR);
       
        return  $sql->execute();
    }
    
    function todoTemasCategoria($id){
        $sql = $this->connect()->prepare("select fk_idTema,tema  from araiza_aprende inner join tema_ar_apr on idTemaArApr = fk_idTema  where fk_idCategoria = :id GROUP BY fk_idTema order by fk_idTema desc;");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function selectVideo($id){
        $sql = $this->connect()->prepare("select * from araiza_aprende where fk_idCategoria = :id order by fk_idTema desc");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function todoVideo($id){
        $sql = $this->connect()->prepare("select * from araiza_aprende;");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function editarVideo($input){
        $sql = $this->connect()->prepare("UPDATE araiza_aprende SET fk_idCategoria= :idCategoria,fk_idTema= :idTema,nombre= :nombre,img= :img,link= :link WHERE idArApr= :idArApr ");
        $sql->bindParam(":idCategoria", $input["categoria"], PDO::PARAM_INT);
        $sql->bindParam(":idTema", $input["tema"], PDO::PARAM_INT);
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR);
        $sql->bindParam(":img", $input["img"], PDO::PARAM_STR);
        $sql->bindParam(":link", $input["link"], PDO::PARAM_STR);
        $sql->bindParam(":idArApr", $input["idArApr"], PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    function subirImagen($f){
        $micarpeta = "../API/imgVideo/galeria-video-araiza-aprende/fotos/"; 
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
  
  function elVideoFotoCarp($input){
           $sql = $this->connect()->prepare("select img, link from araiza_aprende where idArApr = :id;");
            $sql -> bindParam(":id",$input,PDO::PARAM_INT);
            $sql -> execute();
            $sql = $sql-> fetch(PDO::FETCH_NUM);
            
            unlink('../API/imgVideo/galeria-video-araiza-aprende/fotos/'.$sql[0]);
            
            return array('se elimino el video/foto' => $sql[0]);
        }
    

}