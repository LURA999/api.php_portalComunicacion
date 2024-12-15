<?php
require '../Config/database.php';

class araizaAprendeForm extends database {
    
    
    function imprimirDatosPrincipalesForm($id){
        $sql = $this->connect()->prepare("
            SELECT idFormulario, titulo, descripcion, encabezado, seccion_cant 
            FROM `formulario_ar_apr`
            WHERE idFormulario = :id 
            ORDER BY idFormulario ASC;
        ");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function imprimirFormularioPreguntas($id){
        $sql = $this->connect()->prepare("
            SELECT idPregunta, pregunta, respuestas, tipoPregunta, fk_formulario, respuestaCorrecta FROM `fpreguntas_ar_apr` 
            WHERE fk_formulario = :id 
            ORDER BY idPregunta ASC ;
        ");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //para el usuario que inicio
    function imprimirFormularioRespuestas($id, $f, $local){
        
        if($id > 0){
        $sql = $this->connect()->prepare("
            SELECT idPregunta, pregunta, respuestas, respuesta, tipoPregunta, pregunta, fk_formulario 
            FROM `frespuesta_ar_apr` fres 
            RIGHT JOIN fpreguntas_ar_apr fpr ON fpr.idPregunta = fres.fk_pregunta 
            WHERE 
            (fk_usuario = :id OR fk_usuario IS NULL)
            AND fk_formulario = :form 
            ORDER BY `idPregunta` ASC
        ");    
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        } else {
            $str = "";
            if(intval($local) == 0 || intval($local) == 1){
                $str = "AND (u.cveLocal = 1 OR u.cveLocal = 0)";
            }else{
                $str = "AND u.cveLocal = :local";
            }
            
        $sql = $this->connect()->prepare("
           SELECT concat(u.nombres,' ', u.apellidoPaterno,' ', u.apellidoMaterno) as nombre, 
           respuesta, tipoPregunta, fk_formulario
           FROM `frespuesta_ar_apr` fres 
           INNER JOIN fpreguntas_ar_apr fpr on fpr.idPregunta = fres.fk_pregunta 
           INNER JOIN usuario u ON u.idUsuario = fk_usuario 
           WHERE fk_formulario = :form ".$str." ORDER BY fk_usuario ASC, `idPregunta` ASC;
        ");    
            if($local == 0 || $local == 1){
                $str = "AND (u.cveLocal = 1 OR u.cveLocal = 0)";
            }else{
                $sql->bindParam(":local", $local, PDO::PARAM_INT);
            }
        }
        
        $sql->bindParam(":form", $f, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function imprimirFormularios(){
        $sql = $this->connect()->prepare("
            SELECT idFormulario, titulo, descripcion, encabezado, seccion_cant 
            FROM `formulario_ar_apr` 
            ORDER BY idFormulario ASC;
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    function editarPreguntaTexto($input){
        $sql = $this->connect()->prepare("
            UPDATE araiza_aprende 
            SET fk_idCategoria= :idCategoria,fk_idTema= :idTema,nombre= :nombre,img= :img,link= :link, contrasena = :contrasena 
            WHERE idArApr= :idArApr 
        ");
        $sql->bindParam(":idCategoria", $input["categoria"], PDO::PARAM_INT);
        $sql->bindParam(":idTema", $input["tema"], PDO::PARAM_INT);
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR);
        $sql->bindParam(":img", $input["img"], PDO::PARAM_STR);
        $sql->bindParam(":link", $input["link"], PDO::PARAM_STR);
        $sql->bindParam(":idArApr", $input["idArApr"], PDO::PARAM_STR);
        $sql->bindParam(":contrasena", $input["contrasena"], PDO::PARAM_STR);

        return $sql->execute();
    }
    
    function editarRespuestaTexto($input){
        $sql = $this->connect()->prepare("
            UPDATE araiza_aprende 
            SET fk_idCategoria= :idCategoria,fk_idTema= :idTema,nombre= :nombre,img= :img,link= :link, contrasena = :contrasena 
            WHERE idArApr= :idArApr 
        ");
        $sql->bindParam(":idCategoria", $input["categoria"], PDO::PARAM_INT);
        $sql->bindParam(":idTema", $input["tema"], PDO::PARAM_INT);
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR);
        $sql->bindParam(":img", $input["img"], PDO::PARAM_STR);
        $sql->bindParam(":link", $input["link"], PDO::PARAM_STR);
        $sql->bindParam(":idArApr", $input["idArApr"], PDO::PARAM_STR);
        $sql->bindParam(":contrasena", $input["contrasena"], PDO::PARAM_STR);

        return $sql->execute();
    }
    
    function editarRespuesta($input){
        $sql = $this->connect()->prepare("
            UPDATE `frespuesta_ar_apr` 
            SET `respuesta`= :respuesta
            WHERE `fk_pregunta` = :fk_pregunta AND `fk_usuario` = :fk_usuario; 
        ");
        $sql->bindParam(":fk_pregunta", $input[1], PDO::PARAM_INT);
        $sql->bindParam(":fk_usuario", $input[2], PDO::PARAM_INT);
        $sql->bindParam(":respuesta", $input[0], PDO::PARAM_STR);

        return $sql->execute();
    }
    
    function editarEncabezado($input){
        $sql = $this->connect()->prepare("
            UPDATE araiza_aprende 
            SET fk_idCategoria= :idCategoria,fk_idTema= :idTema,nombre= :nombre,img= :img,link= :link, contrasena = :contrasena 
            WHERE idArApr= :idArApr 
        ");
        $sql->bindParam(":idCategoria", $input["categoria"], PDO::PARAM_INT);
        $sql->bindParam(":idTema", $input["tema"], PDO::PARAM_INT);
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR);
        $sql->bindParam(":img", $input["img"], PDO::PARAM_STR);
        $sql->bindParam(":link", $input["link"], PDO::PARAM_STR);
        $sql->bindParam(":idArApr", $input["idArApr"], PDO::PARAM_STR);
        $sql->bindParam(":contrasena", $input["contrasena"], PDO::PARAM_STR);

        return $sql->execute();
    }
    
    function editarDescripcion($input){
        $sql = $this->connect()->prepare("
            UPDATE araiza_aprende 
            SET fk_idCategoria= :idCategoria,fk_idTema= :idTema,nombre= :nombre,img= :img,link= :link, contrasena = :contrasena 
            WHERE idArApr= :idArApr 
        ");
        $sql->bindParam(":idCategoria", $input["categoria"], PDO::PARAM_INT);
        $sql->bindParam(":idTema", $input["tema"], PDO::PARAM_INT);
        $sql->bindParam(":nombre", $input["nombre"], PDO::PARAM_STR);
        $sql->bindParam(":img", $input["img"], PDO::PARAM_STR);
        $sql->bindParam(":link", $input["link"], PDO::PARAM_STR);
        $sql->bindParam(":idArApr", $input["idArApr"], PDO::PARAM_STR);
        $sql->bindParam(":contrasena", $input["contrasena"], PDO::PARAM_STR);

        return $sql->execute();
    }
    
    
    
    function insertarRespuesta($input){
        $sql = $this->connect()->prepare("
            INSERT INTO `frespuesta_ar_apr`(`respuesta`, `fk_pregunta`, `fk_usuario`) 
            VALUES (:res, :idPregunta ,:idUsuario )
        ");
        $sql->bindParam(":res", $input[0], PDO::PARAM_STR);
        $sql->bindParam(":idPregunta", $input[1], PDO::PARAM_INT);
        $sql->bindParam(":idUsuario", $input[2], PDO::PARAM_INT);

        return $sql->execute();
    }
    
    
    ////////////////////////
    function eliminarCategoria($id){
        $sql = $this->connect()->prepare("delete from categoria_ar_apr where idCatArApr = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return  $sql ->execute();
    }
    

    
    
  
    

}