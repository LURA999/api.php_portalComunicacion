<?php
require '../Config/database.php';

class autoCapac extends database {

    function eliminarCapac($id){
        $sql = $this->connect()->prepare("delete from autocapacitaciones where idAutoCap = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql ->execute();
        return $sql;
    }

    function actualizarCapac($input){
        //echo var_dump($input);
        $sql = $this->connect()->prepare("update autocapacitaciones set nombre = :titulo, link = :link, fechaInicial = :fechaI, fechaFinal = :fechaF, cveLocal = :local where idAutoCap = :id;");
        $sql->bindParam(":titulo", $input["nombre"], PDO::PARAM_STR, 255);
        $sql->bindParam(":fechaI", $input["fechaInicial"], PDO::PARAM_STR,15);
        $sql->bindParam(":fechaF", $input["fechaFinal"], PDO::PARAM_STR, 15);
        $sql->bindParam(":local", $input["cveLocal"], PDO::PARAM_INT);
        $sql->bindParam(":id", $input["idAutoCap"], PDO::PARAM_INT);
        $sql->bindParam(":link", $input["link"], PDO::PARAM_STR);
        $sql->execute();
        return $sql;
    }

    function insertarCapac($input){
        $sql = $this->connect()->prepare("insert into autocapacitaciones (nombre,link,fechaInicial,fechaFinal,cveLocal) 
        values(:nombre, :link, :fechaI, :fechaF, :local)");
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR, 45);
        $sql->bindParam(":fechaI", $input["fechaInicial"], PDO::PARAM_STR,15);
        $sql->bindParam(":fechaF", $input["fechaFinal"], PDO::PARAM_STR, 15);
        $sql->bindParam(":local", $input["cveLocal"], PDO::PARAM_INT);
        $sql->bindParam(":link", $input["link"], PDO::PARAM_STR);
        $sql->execute();
        return $sql;
    }

    function todoCapac($id){
        if ($id == 0) {
            $sql = $this->connect()->prepare("select idAutoCap,a.nombre,link,cveLocal, l.nombre local,fechaInicial,fechaFinal from autocapacitaciones a inner join local l where idLocal = cveLocal order by fechaInicial desc;");
        }else{
            $sql = $this->connect()->prepare("select idAutoCap,a.nombre,link,cveLocal, l.nombre local,fechaInicial,fechaFinal from autocapacitaciones a inner join local l on idLocal = cveLocal where cveLocal = :hotel and (fechaInicial <= CURDATE() AND fechaFinal >= CURDATE()) order by fechaInicial desc;");
            $sql->bindParam(":hotel", $id, PDO::PARAM_INT);
        }
        
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function todoCapacHotel($hotel,$busqueda){
        $like = '%'.$busqueda.'%';

        if ($hotel == -1 ) {
            $sql = $this->connect()->prepare("select idAutoCap,a.nombre,link,cveLocal, l.nombre local,fechaInicial,fechaFinal from autocapacitaciones a inner join local l on idLocal = cveLocal where LOWER(a.nombre) like LOWER(:bus) order by fechaInicial desc;");
            $sql->bindParam(":bus", $like, PDO::PARAM_STR);
        } else {
            $sql = $this->connect()->prepare("select idAutoCap,a.nombre,link,cveLocal, l.nombre local,fechaInicial,fechaFinal from autocapacitaciones a inner join local l on idLocal = cveLocal where LOWER(a.nombre) like LOWER(:bus) and cveLocal = :hotel order by fechaInicial desc;");
            $sql->bindParam(":hotel", $hotel, PDO::PARAM_INT);
            $sql->bindParam(":bus", $like, PDO::PARAM_STR);
        }

        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

}