<?php

require '../../Config/database.php';
define('ABSPATH', dirname(__FILE__,3).'/');
error_reporting(0);

class userLogin extends database{

    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function getLogin($usuario, $contrasena){
    $usuario = base64_decode($usuario);
    $contrasena = base64_decode($contrasena);
    $query = $this->getUser($usuario);
    
    $arr = $query -> fetchAll(PDO::FETCH_ASSOC);
    $arrUser;
    $comprobar = false;


  
    for($i = 0; $i<count($arr);$i++){
        if(password_verify($contrasena,$arr[$i]["contrasena"])){
            $arrUser = $arr[$i];
            $comprobar = true;
        }
    }
    
    
    if($comprobar == true)
    {
        while($arrUser)
        {   
            if(password_verify($contrasena,$arrUser["contrasena"]))
            {
                $sql = $this->connect()->prepare('INSERT INTO visitas (usuario) VALUES(:usuario);');
                $sql->bindparam(':usuario', $arrUser["idUsuario"], PDO::PARAM_INT);
                $sql->execute(); 

                return array("error" => false, 
                "nombres" => $arrUser["nombres"], 
                "correo" => $arrUser["correo"], 
                "id" => $arrUser["idUsuario"], 
                "cveRol" => $arrUser ["cveRol"], 
                "cveLocal" => $arrUser["cveLocal"]);
            }else{
                $err = array('error' => true);
                return $err;
            }
        }
    }else{
        $err = array('error' => true);
        return $err;
    }
     
    }

