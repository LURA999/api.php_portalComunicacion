<?php
class database
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;


    public function __construct(){
        $this->host='mysql';
        $this->port='3306';
        $this->username='root';
        $this->password='root_password';
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
                "mysql:host=".$this->host.";
		port=".$this->port.";
		dbname=".$this->database.";",
                $this->username,
                $this->password,
                $options);

            return $con;
        } catch (PDOException $e) {
            exit($e);
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
