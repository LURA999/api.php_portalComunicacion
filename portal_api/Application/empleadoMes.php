<?php
require '../Config/database.php';

class empleadoMes extends database {

    function eliminaFechaCam($id){
        $sql = $this->connect()->prepare("delete from empleado_mes where cveUsuario = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql ->execute();
        return $sql;
    }

    //este actualiza a las demas posiciones cuando se elimina una posicion de algun hotel.
    function actualizarDFechaCambio($input){
        $sql = $this->connect()->prepare("update empleado_mes inner join usuario on cveUsuario = idUsuario set posicion = posicion - 1 
        where ".(($input["cveLocal"] == 1 || $input["cveLocal"] == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")." 
        and posicion > :posicion and cveUsuario = idUsuario");
        $sql->bindParam(":posicion", $input['posicion'], PDO::PARAM_INT);
        
        if($input["cveLocal"] >1) {
            $sql->bindParam(":cveLocal", $input['cveLocal'], PDO::PARAM_INT);
        }
        $sql ->execute();
        return $sql;
    }


    //este actualiza a las demas posiciones cuando se inserta  una posicion de algun hotel y choca con otra posicion.
    function actualizarTUFechaCambio($input){

        $sql = $this->connect()->prepare("update empleado_mes inner join usuario on cveUsuario = idUsuario set posicion = posicion + 1 
        where ".(($input["cveLocal"] == 1 || $input["cveLocal"] == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")." and 
        posicion >= :posicion and cveUsuario = idUsuario");
        
        $sql->bindParam(":posicion", $input['idP'], PDO::PARAM_INT);
        if($input["cveLocal"] > 1) {
            $sql->bindParam(":cveLocal", $input['cveLocal'], PDO::PARAM_INT);
        }
        $sql ->execute();
        return $sql;
    }


    function actualizarFechaCam($in){
        // $sql = $this->connect()->prepare("select count(*) from usuario inner join empleado_mes  on cveUsuario = idUsuario where ".(($in["cveLocal"] == 1 || $in["cveLocal"] == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")." and posicion = :posicion");
        // if($in["cveLocal"] >1) {
        //     $sql->bindParam(":cveLocal", $in['cveLocal'], PDO::PARAM_INT);
        // }       
        // $sql->bindParam(":posicion", $in["posicionAnt"], PDO::PARAM_INT);
        // $sql->execute();
        // $r = $sql->fetch(PDO::FETCH_NUM);
        // if ($r[0] == 2   ) {
            $fi = substr($in["fechaInicio"],0,10);
            $ff = substr($in["fechaFinal"],0,10);

            $sql = $this->connect()->prepare("update empleado_mes set fecha = :fecha, fechaInicio = :fechaInicio, fechaFinal = :fechaFinal,posicion = :posicion where cveUsuario = :id;");
            $sql->bindParam(":fecha", $in["fecha"], PDO::PARAM_INT);
            $sql->bindParam(":fechaInicio", $fi, PDO::PARAM_STR, 45);
            $sql->bindParam(":fechaFinal", $ff, PDO::PARAM_STR, 45);
            $sql->bindParam(":id", $in["idUsuario"], PDO::PARAM_INT);
            $sql->bindParam(":posicion", $in["posicion"], PDO::PARAM_INT);
            $sql->execute();
        // } 
        
        return $sql->execute();
 
    }

    function insertarFechaCam($input){
        
        $fi = substr($input["fechaInicio"],0,10);
        $ff = substr($input["fechaFinal"],0,10);

        $sql = $this->connect()->prepare("
        insert into empleado_mes (fecha,cveUsuario,posicion,fechaInicio,fechaFinal) 
        values(:fecha, :id, :posicion, :fechaInicio, :fechaFinal);");
        $sql->bindParam(":fecha", $input["fecha"], PDO::PARAM_INT);
        $sql->bindParam(":fechaInicio", $fi, PDO::PARAM_STR, 45);
        $sql->bindParam(":fechaFinal", $ff, PDO::PARAM_STR, 45);
        $sql->bindParam(":id", $input["idUsuario"], PDO::PARAM_INT);
        $sql->bindParam(":posicion", $input["posicion"], PDO::PARAM_INT);

        /*
        $sql = $this->connect()->prepare("select count(*) pos, idEmpleado_Mes id, (select count(*) from usuario inner join empleado_mes  on cveUsuario = idUsuario where ".(($input["cveLocal"] == 1 || $input["cveLocal"] == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")." ) total 
        from empleado_mes inner join usuario on cveUsuario = idUsuario where ".(($input["cveLocal"] == 1 || $input["cveLocal"] == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")." and posicion = -1;");
        
        if($input["cveLocal"] >1) {
            $sql->bindParam(":cveLocal", $input['cveLocal'], PDO::PARAM_INT);
        }  
        */
        return $sql ->execute();
        
        // return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function empleadosTotal($cveLocal){
        $com = "";
        if (intval($cveLocal) == 1 || intval($cveLocal) == 0) {
            $cveLocal = 1;
            $com = "cveLocal = :local or cveLocal = 0";
        } else {
            $com = "cveLocal = :local";
        }
        
        $sql = $this->connect()->prepare("select posicion,cveUsuario from empleado_mes inner join usuario on cveUsuario = idUsuario where ".$com);
        $sql->bindParam(":local", $cveLocal, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_NUM);
    }


    function actualizarPos($in){
       
        /* $sql = $this->connect()->prepare("select count(*) from usuario inner join empleado_mes  on cveUsuario = idUsuario where ".(($in["cveLocal"] == 1 || $in["cveLocal"] == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")." and posicion = :pos2");
        $sql->bindParam(":pos2", $in["posicion"], PDO::PARAM_INT);
        if($in["cveLocal"] >1) {
            $sql->bindParam(":cveLocal", $in['cveLocal'], PDO::PARAM_INT);
        }         
        $sql->execute();
        
        $r = $sql->fetch(PDO::FETCH_NUM);
         
        if ($r[0] > 0) { */
            $sql = $this->connect()->prepare("update empleado_mes inner join usuario  on cveUsuario = idUsuario set posicion = :pos where posicion = :pos2 and ".(($in["cveLocal"] == 1 || $in["cveLocal"] == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")."");
            $sql->bindParam(":pos2", $in["posicion"], PDO::PARAM_INT);
            $sql->bindParam(":pos", $in["posicionAnt"], PDO::PARAM_INT);
            if($in["cveLocal"] >1) {
                $sql->bindParam(":cveLocal", $in['cveLocal'], PDO::PARAM_INT);
            }              
            return $sql->execute();
        // }
        
        // return $sql;
    }

    function actualizarUFechaPos($input){

        $sql = $this->connect()->prepare("update empleado_mes set posicion = :idS
        where cveUsuario = :idP");
        $sql ->bindParam(":idP",$input["idP"],PDO::PARAM_INT);
        $sql ->bindParam(":idS",$input["idS"],PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

}