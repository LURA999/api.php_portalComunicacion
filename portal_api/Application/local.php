<?php

require '../Config/database.php';

class local extends database{

    function todoLocal ($opc){
        switch ($opc) {
            case -1:
                $sql = $this->connect()->prepare('select idLocal,nombre from local;');
            break;
            case 1:
                $sql = $this->connect()->prepare('select  idLocal,l.nombre ,IFNULL((select count(cveLocal) from imgVideo where cveLocal = idLocal and cveSeccion = 1 group by cveLocal),0) as cantidad from local l group by idLocal');
            break;
            case 2:
                $sql = $this->connect()->prepare('select idLocal,l.nombre ,IFNULL((select count(cveLocal) from noticia where cveLocal = idLocal and fechaFinal >= DATE_FORMAT(curdate(), "%Y-%m-%d") and fechaInicial <= fechaFinal group by cveLocal),0) as cantidad from local l group by idLocal');
            break;
            
        }
        
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}


