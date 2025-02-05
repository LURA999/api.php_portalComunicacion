<?php
    require '../../Config/database.php';

    class imgVideoNoticia extends database
    {
     
        function insertarVideoImg($input) {
            $fi = substr($input["fechaInicial"],0,10);
            $ff = substr($input["fechaFinal"],0,10);
            $sql = $this->connect()->prepare("insert into noticia (fechaInicial,fechaFinal,imgVideo,formato,cveLocal,titulo, descripcion,link) 
            values(:fechaInicial,:fechaFinal,:imgVideo,:form,:cveLocal,:titulo,:descripcion,:link)");
            $sql ->bindParam(":fechaInicial",$fi,PDO::PARAM_STR,30);
            $sql ->bindParam(":fechaFinal",$ff,PDO::PARAM_STR,30);
            $sql ->bindParam(":imgVideo", $input["imgVideo"],PDO::PARAM_STR);
            $sql ->bindParam(":form", explode("/", $input["formato"])[0] ,PDO::PARAM_STR);
            $sql ->bindParam(":cveLocal",$input["cveLocal"],PDO::PARAM_INT);
            $sql ->bindParam(":titulo", $input["titulo"],PDO::PARAM_STR,50);
            $sql ->bindParam(":descripcion",$input["descripcion"],PDO::PARAM_STR);
            $sql ->bindParam(":link",$input["link"],PDO::PARAM_STR);
            $sql->execute();
            return $sql;
        }
        
        function subirVidImagen($f){
            
            if(explode("/", $f["info"]['type'])[0] === "image"){
                $micarpeta = "../../API/imgVideo/galeria-noticia/fotos/";    
            } else {
                $micarpeta = "../../API/imgVideo/galeria-noticia/videos/";
            }

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

        function todosVideoImg($id,$historial,$filtroHistorial) {
            if ($id == -1 || $id == -2) {
                switch (intval($id)) {
                    case -1:
                        //Activos
                        $sql = $this->connect()->prepare("select idNoticia,fechaInicial,fechaFinal,imgVideo, formato,cveLocal,titulo, descripcion,link from noticia where fechaFinal >= DATE_FORMAT(curdate(), '%Y-%m-%d') and fechaInicial <= fechaFinal order by fechaInicial desc;");
                        break;
                    case -2:
                        //Desactivados
                        $sql = $this->connect()->prepare("select idNoticia,fechaInicial,fechaFinal,imgVideo, formato,cveLocal,titulo, descripcion,link from noticia where fechaFinal < DATE_FORMAT(curdate(), '%Y-%m-%d') order by fechaInicial desc;");
                        break;
                }
           }else {
                if ($historial > 0) {
                    //cuando estas en la vista del historial
                    if ($filtroHistorial>0) {
                        $sql = $this->connect()->prepare("select idNoticia,fechaInicial,fechaFinal,imgVideo,cveLocal, formato,titulo, descripcion,link from noticia where cveLocal = :id and (MONTH(fechaInicial) = :fecha /*or MONTH(fechaFinal) = :fecha*/) order by fechaInicial desc;");
                        $sql ->bindParam(":fecha",$filtroHistorial,PDO::PARAM_INT);
                        $sql ->bindParam(":id",$id,PDO::PARAM_INT);
                    } else {
                        $sql = $this->connect()->prepare("select idNoticia,fechaInicial,fechaFinal,imgVideo,cveLocal, formato,titulo, descripcion,link from noticia where cveLocal = :id order by fechaInicial desc;");
                        $sql ->bindParam(":id",$id,PDO::PARAM_INT);
                    }
                } else {
                    //cuando no estas en la vista del historial
                    $sql = $this->connect()->prepare("select idNoticia,fechaInicial,fechaFinal,imgVideo,cveLocal, formato,titulo, descripcion,link from noticia where cveLocal = :id and (fechaInicial >= DATE_FORMAT(curdate(), '%Y-%m-%d') or DATE_FORMAT(curdate(), '%Y-%m-%d') <= fechaFinal) order by fechaInicial desc;");
                    $sql ->bindParam(":id",$id,PDO::PARAM_INT);    
                }
            }
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }



        function eliminarVideoImg($id) {
            

            $sql = $this->connect()->prepare("select imgVideo, formato, link from noticia where idNoticia = :id;");
            $sql -> bindParam(":id",$id,PDO::PARAM_INT);
            $sql -> execute();
            $sql = $sql-> fetch(PDO::FETCH_NUM);
            switch ($sql[1]) {
                case 'image':
                    unlink('../../API/imgVideo/galeria-noticia/fotos/'.$sql[0]);
                    break;
                case 'video':
                    unlink('../../API/imgVideo/galeria-noticia/videos/'.$sql[0]);                
                    break;
            }
            
            $sql = $this->connect()->prepare("delete from noticia where idNoticia = :id;");
            $sql -> bindParam(":id",$id,PDO::PARAM_INT);
            $sql -> execute();
            /*
            $sql = $this->connect()->prepare("delete from noticia where idNoticia = :id");
            $sql ->bindParam(":id",$id,PDO::PARAM_INT);
            $sql->execute();*/
            return $sql;
        }


        function actualizarVideoImg($input){
            $fi = substr($input["fechaInicial"],0,10);
            $ff = substr($input["fechaFinal"],0,10);
           /* $sql2 = $this->connect()->prepare("select idNoticia,fechaInicial,fechaFinal,imgVideo, formato,cveLocal,titulo, descripcion from noticia;");
            $sql2 -> execute();
            $sql2 = $sql2 -> fetchAll(PDO::FETCH_ASSOC);*/
            $sql = $this->connect()->prepare("update noticia set fechaInicial = :fechaInicial , 
            fechaFinal = :fechaFinal, cveLocal = :cveLocal, titulo = :titulo, descripcion = :descripcion,
            imgVideo = :imgVideo, formato = :formato, link = :link where idNoticia = :idNoticia;");
            $sql ->bindParam(":fechaInicial",$fi,PDO::PARAM_STR,15);
            $sql ->bindParam(":fechaFinal",$ff,PDO::PARAM_STR,15);
            $sql ->bindParam(":cveLocal",$input["cveLocal"],PDO::PARAM_INT);
            $sql ->bindParam(":titulo",$input["titulo"],PDO::PARAM_STR,150);
            $sql ->bindParam(":imgVideo",$input["imgVideo"],PDO::PARAM_STR);
            $sql ->bindParam(":descripcion",$input["descripcion"],PDO::PARAM_STR);
            $sql ->bindParam(":idNoticia",$input["idNoticia"],PDO::PARAM_INT);
            $sql ->bindParam(":formato",$input["formato"],PDO::PARAM_STR,5);
            $sql ->bindParam(":link",$input["link"],PDO::PARAM_STR);
            $sql->execute();
            return $sql;
        }

        function elVideoFotoCarp($input){
            $sql = $this->connect()->prepare("select imgVideo, formato, link from noticia where idNoticia = :id;");
            $sql -> bindParam(":id",$input,PDO::PARAM_INT);
            $sql -> execute();
            $sql = $sql-> fetch(PDO::FETCH_NUM);
            switch ($sql[1]) {
                case 'image':
                    unlink('../../API/imgVideo/galeria-noticia/fotos/'.$sql[0]);
                    break;
                case 'video':
                    unlink('../../API/imgVideo/galeria-noticia/videos/'.$sql[0]);                
                    break;
            }
            return array('se elimino el video/foto' => $sql[0]);
        }
    }
