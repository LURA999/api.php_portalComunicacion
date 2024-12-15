<?php
require '../Config/database.php';

class menu extends database {

    function eliminarComida($id){
        $sql = $this->connect()->prepare("delete from menu where idMenu = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql ->execute();
        return $sql;
    }

    function actualizarComida($input){
        //echo var_dump($input);
        $sql = $this->connect()->prepare("update menu set nombre = :titulo, descripcion = :descrip, fecha = :fecha, cveLocal = :local where idMenu = :id;");
        $sql->bindParam(":titulo", $input["nombre"], PDO::PARAM_STR, 255);
        $sql->bindParam(":descrip", $input["descripcion"], PDO::PARAM_STR, 255);
        $sql->bindParam(":fecha", $input["fecha"], PDO::PARAM_STR, 45);
        $sql->bindParam(":local", $input["cveLocal"], PDO::PARAM_INT);
        $sql->bindParam(":id", $input["idMenu"], PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

    function insertarComida($input){
        $sql = $this->connect()->prepare("insert into menu (nombre,descripcion,fecha,cveLocal) 
        values(:titulo, :descrip, :fecha, :local)");
        $sql->bindParam(":titulo", $input["nombre"], PDO::PARAM_STR, 45);
        $sql->bindParam(":descrip", $input["descripcion"], PDO::PARAM_STR);
        $sql->bindParam(":fecha", $input["fecha"], PDO::PARAM_STR, 45);
        $sql->bindParam(":local", $input["cveLocal"], PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

    function todoComida($id,$opc){
       
        switch(intval($opc)){
            case 1:
                if ($id == 0) {
                    $sql = $this->connect()->prepare("select idMenu,m.nombre,cveLocal,loc.nombre local,descripcion,fecha from menu m inner join local loc where idLocal = cveLocal order by fecha asc;");
                }else{
                    $sql = $this->connect()->prepare("select idMenu,m.nombre,cveLocal,loc.nombre local,descripcion,fecha from menu m inner join local loc on idLocal = cveLocal where cveLocal = :hotel order by fecha desc;");
                    $sql->bindParam(":hotel", $id, PDO::PARAM_INT);
                }
            break;
            case 2:
                $sql = $this->connect()->prepare("select idMenu,m.nombre,cveLocal,loc.nombre local,descripcion,fecha from menu m inner join local loc on idLocal = cveLocal where cveLocal = :hotel and MONTH(fecha) = MONTH(CURRENT_DATE())  and DAY(CURRENT_DATE()) <= DAY(fecha) order by fecha asc;");
                $sql->bindParam(":hotel", $id, PDO::PARAM_INT);
            break;
        }
        

        /*
        if ($id == 0) {
            $sql = $this->connect()->prepare("select idMenu,m.nombre,cveLocal,loc.nombre local,descripcion,fecha from menu m inner join local loc where idLocal = cveLocal order by fecha desc;");
        }else{
            $sql = $this->connect()->prepare("select idMenu,m.nombre,cveLocal,loc.nombre local,descripcion,fecha from menu m inner join local loc on idLocal = cveLocal where cveLocal = :hotel order by fecha desc;");
            $sql->bindParam(":hotel", $id, PDO::PARAM_INT);
        }
        */
        
        
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function todoComidaHotel($hotel,$busqueda){
        $like = '%'.$busqueda.'%';
        if ($hotel == 0 ) {
            $sql = $this->connect()->prepare("select idMenu,m.nombre,cveLocal,loc.nombre local,descripcion,fecha from menu m inner join local loc on idLocal = cveLocal where LOWER(m.nombre) like LOWER(:bus) order by fecha desc;");
            $sql->bindParam(":bus", $like, PDO::PARAM_STR);
        } else {
            $sql = $this->connect()->prepare("select idMenu,m.nombre,cveLocal,loc.nombre local,descripcion,fecha from menu m inner join local loc  on idLocal = cveLocal where LOWER(m.nombre) like LOWER(:bus) and cveLocal = :hotel order by fecha desc;");
            $sql->bindParam(":hotel", $hotel, PDO::PARAM_INT);
            $sql->bindParam(":bus", $like, PDO::PARAM_STR);
        }


        /*if ($hotel == 0 ) {
            $sql = $this->connect()->prepare("select idUsuario,usuario,nombres,apellidoPaterno,apellidoMaterno, IFNULL(fecha, 'No tiene') fecha from empleado_mes right join usuario on cveUsuario = idUsuario where LOWER(concat(nombres, ' ',apellidoPaterno,' ',apellidoMaterno)) like LOWER(:bus) order by fecha desc;");
            $sql->bindParam(":bus", $like, PDO::PARAM_STR);
        } else {
            $sql = $this->connect()->prepare("select idUsuario,usuario,nombres,apellidoPaterno,apellidoMaterno, IFNULL(fecha, 'No tiene') fecha from empleado_mes right join usuario on cveUsuario = idUsuario where LOWER(concat(nombres, ' ',apellidoPaterno,' ',apellidoMaterno)) like LOWER(:bus) and cveLocal = :hotel order by fecha desc;");
            $sql->bindParam(":hotel", $hotel, PDO::PARAM_INT);
            $sql->bindParam(":bus", $like, PDO::PARAM_STR);
        }*/
 
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

}