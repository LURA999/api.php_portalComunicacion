<?php
require '../Config/database.php';

class votarCompetencia extends database {
    
    function imprimirDatosExcel($comp){
        $sql = $this->connect()->prepare('
            SELECT `idVotacion`, u.`usuario` cveUsuario, CONCAT(u.nombres, " ",u.apellidoPaterno, " ", u.apellidoMaterno) nombre, 
            `cveCompetencia`, u2.idEquipo `cveUsuario_competidor`, nombreEquipo nombre_comp, 
            `fecha` FROM `votaciones` v 
            INNER JOIN usuario u ON u.idUsuario = cveUsuario 
            INNER JOIN votaciones_equipo u2 ON u2.idEquipo = cveUsuario_competidor 
            INNER JOIN votaciones_competencia ON idCompetencia = cveCompetencia 
            WHERE idCompetencia = :comp;
        ');
        $sql->bindParam(":comp", $comp, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function contadorDeVotos($comp){
        $sql = $this->connect()->prepare('
        SELECT 
        vt.cveCompentencia, 
        u2.idEquipo cveUsuario_competidor, 
        u2.nombreEquipo AS nombre_comp,
        (SELECT COUNT(v.cveUsuario) 
         FROM votaciones v 
         WHERE v.cveUsuario_competidor = u2.idEquipo) AS votos
        FROM 
            votaciones_usuario vt
        INNER JOIN 
            votaciones_equipo u2 ON u2.idEquipo = vt.cveUsuario
        WHERE 
            vt.cveCompentencia = :comp;
        ');
        $sql->bindParam(":comp", $comp, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    function imprimirDatosCompetencia($local,$nombre){
        if(strlen(trim($nombre)) > 0){
        $str = '';
        if($local >1) {
            $str = 'vc.cveLocal = :cveLocal AND';
        } else if($local == 1){
            $str = '(vc.cveLocal = 1 or vc.cveLocal = 0) AND';
        } else{
            $str = '';
        }
        
        $like = '%'.$nombre.'%';
        $sql = $this->connect()->prepare("
            SELECT `idCompetencia`, vc.`nombre`, `fecha_inicial`, `fecha_final`, `cveLocal`, activar, l.nombre hotel 
            FROM `votaciones_competencia` vc INNER JOIN `local` l ON `idLocal` = vc.cveLocal
            WHERE   ".$str."  vc.`nombre` LIKE :nombre 
            ORDER BY fecha_final DESC;
        ");
        
            
        $sql->bindParam(":nombre", $like, PDO::PARAM_STR);
        
        if($local >1) {
            $sql->bindParam(":cveLocal", $local, PDO::PARAM_INT);
        } 
        
        } else if (strlen(trim($nombre)) == 0 && $local == -1){
        $sql = $this->connect()->prepare('
            SELECT `idCompetencia`, vc.`nombre`, `fecha_inicial`, `fecha_final`, `cveLocal`, l.nombre hotel, activar  
            FROM `votaciones_competencia` vc INNER JOIN `local` l ON `idLocal` = vc.`cveLocal` 
            ORDER BY fecha_final DESC ;
        ');
            
        }else if (strlen(trim($nombre)) == 0) {
           
            $sql = $this->connect()->prepare("
            SELECT `idCompetencia`, vc.`nombre`, `fecha_inicial`, `fecha_final`, `cveLocal`, activar, l.nombre hotel 
            FROM `votaciones_competencia` vc
            INNER JOIN `local` l ON `idLocal` = vc.cveLocal
            WHERE ".(($local == 1 || $local == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")."
            ORDER BY fecha_final DESC
        ");
            if($local >1) {
                $sql->bindParam(":cveLocal", $local, PDO::PARAM_INT);
            } 
        }
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function imprimirUsuariosCompetencia($fkCompentencia, $local){
       /* $sql = $this->connect()->prepare('
            SELECT nombreEquipo, d.`departamento`, img, cveCompentencia 
            FROM `votaciones_usuario` vt 
            INNER JOIN `usuario` u  ON vt.`cveUsuario` = u.`idUsuario`  
            INNER JOIN `departamentos` d ON u.`cveDepartamento` = d.`idDepartamento`
            WHERE vt.`cveCompentencia` = :fkCompentencia;
        ');*/
        $sql = $this->connect()->prepare('
           SELECT idEquipo, nombreEquipo, imgEquipo, cveCompentencia 
            FROM `votaciones_usuario` vt 
            INNER JOIN votaciones_equipo ve ON ve.idEquipo = vt.`cveUsuario`
            WHERE vt.`cveCompentencia` = :fkCompentencia;
        ');
        
        $sql->bindParam(":fkCompentencia", $fkCompentencia, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function comprobarVotacion($cveUsuario, $local){
        $sql = $this->connect()->prepare('
            SELECT count(*) votar FROM votaciones 
            INNER JOIN votaciones_competencia ON idCompetencia = cveCompetencia
            WHERE cveUsuario = :cveUsuario AND '.(($local == 1 || $local == 0) ? '(cveLocal = 1 or cveLocal= 0)' : 'cveLocal = :cveLocal').' AND activar = 1;
        ');
        
        $sql->bindParam(":cveUsuario", $cveUsuario, PDO::PARAM_INT);
        if($local >1) {
            $sql->bindParam(":cveLocal", $local, PDO::PARAM_INT);
        }        
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function imprimirEventoActivada($local){
        $str = "";
        if($local >1) {
            $str = 'vc.cveLocal = :cveLocal';
        }else {
            $str = '(vc.cveLocal = 1 or vc.cveLocal = 0)';
        } 
        
        $sql = $this->connect()->prepare('
           SELECT idEquipo ,nombreEquipo, imgEquipo, cveCompentencia 
            FROM `votaciones_usuario` vt 
            INNER JOIN `votaciones_competencia` vc ON idCompetencia = cveCompentencia
            INNER JOIN votaciones_equipo ve ON ve.idEquipo = vt.`cveUsuario`
            WHERE '.$str.' AND activar = 1 AND NOW() BETWEEN fecha_inicial AND fecha_final;
        ');
        
        
        /*$sql = $this->connect()->prepare('
            SELECT `cveUsuario` id, CONCAT(u.`nombres`," ",u.`apellidoPaterno`," ",u.`apellidoMaterno`) nombre, d.`departamento`, img, cveCompentencia 
            FROM `votaciones_usuario` vt 
            INNER JOIN `votaciones_competencia` vc ON idCompetencia = cveCompentencia
            INNER JOIN `usuario` u  ON vt.`cveUsuario` = u.`idUsuario`  
            INNER JOIN `departamentos` d ON u.`cveDepartamento` = d.`idDepartamento`
            WHERE '.$str.' AND activar = 1 AND NOW() BETWEEN fecha_inicial AND fecha_final;
        ');*/
        
        
        if($local >1) {
            $sql->bindParam(":cveLocal", $local, PDO::PARAM_INT);
        } 
         $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function actualizarCompetencia($input){
        $fi = substr($input["fechaInicial"],0,10);
        $ff = substr($input["fechaFinal"],0,10);

        $sql = $this->connect()->prepare("
           UPDATE `votaciones_competencia` 
           SET `nombre`= :nombre,`fecha_inicial`= :fechaInicial,`fecha_final`= :fechaFinal,`cveLocal`= :cveLocal 
           WHERE  `idCompetencia` = :idCompetencia;
        ");
        
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR);
        $sql->bindParam(":fechaInicial", $fi, PDO::PARAM_STR);
        $sql->bindParam(":fechaFinal", $ff, PDO::PARAM_STR);
        $sql->bindParam(":cveLocal", $input["cveLocal"], PDO::PARAM_INT);
        $sql->bindParam(":idCompetencia", $input["idCompetencia"], PDO::PARAM_INT);

        return $sql->execute();
    }
    
    
    function actualizarActividad($input){
        if($input[2] == 1){
        $sql = $this->connect()->prepare("
           UPDATE `votaciones_competencia` 
           SET activar = 0
           WHERE activar = 1 AND ".(($input[0] == 1 || $input[0] == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")." ;
        ");
        if($input[0] >1) {
            $sql->bindParam(":cveLocal", $input[0], PDO::PARAM_INT);
        } 
        $sql->execute();
        }
        
        $sql = $this->connect()->prepare("
           UPDATE `votaciones_competencia` 
           SET activar = :activar
           WHERE  `idCompetencia` = :idCompetencia ;
        ");
        
        $sql->bindParam(":idCompetencia", $input[1], PDO::PARAM_INT);
        $sql->bindParam(":activar", $input[2], PDO::PARAM_INT);
        

        return $sql->execute();
    }
    
    function insertarVotacion($i){
        $sql = $this->connect()->prepare('
            INSERT INTO `votaciones`(`cveCompetencia`, `cveUsuario`, `cveUsuario_competidor`) 
            VALUES (:cveCompetencia , :cveUsuario, :cveUsuario2)
        ');
        $sql->bindParam(":cveCompetencia", $i[0], PDO::PARAM_INT);
        $sql->bindParam(":cveUsuario", $i[1], PDO::PARAM_INT);
        $sql->bindParam(":cveUsuario2", $i[2], PDO::PARAM_INT);
        return $sql->execute();
    }
    
    
    function insertarCompetencia($input){
        // echo var_dump($input);
        $fi = substr($input["fechaInicial"],0,10);
        $ff = substr($input["fechaFinal"],0,10);
        $connection1 = $this->connect();
        $sql = $connection1->prepare("
            INSERT INTO `votaciones_competencia`(`nombre`, `fecha_inicial`, `fecha_final`, `cveLocal`, `activar`) 
            VALUES (:nombre, :fechaInicial, :fechaFinal, :cveLocal, 1);
        ");
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR);
        $sql->bindParam(":fechaInicial", $fi, PDO::PARAM_STR);
        $sql->bindParam(":fechaFinal", $ff, PDO::PARAM_STR);
        $sql->bindParam(":cveLocal", $input["cveLocal"], PDO::PARAM_INT);
        if ($sql->execute()) {
            $lastInsertedId = $connection1->lastInsertId();
            return json_encode(array("status" => "200", "container"=> [ [ "ultimoId" => $lastInsertedId] ], "info" => "Es el id de la insercion" ) );    
        } else {
            return json_encode(array("status" => "404", "container"=> [ [ "error" => $sql->errorInfo()[2] ] ], "info" => "Error de la consulta" ));
        }
    }
    
    function insertarUsuariosCompetencia($input){
        $sql = $this->connect()->prepare('
            INSERT INTO `votaciones_usuario`(`cveCompentencia`, `cveUsuario`) 
            VALUES (:cveCompetencia , :cveUsuario)
        ');
        $sql->bindParam(":cveCompetencia", $input[0], PDO::PARAM_INT);
        $sql->bindParam(":cveUsuario", $input[1], PDO::PARAM_INT);

        return $sql->execute();
    }
    
    
    function eliminarUsuariosCompetencia($idU,$idC){
        $sql = $this->connect()->prepare('
            DELETE FROM `votaciones_usuario` 
            WHERE `cveCompentencia` = :cveCompentencia AND
            `cveUsuario` = :cveUsuario;
        ');
        $sql->bindParam(":cveUsuario", $idU, PDO::PARAM_INT);
        $sql->bindParam(":cveCompentencia", $idC, PDO::PARAM_INT);
        return  $sql ->execute();
    }
    
    function eliminarCompetencia($id){
        $sql = $this->connect()->prepare('
            DELETE FROM `votaciones_competencia` 
            WHERE `idCompetencia` = :cveCompentencia
        ');
        $sql->bindParam(":cveCompentencia", $id, PDO::PARAM_INT);
        return  $sql ->execute();
    }

    
    
  
    

}