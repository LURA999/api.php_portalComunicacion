<?php
require '../Config/database.php';

class alianzas extends database {

    function todoAlianza($id){
        $sql = $this->connect()->prepare("select nombre from alianzas ORDER BY CASE 
                                            WHEN cveLocal = 0 THEN 1
                                            WHEN cveLocal = :id THEN 2
                                            ELSE 3
                                            end;");
                                            
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_NUM);
    }


}