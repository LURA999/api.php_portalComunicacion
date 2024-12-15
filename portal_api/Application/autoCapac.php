<?php
require '../Config/database.php';
error_reporting(0);

class autoCapac extends database {

    //Eliminando capacitacion
    function eliminarCapac($id){
        $sql = $this->connect()->prepare("DELETE FROM autocapacitaciones WHERE idCapacitacion = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $exec = $sql->execute();

        return $exec;
    }

    //Eliminando capacitacion_Det
    function eliminarCapac_det($id){
        $sql = $this->connect()->prepare("delete from autocapacitaciones_det where idAutoCap = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql ->execute();
        return $sql;
    }

    //Actualizando capacitacion
    function actualizarCapac($input){
        //echo var_dump($input);
        $sql = $this->connect()->prepare("UPDATE autocapacitaciones 
        SET nombre = :nombre,
        img = :img,
        link = :link
        WHERE idCapacitacion = :id;");
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR, 255);
        $sql->bindParam(":id", $input["idCapacitacion"], PDO::PARAM_INT);
        $sql->bindParam(":img", $input["img"], PDO::PARAM_STR);
        $sql->bindParam(":link", $input["link"], PDO::PARAM_STR);
        $sql->execute();
        return $sql;
    }

    function actualizarCapacHotel($input){
        //echo var_dump($input);
        $sql = $this->connect()->prepare("update autocapacitaciones 
        set fk_autocapacitacion = :titulo, 
        where idAutoCap = :id;");
        $sql->bindParam(":titulo", $input["nombre"], PDO::PARAM_STR, 255);
        $sql->bindParam(":id", $input["idCapacidad"], PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

    //Creando nueva capacitacion
    function insertarCapac($input){
        $sql = $this->connect()->prepare("INSERT INTO autocapacitaciones (nombre, link, img) 
        VALUES(:nombre, :link, :img)");
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR);
        $sql->bindParam(":img", $input["img"], PDO::PARAM_STR);
        $sql->bindParam(":link", $input["link"], PDO::PARAM_STR);
        $sql->execute();
        return $sql;
    }

    //Mostrando todos las capacitaciones de un hotel en especifico
    function selectCapac($input){
        $sql = $this->connect()->prepare("SELECT idCapacitacion, nombre, link, img FROM autocapacitaciones");
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR, 45);
        $sql->bindParam(":local", $input["cveLocal"], PDO::PARAM_INT);
        $sql->bindParam(":img", $input["link"], PDO::PARAM_STR);
        $sql->execute();
        return $sql;
    }


    //Asignando capacitacion al hotel
    function actualizarAutocapacitacion_Det($input){
        $sql = $this->connect()->prepare("INSERT into autocapacitaciones_det (fk_autocapacitacion, cveLocal) 
        VALUES(:fk_autocapacitacion, :local)");
        $sql->bindParam(":fk_autocapacitacion", $input["fk_autocapacitacion"], PDO::PARAM_STR, 45);
        $sql->bindParam(":local", $input["cveLocal"], PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

    //Mostrar todas las capacitaciones de todos los hoteles
    function todoCapac($id){
        if ($id == 0) {
            $sql = $this->connect()->prepare("select idCapacitacion,a.nombre,link, img 
            from autocapacitaciones a 
            /* INNER JOIN autocapacitaciones_det ad ON fk_autocapacitacion = idCapacitacion 
            INNER JOIN local l on idLocal = ad.cveLocal*/
            /*WHERE idLocal = cveLocal*/ GROUP BY a.nombre");
        }else{
            $sql = $this->connect()->prepare("select idCapacitacion,a.nombre,link, img 
            from autocapacitaciones a 
            /* INNER JOIN autocapacitaciones_det ad ON fk_autocapacitacion = idCapacitacion 
            INNER JOIN local l on idLocal = ad.cveLocal */ 
            /* WHERE ad.cveLocal = :hotel*/ GROUP BY a.nombre");
            // $sql->bindParam(":hotel", $id, PDO::PARAM_INT);
        }
        
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //Mostrar todas las capacitaciones de todos los hoteles pero ahora con mas filtros junto con el like
    function todoCapacHotel($hotel,$busqueda){
        $like = '%'.$busqueda.'%';
        if ($hotel == -1 ) {
            $sql = $this->connect()->prepare("select idCapacitacion,a.nombre,link, img
            from autocapacitaciones a 
           /*  INNER JOIN autocapacitaciones_det ad ON fk_autocapacitacion = idCapacitacion
            INNER JOIN local l on idLocal = ad.cveLocal   */
            WHERE LOWER(a.nombre) like LOWER(:bus) GROUP BY a.nombre");
            $sql->bindParam(":bus", $like, PDO::PARAM_STR);
        } else {
            $sql = $this->connect()->prepare("select idCapacitacion,a.nombre,link, img
            from autocapacitaciones a 
            /* INNER JOIN autocapacitaciones_det ad ON fk_autocapacitacion = idCapacitacion 
            INNER JOIN local l on idLocal = ad.cveLocal */
            WHERE LOWER(a.nombre) like LOWER(:bus) /*and ad.cveLocal = :hotel */ GROUP BY a.nombre");
            //$sql->bindParam(":hotel", $hotel, PDO::PARAM_INT);
            $sql->bindParam(":bus", $like, PDO::PARAM_STR);
        }
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //Sube imagen de la autocapacitación
    function subirImagen($f){
        $micarpeta = "../API/imgVideo/galeria-autocapacitaciones/"; 
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
        $sql = $this->connect()->prepare("select img, link from autocapacitaciones where idCapacitacion = :id;");
        $sql -> bindParam(":id",$id,PDO::PARAM_INT);
        $sql -> execute();
        $sql = $sql-> fetch(PDO::FETCH_NUM);
         
         unlink('../API/imgVideo/galeria-autocapacitaciones/'.$sql[0]);

               
        // $sql = $this->connect()->prepare("delete from autocapacitaciones where idCapacitaciones = :idArApr");
        // $sql->bindParam(":id", $id, PDO::PARAM_INT);
         
        return $sql->execute();
     }

    //Solo mostrar las capacitaciones registradas
    function capacitacionesRegistradas(){
        $sql = $this->connect()->prepare("SELECT idCapacitacion, nombre, img, link FROM autocapacitaciones");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

}