    function getUser($usuario){
        $sql = $this->connect()->prepare('SELECT nombres,correo,contrasena,idUsuario,cveRol,cveLocal 
        from usuario  u where u.usuario = :usuario;');
        $sql->bindparam(':usuario', $usuario, PDO::PARAM_INT);
        $sql->execute();     
        
        return $sql;
    }   
       
    function searchUser($usuario, $hotel,$fechaInicial,$fechaFinal,$var,$tipoVista){
        $like = '%'.$usuario.'%';

        switch (intval($var)) {
            //El case 1, es para la lista de usuarios normales
            case 1:
               // $com= " and DATE(fecha) = DATE(now())";
                $extensionIFinal = "";
                if(!is_null($fechaFinal) && $fechaFinal !="" && $fechaFinal !="null" ){
                    $com = "";
                    $extensionIFinal = " and DATE(fecha) < :fechaFinal";
                }

                $extensionIInicial = "";
                if (!is_null($fechaInicial) && $fechaInicial  !="" && $fechaInicial !="null") {
                    $com = "";
                    $extensionIInicial = " and DATE(fecha) = :fechaInicial";
                }  

                $extensionCom = "";
                if((!is_null($fechaFinal) && $fechaFinal !="" && $fechaFinal !="null") && (!is_null($fechaInicial) && $fechaInicial  !="" && $fechaInicial !="null")){
                    $extensionIFinal = "";
                    $extensionIInicial = "";
                    $extensionCom = " and DATE(fecha) >= :fechaInicial and DATE(fecha) <= :fechaFinal";
                }

                $extensionVista = "";
                if(intVal($tipoVista) == 0) {
                    $extensionVista = " having fecha = 0"; 
                }

                if(intVal($tipoVista) == 1) {
                    $extensionVista = " having fecha >= 1"; 
                }
                
                

                 if($hotel == -1){
                    $sql = $this->connect()->prepare("SELECT idUsuario, u.usuario, apellidoPaterno, apellidoMaterno, nombres, correo, cveRol, cveLocal, l.nombre AS local, fechaNacimiento, img, fechaIngreso, dep.departamento, contrato, COUNT(v.fecha) AS fecha
                    FROM usuario u  INNER JOIN local l ON cveLocal = idLocal INNER JOIN departamentos dep on idDepartamento = cveDepartamento 
                    LEFT JOIN visitas v ON v.usuario = u.idUsuario ".$com." ".$extensionIInicial." ".$extensionIFinal." ".$extensionCom."
                    WHERE (LOWER(concat(nombres, ' ',apellidoPaterno,' ',apellidoMaterno)) like LOWER(:bus) or  u.usuario like :bus) 
                    group by  SUBSTRING_INDEX(img, '.', 1) ".$extensionVista."  order by u.usuario desc;");
                    $sql->bindparam(':bus', $like, PDO::PARAM_STR,50);

                    if(!is_null($fechaFinal) && $fechaFinal !="" && $fechaFinal !="null" ){
                        $ff = substr($fechaFinal,0,10);
                        $sql->bindParam(":fechaFinal", $ff, PDO::PARAM_STR);
                    }

                    if (!is_null($fechaInicial) && $fechaInicial  !="" && $fechaInicial !="null") {
                        $fi = substr($fechaInicial,0,10);
                        $sql->bindParam(":fechaInicial", $fi, PDO::PARAM_STR);
                    }   

                } else {
                    $sql = $this->connect()->prepare("SELECT idUsuario, u.usuario, apellidoPaterno, apellidoMaterno, nombres, correo, cveRol, cveLocal, l.nombre AS local, fechaNacimiento, img, fechaIngreso, dep.departamento, contrato, COUNT(v.fecha) AS fecha
                    from usuario u 
                    INNER JOIN local l on cveLocal = idLocal INNER JOIN departamentos dep on idDepartamento = cveDepartamento
                    LEFT JOIN visitas v  on v.usuario = idUsuario ".$com." ".$extensionIInicial." ".$extensionIFinal." ".$extensionCom."
                    WHERE (LOWER(concat(nombres, ' ',apellidoPaterno,' ',apellidoMaterno)) like LOWER(:bus) or  u.usuario like :bus)  and ".(($hotel == 1 || $hotel == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")."  
                    GROUP BY  SUBSTRING_INDEX(img, '.', 1) ".$extensionVista."  order by usuario desc ;");

                    $sql->bindparam(':bus', $like, PDO::PARAM_STR,50);
                    if($hotel >1) {
                        $sql->bindParam(":cveLocal", $hotel, PDO::PARAM_INT);
                    }  

                    if(!is_null($fechaFinal) && $fechaFinal !="" && $fechaFinal !="null" ){
                        $ff = substr($fechaFinal,0,10);
                        $sql->bindParam(":fechaFinal", $ff, PDO::PARAM_STR);
                    }

                    if (!is_null($fechaInicial) && $fechaInicial  !="" && $fechaInicial !="null") {
                        $fi = substr($fechaInicial,0,10);
                        $sql->bindParam(":fechaInicial", $fi, PDO::PARAM_STR);
                    }   
                }
                break;
                //el case 2, es para usuarios que son empleados del mes
            case 2:
                if ($hotel == -1 ) {
                    $sql = $this->connect()->prepare("SELECT idUsuario,usuario,nombres,apellidoPaterno,apellidoMaterno, l.nombre local, cveLocal, fecha, fechaInicio, fechaFinal, contrato , posicion
                    from empleado_mes right join usuario on cveUsuario = idUsuario inner join local l on cveLocal = idLocal 
                    having LOWER(concat(nombres, ' ',apellidoPaterno,' ',apellidoMaterno)) like LOWER(:bus) or usuario like :bus order by fecha desc;");
                    $sql->bindParam(":bus", $like, PDO::PARAM_STR,50);
                } else {
                    $sql = $this->connect()->prepare("SELECT idUsuario,usuario,nombres,apellidoPaterno,apellidoMaterno, l.nombre local, cveLocal, fecha, fechaInicio, fechaFinal, contrato , posicion
                    from empleado_mes right join usuario u on cveUsuario = idUsuario inner join local l  on u.cveLocal = idLocal 
                    where  ".(($hotel == 1 || $hotel == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")."
                    having LOWER(concat(nombres, ' ',apellidoPaterno,' ',apellidoMaterno)) like LOWER(:bus) or usuario like :bus order by fecha desc;");

                    if($hotel >1) {
                        $sql->bindParam(":cveLocal", $hotel, PDO::PARAM_INT);
                    } 
                    $sql->bindParam(":bus", $like, PDO::PARAM_STR,50);
                }      
                break;
        }
        
        $sql->execute();     
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllUsers($var){
        switch (intval($var)) {
            case 1: 
                $sql = $this->connect()->prepare('SELECT idUsuario, u.usuario, apellidoPaterno, apellidoMaterno, nombres, correo, idUsuario, cveRol,cveLocal, l.nombre local, fechaNacimiento, img, fechaIngreso, dep.departamento, contrato, c.nombre contratoNombre, count(fecha) fecha
                from usuario u INNER JOIN local l on cveLocal = idLocal INNER JOIN contrato c on contrato = idContrato LEFT JOIN visitas v  on v.usuario = idUsuario INNER JOIN departamentos dep on idDepartamento = cveDepartamento group by  SUBSTRING_INDEX(img, ".", 1) desc');
            break;
            case 2:
                $sql = $this->connect()->prepare('SELECT idUsuario,usuario,nombres,apellidoPaterno,apellidoMaterno, fecha, l.nombre local, cveLocal, contrato, c.nombre contratoNombre, posicion, fechaInicio, fechaFinal from empleado_mes right join usuario u on cveUsuario = idUsuario inner join local l on cveLocal = idLocal inner join contrato c on contrato = idContrato order by fecha desc');
            break;
        }
        $sql->execute();     
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllUsersBirth($hotel){
        $sql = $this->connect()->prepare("SELECT fechaNacimiento, dep.departamento, nombres, apellidoPaterno, apellidoMaterno, img, cveLocal
        FROM usuario INNER JOIN departamentos dep on idDepartamento = cveDepartamento where MONTH(CURRENT_DATE()) = MONTH(fechaNacimiento) and ".(($hotel == 1 || $hotel == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "(cveLocal = :cveLocal)")." order by DAY(fechaNacimiento) asc");
        if( $hotel  >1) {
            $sql->bindParam(":cveLocal",  $hotel, PDO::PARAM_INT);
        }  
        $sql->execute();     
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllUsersAniv($aniv){

        $sql = $this->connect()->prepare("SELECT usuario,nombres, apellidoPaterno, dep.departamento, img, fechaIngreso , 
        YEAR(CURRENT_DATE()) - YEAR(fechaIngreso) AS anos_transcurridos, fechaIngreso   
        FROM usuario INNER JOIN departamentos dep on idDepartamento = cveDepartamento where ".(($aniv == 1 || $aniv == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")." and  (DATE_ADD(fechaIngreso, INTERVAL 1 YEAR) <= CURRENT_DATE() OR fechaIngreso >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 YEAR)) 
        AND MONTH(fechaIngreso) = MONTH(CURRENT_DATE()) having anos_transcurridos > 0 order by anos_transcurridos desc; ");
        if( $aniv  >1) {
            $sql->bindParam(":cveLocal",  $aniv, PDO::PARAM_INT);
        }   
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllUsersMesi($mesi){
        $sql = $this->connect()->prepare("SELECT cveUsuario, nombres,  mesString(fecha) mes, dep.departamento, contrato, c.nombre contratoNombre, img, apellidoPaterno, apellidoMaterno, posicion, cveLocal, fechaInicio, fechaFinal 
        FROM empleado_mes INNER JOIN usuario on cveUsuario = idUsuario INNER JOIN contrato c on contrato = idContrato INNER JOIN departamentos dep on idDepartamento = cveDepartamento
        where fechaInicio <= DATE(now()) and fechaFinal >= DATE(now()) and ".(($mesi == 1 || $mesi == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "cveLocal = :cveLocal")." order by fecha,contrato, posicion asc;");
        if( $mesi  >1) {
            $sql->bindParam(":cveLocal",  $mesi, PDO::PARAM_INT);
        } 
        $sql->execute();     
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function DELETEUser($usuario){
        $sql = $this->connect()->prepare('DELETE from usuario where idUsuario = :usuario');
        $sql->bindparam(':usuario', $usuario, PDO::PARAM_INT);
        $sql->execute();     
        return $sql;
    }

    function updateUser($input,$modalidad){
        $consulta = "";
        $param = "usuario = :usuario, nombres = :nombres, apellidoPaterno = :apellidoPaterno, 
        apellidoMaterno = :apellidoMaterno, correo = :correo, cveRol = :cveRol, cveLocal = :cveLocal, 
        fechaNacimiento = :fechaNacimiento, fechaIngreso = :fechaIngreso, cveDepartamento = :departamento , contrato = :contrato";

        if ($input['contrasena'] !== '') {
            $param = $param . ', contrasena = :contrasena';
            
        } 

        if($input['img'] !== ''){
            $param = $param . ', img = lower(:img)';
        }
        
        $fn = substr($input["fechaNacimiento"],0,10);
        $fi = substr($input["fechaIngreso"],0,10);

        $consulta = 'UPDATE usuario set '.$param.'
        where idUsuario = :idUsuario';
        
         
        $sql = $this->connect()->prepare($consulta);
        $sql->bindparam(':usuario', $input['usuario'], PDO::PARAM_INT);
        $sql->bindparam(':idUsuario', $input['idUsuario'], PDO::PARAM_INT);
        $sql->bindparam(':nombres', $input['nombres'], PDO::PARAM_STR,50);
        $sql->bindparam(':apellidoPaterno', $input['apellidoPaterno'], PDO::PARAM_STR,30);
        $sql->bindparam(':apellidoMaterno', $input['apellidoMaterno'], PDO::PARAM_STR,30);
        $sql->bindparam(':correo', $input['correo'], PDO::PARAM_STR,55);
        $sql->bindparam(':cveRol', $input['cveRol'], PDO::PARAM_INT);
        $sql->bindparam(':cveLocal', $input['cveLocal'], PDO::PARAM_INT);
        $sql->bindparam(':fechaNacimiento', $fn, PDO::PARAM_STR,10);
        $sql->bindparam(':fechaIngreso', $fi, PDO::PARAM_STR,10);
        $sql->bindparam(':departamento', $input['departamento'], PDO::PARAM_STR,30);
        $sql->bindparam(':contrato', $input['contrato'], PDO::PARAM_INT);
        if(isset($input['img']) && $input['img'] !== ''){  
            $sql->bindparam(':img', $input['img'], PDO::PARAM_STR);
            if ($modalidad == 'false' || $modalidad == false) {
                $input['img'] = $input['usuario']."_".strtolower(explode("_",$input['img'])[1]);
                rename("../../API/imgVideo/fotos/".$input['imgn'], "../../API/imgVideo/fotos/".strtolower($input['img']));
            }else{
                unlink("../../API/imgVideo/fotos/".$input['imgn']);
            }
        }

        if ($input['contrasena'] !== '') {
            $contrasena = password_hash($input["contrasena"], PASSWORD_DEFAULT);
            $sql->bindparam(':contrasena', $contrasena, PDO::PARAM_STR);
        }

        $sql = $sql->execute();    
        
       
        return $sql;
    }

    function updateLoginPass($input){
        $sql = $this->connect()->prepare('UPDATE usuario u set u.contrasena = :contrasena where u.idUsuario = :cveUsuario');
        $sql->bindparam(':contrasena', $contrasena , PDO::PARAM_STR);
        $sql->bindparam(':cveUsuario', $input['id'], PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }


    function updatedLoginLevel($input){
        $sql = $this->connect()->prepare('UPDATE usuario u set u.nivel = :nivel where u.cve_usuario = :id');
        $sql->bindparam(':nivel', $input['nivel'], PDO::PARAM_STR,40);
        $sql->bindparam(':id', $input['id'], PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }
    
    function todosDepartamentos(){
        $sql = $this->connect()->prepare("select idDepartamento, departamento from departamentos order by departamento asc;");
        $sql->execute();     
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function createUser($input){
        
        $fn = substr($input["fechaNacimiento"],0,10);
        $fi = substr($input["fechaIngreso"],0,10);

        $contrasena = password_hash($input["contrasena"], PASSWORD_DEFAULT);
        $sql = $this->connect()->prepare('
        INSERT INTO usuario (usuario,nombres,apellidoPaterno,apellidoMaterno,correo,contrasena,cveRol,cveLocal,fechaNacimiento,img,fechaIngreso, cveDepartamento, contrato) 
        values( :usuario, :nombres, :apellidoPaterno, :apellidoMaterno, :correo, :contrasena, :cveRol, :cveLocal, :fechaNacimiento, lower(:img), :fechaIngreso, :departamento, :contrato );');
        $sql->bindparam(':usuario', $input['usuario'], PDO::PARAM_INT);
        $sql->bindparam(':nombres', $input['nombres'], PDO::PARAM_STR,50);
        $sql->bindparam(':apellidoPaterno', $input['apellidoPaterno'], PDO::PARAM_STR,30);
        $sql->bindparam(':apellidoMaterno', $input['apellidoMaterno'], PDO::PARAM_STR,30);
        $sql->bindparam(':correo', $input['correo'], PDO::PARAM_STR,55);
        $sql->bindparam(':cveRol', $input['cveRol'], PDO::PARAM_INT);
        $sql->bindparam(':cveLocal', $input['cveLocal'], PDO::PARAM_INT);
        $sql->bindparam(':contrasena', $contrasena, PDO::PARAM_STR);
        $sql->bindparam(':fechaNacimiento', $fn, PDO::PARAM_STR,10);
        $sql->bindparam(':img', $input['img'], PDO::PARAM_STR);
        $sql->bindparam(':fechaIngreso', $fi, PDO::PARAM_STR,10);
        $sql->bindparam(':departamento', $input['departamento'], PDO::PARAM_STR,30);
        $sql->bindparam(':contrato', $input['contrato'], PDO::PARAM_INT);

        $sql->execute();
        return $sql;
    }

    function insertVisita($in){
        $sql = $this->connect()->prepare('INSERT INTO visitas (usuario) VALUES(:usuario);');
        $sql->bindparam(':usuario', $in["resg"], PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }
    
    function insertDepartamento($input){
        $sql = $this->connect()->prepare('INSERT INTO departamentos (departamento) VALUES(:departamento);');
        $sql->bindparam(':departamento', $input["departamento"], PDO::PARAM_STR);
        return $sql->execute();
    }
    
    function updateDepartamento($input){
        $sql = $this->connect()->prepare('UPDATE departamentos set departamento = :departamento where idDepartamento = :idDepartamento;');
        $sql->bindparam(':idDepartamento', $input["idDepartamento"], PDO::PARAM_INT);
        $sql->bindparam(':departamento', $input["departamento"], PDO::PARAM_STR);
        return $sql->execute();
    }
    
    function deleteDepartamento($input){
        $sql = $this->connect()->prepare('DELETE FROM departamentos where idDepartamento = :idDepartamento');
        $sql->bindparam(':idDepartamento', $input, PDO::PARAM_INT);
        return $sql->execute();
    }
    
    function buscarRepetidoUpdate($user,$cve,$id){
        $sql = $this->connect()->prepare('select count(*) total from usuario where usuario = :usuario and '.(($cve == 1 || $cve == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "(cveLocal = :cveLocal)").' and idUsuario != :id;');
        $sql->bindparam(':usuario', $user, PDO::PARAM_INT);
        if( $cve  >1) {
            $sql->bindParam(":cveLocal",  $cve, PDO::PARAM_INT);
        }
        $sql->bindparam(':id', $id, PDO::PARAM_INT);

        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);

    }
    
    function buscarRepetidoInsert($user, $cve){
        $sql = $this->connect()->prepare('select count(*) total from usuario where '.(($cve == 1 || $cve == 0) ? "(cveLocal = 1 or cveLocal= 0)" : "(cveLocal = :cveLocal)").' and usuario = :usuario ;');
        $sql->bindparam(':usuario', $user, PDO::PARAM_INT);
        if( $cve  >1) {
            $sql->bindParam(":cveLocal",  $cve, PDO::PARAM_INT);
        }        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);

    }

    

   

}

