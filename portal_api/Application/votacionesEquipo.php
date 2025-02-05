<?php
require '../Config/database.php';

class votacionesEquipo extends database {
    function mostrarEquipos(){
        $sql = $this->connect()->prepare("SELECT * FROM `votaciones_equipo`");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function crearEquipo($input){
        $sql = $this->connect()->prepare("
        INSERT INTO `votaciones_equipo`(`nombreEquipo`, `imgEquipo`) VALUES (:nombreEquipo, :imgEquipo)");
        $sql->bindParam(":nombreEquipo", $input["nombreEquipo"], PDO::PARAM_STR);
        $sql->bindParam(":imgEquipo", $input["imgEquipo"], PDO::PARAM_STR);
        return $sql->execute();
    }

    function actualizarEquipo($input){
        $fecha = substr($input["fecha"],0,10);
        $sql = $this->connect()->prepare("update menu set nombre = :titulo, 
        descripcion = :descrip, fecha = :fecha, cveLocal = :local where idMenu = :id;");
        $sql->bindParam(":titulo", $input["nombre"], PDO::PARAM_STR, 255);
        $sql->bindParam(":descrip", $input["descripcion"], PDO::PARAM_STR, 255);
        $sql->bindParam(":fecha", $fecha, PDO::PARAM_STR, 45);
        $sql->bindParam(":local", $input["cveLocal"], PDO::PARAM_INT);
        $sql->bindParam(":id", $input["idMenu"], PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }


    /** 
     * imprimiendo los equipos de acuerdo al hotel
     * SELECT ve.nombreEquipo, vc.nombre  FROM `v_equipo_competencia` vec 
     * INNER JOIN votaciones_equipo ve ON ve.idEquipo =  vec.cveEquipo
     * INNER JOIN votaciones_competencia vc ON vc.idCompetencia = vec.cveCompentencia
     * WHERE cveLocal = 2;
     */

}