<?php
    require '../../Config/database.php';

    class imgVideo extends database
    {

        function insertarVideoImg($input, $actualizar) {
            
            $fi = substr($input["fechaInicial"],0,10);
            $ff = substr($input["fechaFinal"],0,10);
            if ($actualizar === true || $actualizar === "true") {
                $sql = $this->connect()->prepare("update imgVideo set fechaInicial = :fechaInicial , 
                fechaFinal = :fechaFinal, cveLocal = :cveLocal, imgVideo = :imgVideo ,link = :link, formato = :formato ,
                posicion = :posicion
                where cveLocal = :cveLocal and cveSeccion = :cveSeccion ;");
                $sql ->bindParam(":fechaInicial",$fi,PDO::PARAM_STR,15);
                $sql ->bindParam(":fechaFinal",$ff,PDO::PARAM_STR,15);
                $sql ->bindParam(":imgVideo",$input["imgVideo"],PDO::PARAM_STR);
                $sql ->bindParam(":formato", explode("/", $input["formato"])[0] ,PDO::PARAM_STR);
                $sql ->bindParam(":cveLocal",$input["cveLocal"],PDO::PARAM_INT);
                $sql ->bindParam(":cveSeccion",$input["cveSeccion"],PDO::PARAM_INT);
                $sql ->bindParam(":posicion",$input["posicion"],PDO::PARAM_INT);
                $sql ->bindParam(":link",$input["link"],PDO::PARAM_STR);
                $sql->execute();

            } else {
                $sql = $this->connect()->prepare("insert into imgVideo (fechaInicial,fechaFinal,imgVideo,formato,cveLocal,cveSeccion,link,posicion) 
                values(:fechaInicial,:fechaFinal,:imgVideo,:form,:cveLocal,:cveSeccion,:link,:posicion)");
                $sql ->bindParam(":fechaInicial",$fi, PDO::PARAM_STR,30);
                $sql ->bindParam(":fechaFinal",$ff, PDO::PARAM_STR,30);
                $sql ->bindParam(":imgVideo", $input["imgVideo"], PDO::PARAM_STR);
                $sql ->bindParam(":form", explode("/", $input["formato"])[0], PDO::PARAM_STR);
                $sql ->bindParam(":cveLocal",$input["cveLocal"], PDO::PARAM_INT);
                $sql ->bindParam(":cveSeccion",$input["cveSeccion"], PDO::PARAM_INT);
                $sql ->bindParam(":posicion",$input["posicion"],PDO::PARAM_INT);
                $sql ->bindParam(":link",$input["link"],PDO::PARAM_STR);
                $sql = $sql ->execute();

                $sql = $this->connect()->prepare("select count(*) pos, idImgVideo id, (select count(*)  from imgVideo 
                where cveSeccion = :cveSeccion and cveLocal = :cveLocal) total from imgVideo where cveSeccion = :cveSeccion and cveLocal = :cveLocal and posicion = -1;");
                $sql ->bindParam(":cveLocal",$input["cveLocal"], PDO::PARAM_INT);
                $sql ->bindParam(":cveSeccion",$input["cveSeccion"], PDO::PARAM_INT);
                $sql ->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);

            }
        return $sql;
        }
        
        function subirVidImagen($f, $x){
            if(explode("/", $f["info"]['type'])[0] === "image"){
                $micarpeta = "../../API/imgVideo/galeria-slide/fotos/";    
            } else {
                $micarpeta = "../../API/imgVideo/galeria-slide/videos/";
            }
            $imagen_nombre =  $f["info"]['name'];
            try {

                switch ($x) {
                    case 1:
                        $imagen_nombre =  $f["info"]['name'];
                        if(explode("/", $f["info"]['type'])[0] === "image"){
                            $micarpeta = "../../API/imgVideo/galeria-slide/fotos/";    
                        } else {
                            $micarpeta = "../../API/imgVideo/galeria-slide/videos/";
                        }
                    break;
                    case 2:
                        $micarpeta = $this->brrCualqArchiv("../../API/imgVideo/menu/", explode(".",$imagen_nombre)[0]);
                    break;
                    case 3:
                        $micarpeta = $this->brrCualqArchiv("../../API/imgVideo/cumpleanos/", explode(".",$imagen_nombre)[0]);
                    break;
                    case 4:
                        $micarpeta = $this->brrCualqArchiv("../../API/imgVideo/aniversario/", explode(".",$imagen_nombre)[0]);
                    break;
                    case 5:
                        $micarpeta = $this->brrCualqArchiv("../../API/imgVideo/empleado-mes/", explode(".",$imagen_nombre)[0]);
                    break;
                }
            }catch(Exception $e){ }

            $directorio_final = $micarpeta.$imagen_nombre; 
            $contador = 1;
            while(file_exists($directorio_final)){
                $fileNameFull = new SplFileInfo($f['info']['name']);
                $nombreBase = preg_replace("/.[^.]+$/", "", $fileNameFull);
                $imagen_nombre = $nombreBase."(".$contador.").".$fileNameFull->getExtension();
                $directorio_final = $micarpeta.$imagen_nombre;
                $contador ++;
            }
            move_uploaded_file($f['info']['tmp_name'], $directorio_final);
            return array('directorio' => $directorio_final, 'nombre' => $imagen_nombre, 'tipo' => explode("/", $f["info"]['type'])[0]);
        }

        function todosVideoImg($id, $sec) {
            if ($id == -1) {
                $sql = $this->connect()->prepare("select idImgVideo,fechaInicial,fechaFinal,imgVideo,cveLocal,cveSeccion,formato,link, posicion from imgVideo where cveSeccion = :sec order by posicion asc");
                $sql ->bindParam(":sec",$sec,PDO::PARAM_INT);
            } else {
                $sql = $this->connect()->prepare("select idImgVideo,fechaInicial,fechaFinal,imgVideo,cveLocal,cveSeccion,formato,link, posicion from imgVideo where cveSeccion = :sec and cveLocal = :id and fechaFinal >= DATE_FORMAT(curdate(), '%Y-%m-%d') order by posicion asc;");
                $sql ->bindParam(":id",$id,PDO::PARAM_INT);
                $sql ->bindParam(":sec",$sec,PDO::PARAM_INT);
            }
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        function eliminarVideoImg($id) {
            $sql = $this->connect()->prepare("select imgVideo, formato,link,posicion from imgVideo where idImgVideo = :id;");
            $sql -> bindParam(":id",$id,PDO::PARAM_INT);
            $sql -> execute();
            $sql = $sql-> fetch(PDO::FETCH_NUM);
            switch ($sql[1]) {
                case 'image':
                    unlink('../../API/imgVideo/galeria-slide/fotos/'.$sql[0]);
                break;
                case 'video':
                    unlink('../../API/imgVideo/galeria-slide/videos/'.$sql[0]);                
                break;
            }
            $sql = $this->connect()->prepare("delete from imgVideo where idImgVideo = :id");
            $sql ->bindParam(":id",$id,PDO::PARAM_INT);
            $sql->execute();
            return $sql;
        }

        function actualizarVideoImg($input){
            
            $fi = substr($input['obj']["fechaInicial"],0,10);
            $ff = substr($input['obj']["fechaFinal"],0,10);
            //CUANDO QUIERES CAMBIAR TODO EXCPETO EL LOCAL
            if($input['obj']["cveLocal"] == $input['obj']["cveLocal2"]){
                $sql2 = $this->connect()->prepare("select count(*) from imgVideo 
                where posicion = :idP and cveLocal = :cveLocal and cveSeccion = 1;");
                $sql2 -> bindParam(":cveLocal",$input['obj']["cveLocal"],PDO::PARAM_INT);
                $sql2 ->bindParam(":idP",$input['obj']["posicion2"],PDO::PARAM_INT);
                $sql2 ->execute(); 
                $result2 = $sql2->fetch(PDO::FETCH_NUM);

             if(intval($result2[0]) == 2){
                $sql = $this->connect()->prepare("update imgVideo set fechaInicial = :fechaInicial , 
                fechaFinal = :fechaFinal, cveLocal = :cveLocal, imgVideo = :imgVideo, formato = :formato,link = :link,posicion = :posicion 
                where idImgVideo = :idImgVideo ;");
                $sql ->bindParam(":fechaInicial",$fi,PDO::PARAM_STR,15);
                $sql ->bindParam(":fechaFinal",$ff,PDO::PARAM_STR,15);
                $sql ->bindParam(":imgVideo",$input['obj']["imgVideo"],PDO::PARAM_STR);
                $sql ->bindParam(":cveLocal",$input['obj']["cveLocal"],PDO::PARAM_INT);
                $sql ->bindParam(":idImgVideo",$input['obj']["idImgVideo"],PDO::PARAM_INT);
                $sql ->bindParam(":formato",$input['obj']["formato"],PDO::PARAM_STR,5);
                $sql ->bindParam(":posicion",$input['obj']["posicion"],PDO::PARAM_INT);
                $sql ->bindParam(":link",$input['obj']["link"],PDO::PARAM_STR);
                $sql->execute();
                return $sql;
             }else{
                $sql = $this->connect()->prepare("update imgVideo set fechaInicial = :fechaInicial , 
                fechaFinal = :fechaFinal, cveLocal = :cveLocal, imgVideo = :imgVideo, formato = :formato,link = :link
                where idImgVideo = :idImgVideo ;");
                $sql ->bindParam(":fechaInicial",$fi,PDO::PARAM_STR,15);
                $sql ->bindParam(":fechaFinal",$ff,PDO::PARAM_STR,15);
                $sql ->bindParam(":imgVideo",$input['obj']["imgVideo"],PDO::PARAM_STR);
                $sql ->bindParam(":cveLocal",$input['obj']["cveLocal"],PDO::PARAM_INT);
                $sql ->bindParam(":idImgVideo",$input['obj']["idImgVideo"],PDO::PARAM_INT);
                $sql ->bindParam(":formato",$input['obj']["formato"],PDO::PARAM_STR,5);
                $sql ->bindParam(":link",$input['obj']["link"],PDO::PARAM_STR);
                $sql->execute();
             }
            } else {

                //CUANDO CAMBIAS TODO, INCLUYENDO EL LOCAL
                $sql1 = $this->connect()->prepare("select count(*) from imgVideo 
                where cveLocal = :cveLocal and cveSeccion = 1;");
                $sql1 -> bindParam(":cveLocal",$input['obj']["cveLocal"],PDO::PARAM_INT);
                $sql1 ->execute(); 
                $result1 = $sql1->fetch(PDO::FETCH_NUM);

                $sql1 = $this->connect()->prepare("select count(*) from imgVideo 
                where cveLocal = :cveLocal and cveSeccion = 1 and posicion = :posicion;");
                $sql1 -> bindParam(":cveLocal",$input['obj']["cveLocal"],PDO::PARAM_INT);
                $sql1 -> bindParam(":posicion",$input['obj']["posicion"],PDO::PARAM_INT);
                $sql1 ->execute(); 
                $result2 = $sql1->fetch(PDO::FETCH_NUM);
    

                if(intval($result1[0]) == 0 ){
                    $input['obj']["posicion"] = 1;
                }
                if(intval($result1[0]) > 0 && ($result2[0] == 0 || $result2[0] == "0")){
                    $input['obj']["posicion"] = intval($result1[0]) + 1; 
                } 

                $sql = $this->connect()->prepare("update imgVideo set fechaInicial = :fechaInicial , 
                fechaFinal = :fechaFinal, cveLocal = :cveLocal, imgVideo = :imgVideo, formato = :formato,link = :link,posicion = :posicion
                where idImgVideo = :idImgVideo ;");
                $sql -> bindParam(":fechaInicial",$fi,PDO::PARAM_STR,15);
                $sql -> bindParam(":fechaFinal",$ff,PDO::PARAM_STR,15);
                $sql -> bindParam(":imgVideo",$input['obj']["imgVideo"],PDO::PARAM_STR);
                $sql -> bindParam(":cveLocal",$input['obj']["cveLocal"],PDO::PARAM_INT);
                $sql -> bindParam(":idImgVideo",$input['obj']["idImgVideo"],PDO::PARAM_INT);
                $sql -> bindParam(":formato",$input['obj']["formato"],PDO::PARAM_STR,5);
                $sql -> bindParam(":posicion",$input['obj']["posicion"],PDO::PARAM_INT);
                $sql -> bindParam(":link",$input['obj']["link"],PDO::PARAM_STR);
                $sql -> execute();
                return $sql;
        
        }
            
        }

        function elVideoFotoCarp($input){
            $sql = $this->connect()->prepare("select imgVideo, formato, link, posicion from imgVideo where idImgVideo = :id;");
            $sql -> bindParam(":id",$input,PDO::PARAM_INT);
            $sql -> execute();
            $sql = $sql-> fetch(PDO::FETCH_NUM);
            switch ($sql[1]) {
                case 'image':
                    unlink('../../API/imgVideo/galeria-slide/fotos/'.$sql[0]);
                break;
                case 'video':
                    unlink('../../API/imgVideo/galeria-slide/videos/'.$sql[0]);                
                break;
            }
            return array('se elimino el video/foto' => $sql[0]);
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

        //actualizar todos, cuando se inserta un nuevo slider y este interrumpe con la posicion de otro
        // ESTE NOMAS SE ACTIVA CUANDO EL SLIDE ES TOTALMENTE NUEVO
        function actualizarTUPos($in){
            $sql = $this->connect()->prepare("update imgVideo set posicion = posicion + 1 
            where posicion >= :id and cveLocal = :cveLocal and cveSeccion = :cveSeccion");
            $sql ->bindParam(":id",$in["idP"],PDO::PARAM_INT); 
            $sql ->bindParam(":cveLocal",$in["cveLocal"],PDO::PARAM_INT); 
            $sql ->bindParam(":cveSeccion",$in["cveSeccion"],PDO::PARAM_INT);
            $sql->execute();
            return $sql;
        }

        //actualizar todos, cuando se inserta un nuevo slider y este interrumpe con la posicion de otro
        // ESTE NOMAS SE ACTIVA CUANDO EL SLIDE ES VIEJO
        function actualizarTUVPos($in){
            $sql = $this->connect()->prepare("update imgVideo set posicion = posicion + 1 
            where posicion >= :idP1 and cveLocal = :cveLocal and cveSeccion = :cveSeccion and idImgVideo <> :idP2");
            $sql ->bindParam(":idP1",$in["idP1"],PDO::PARAM_INT); 
            $sql ->bindParam(":idP2",$in["idP2"],PDO::PARAM_INT); 
            $sql ->bindParam(":cveLocal",$in["cveLocal"],PDO::PARAM_INT); 
            $sql ->bindParam(":cveSeccion",$in["cveSeccion"],PDO::PARAM_INT);
            $sql->execute();
            return $sql;
        }

        //actualizar todos, cuando se elimina un slider
        function actualizarTDPos($id,$local,$seccion){
            $sql = $this->connect()->prepare("update imgVideo set posicion = posicion - 1 
            where posicion > :id and cveLocal = :cveLocal and cveSeccion = :cveSeccion");
            $sql ->bindParam(":id",$id,PDO::PARAM_INT);
            $sql ->bindParam(":cveLocal",$local,PDO::PARAM_INT);
            $sql ->bindParam(":cveSeccion",$seccion,PDO::PARAM_INT);
            $sql->execute();
            return $sql;
        }

        /* actualizar la posicion de un slider al anterior de otro slider, este solo se ejecuta cuando se quiere
        intercambiar posiciones con otro slider */
        function actualizarUCPos($in){
            $sql = $this->connect()->prepare("select count(*) from imgVideo 
            where posicion = :idP and cveLocal = :cveLocal and cveSeccion = 1;");
            $sql -> bindParam(":cveLocal",$in["cveLocal"],PDO::PARAM_INT);
            $sql -> bindParam(":idP",$in["idP"],PDO::PARAM_INT);
            $sql -> execute(); 
            $result = $sql->fetch(PDO::FETCH_NUM);
            if(intval($result[0]) == 1){
                $sql = $this->connect()->prepare("update imgVideo set posicion = :idS
                where posicion = :idP and cveLocal = :cveLocal and cveSeccion = 1");
                $sql ->bindParam(":idP",$in["idP"],PDO::PARAM_INT);
                $sql ->bindParam(":idS",$in["idS"],PDO::PARAM_INT);
                $sql ->bindParam(":cveLocal",$in["cveLocal"],PDO::PARAM_INT);
                $sql->execute(); 
            }
            return $sql;
        }

        //actualizar la posicion de un slider (update normal)
        function actualizarUPos($in){
            $sql = $this->connect()->prepare("update imgVideo set posicion = :idS
            where idImgVideo = :idP");
            $sql ->bindParam(":idP",$in["idP"],PDO::PARAM_INT);
            $sql ->bindParam(":idS",$in["idS"],PDO::PARAM_INT);
            $sql->execute();
            return $sql;
        }
    }