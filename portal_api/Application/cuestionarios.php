<?php
require '../Config/database.php';

class cuestionarios extends database {
    function todoCuestionarios(){
        $sql = $this->connect()->prepare("
        SELECT 
         formulario_ar_apr.idFormulario,
         formulario_ar_apr.titulo, 
         local.idLocal,
         local.nombre as hotel 
        FROM `formulario_ar_apr` 
        INNER JOIN local ON formulario_ar_apr.id_hotel = local.idLocal order by idFormulario asc");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function eliminarCuestionario($id) {
        $sql = $this->connect()->prepare("DELETE FROM `formulario_ar_apr` WHERE idFormulario=:id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }

    function eliminarPregunta($id) {
        $sql = $this->connect()->prepare("DELETE FROM `fpreguntas_ar_apr` WHERE idPregunta=:id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }

    function eliminarRespuesta($id) {
        $sql = $this->connect()->prepare("DELETE FROM `frespuesta_ar_apr` WHERE fk_pregunta=:id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }

    function traerCuestionarioTitulo($input){
        $sql = $this->connect()->prepare("SELECT * FROM `formulario_ar_apr` WHERE idFormulario=:id");
        $sql->bindParam(":id", $input, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function traerCuestionarioPreguntas($input){
        $sql = $this->connect()->prepare("SELECT * FROM `fpreguntas_ar_apr` WHERE fk_formulario=:id order by idPregunta asc");
        $sql->bindParam(":id", $input, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function enviarCuestionario($input){ 
      $sql = $this->connect()->prepare("
        UPDATE `fpreguntas_ar_apr` 
        SET 
        `pregunta`= :pregunta,
        `respuestaCorrecta`= :respuestaCorrecta,
        `respuestas`= :respuestas,
        `cantidadRespuestas`= :cantidadRespuestas,
        `tipoPregunta`= :tipoQuestion
        WHERE `fk_formulario`= :fk_formulario AND idPregunta = :idPregunta
        ");

        $sql->bindParam(":pregunta", $input["pregunta"], PDO::PARAM_STR);
        $sql->bindParam(":respuestaCorrecta", $input["respuestaCorrecta"], PDO::PARAM_STR);
        $sql->bindParam(":respuestas", $input["respuesta"], PDO::PARAM_STR);
        $sql->bindParam(":idPregunta", $input["idPregunta"], PDO::PARAM_STR);
        $sql->bindParam(":cantidadRespuestas", $input["cantidadRespuestas"], PDO::PARAM_INT);
        $sql->bindParam(":fk_formulario", $input["idCuestionario"], PDO::PARAM_INT);
        $sql->bindParam(":tipoQuestion", $input["tipoQuestion"], PDO::PARAM_INT);
        return $sql->execute();
    }
    
    function actualizarModDesc($input) {
        $sql = $this->connect()->prepare("
            UPDATE `formulario_ar_apr` 
            SET
            `titulo` = :titulo,
            `descripcion` = :descripcion
            WHERE 
            `idFormulario` = :idFormulario;
        ");
        $sql->bindParam(":titulo", $input["titulo"], PDO::PARAM_STR);
        $sql->bindParam(":descripcion", $input["descripcion"], PDO::PARAM_STR);
        $sql->bindParam(":idFormulario", $input["idFormulario"], PDO::PARAM_INT);
        return $sql->execute();
    }

    function insertarCuestionario($input) { 
	$sql = $this->connect()->prepare("
            INSERT INTO `fpreguntas_ar_apr` 
            (`pregunta`, `respuestaCorrecta`, `respuestas`, `cantidadRespuestas`, `tipoPregunta`, `fk_formulario`)
            VALUES 
            (:pregunta, :respuestaCorrecta, :respuestas, :cantidadRespuestas, :tipoQuestion, :fk_formulario)
        ");

        $sql->bindParam(":pregunta", $input["pregunta"], PDO::PARAM_STR);
        $sql->bindParam(":respuestaCorrecta", $input["respuestaCorrecta"], PDO::PARAM_STR);
        $sql->bindParam(":respuestas", $input["respuesta"], PDO::PARAM_STR);
        $sql->bindParam(":cantidadRespuestas", $input["cantidadRespuestas"], PDO::PARAM_INT);
        $sql->bindParam(":fk_formulario", $input["idCuestionario"], PDO::PARAM_INT);
        $sql->bindParam(":tipoQuestion", $input["tipoQuestion"], PDO::PARAM_INT);
        return $sql->execute();
    }

    function insertarModDesc($input) {
    $connection1 = $this->connect();

	$seccion_cant = '["10"]';
	$encabezado = '[]';
	$id_hotel = 1;

	$sql = $connection1->prepare("
		INSERT INTO `formulario_ar_apr` 
        	(`titulo`, `descripcion`, `seccion_cant`, `id_hotel`, `encabezado`)
        	VALUES (:titulo, :descripcion, :seccion_cant, :id_hotel, :encabezado);
	");
        $sql->bindParam(":descripcion", $input["descripcion"], PDO::PARAM_STR);
        $sql->bindParam(":titulo", $input["titulo"], PDO::PARAM_STR);
        $sql->bindParam(":seccion_cant", $seccion_cant, PDO::PARAM_STR);
        $sql->bindParam(":id_hotel", $id_hotel, PDO::PARAM_STR);
        $sql->bindParam(":encabezado", $encabezado, PDO::PARAM_STR);

	if ($sql->execute()) {
            $lastInsertedId = $connection1->lastInsertId();
            return json_encode(array("status" => "200", "container"=> [ [ "ultimoId" => $lastInsertedId] ], "info" => "Es el id de la insercion" ) );    
        } else {
            return json_encode(array("status" => "404", "container"=> [ [ "error" => $sql->errorInfo()[2] ] ], "info" => "Error de la consulta" ));
        }

    }
}








