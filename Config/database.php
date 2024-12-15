<?php
class database
{
    private $host;
    private $username;
    private $password;
    private $database;


    public function __construct(){
        $this->host='localhost';
        $this->username='root';
        $this->password='';
        $this->database='comunica_portalComunicacion';
   }

    public function connect()
    {
        $options = [
            PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES, false
        ];

        try {
            $con = new PDO(
                "mysql: host=".$this->host.";port=3306;dbname=".$this->database.";",
                $this->username,
                $this->password,
                $options);

            return $con;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }	
    }


    function getParams($input)
    {
        $filterParams = [];
        foreach ($input as $param => $value) {
            $filterParams[] = "$param=:$param";
        }
        return implode(", ", $filterParams);
    }
}
