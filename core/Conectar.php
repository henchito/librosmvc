<?php
class Conectar{
    private $driver;
    private $host, $user, $pass, $database, $charset;
    private $con;
   
    public function __construct() {
        $db_cfg = require_once 'config/database.php';
        $this->driver=$db_cfg["driver"];
        $this->host=$db_cfg["host"];
        $this->user=$db_cfg["user"];
        $this->pass=$db_cfg["pass"];
        $this->database=$db_cfg["database"];
        $this->charset=$db_cfg["charset"];
    }
     
    public function conexion(){
         
        if(($this->driver=="mysql" || $this->driver==null) and $con==null ){   //el and $con==null es nuevo!!!!!
            $con=new mysqli($this->host, $this->user, $this->pass, $this->database);
            $con->set_charset( (string) $this->charset);
        }
         
        return $con;
    }
    
    public function desconexion(){
        $con->close();
    }
     
    public function startFluent(){
        require_once "FluentPDO/FluentPDO.php";
         
        if($this->driver=="mysql" || $this->driver==null){
            $pdo = new PDO($this->driver.":dbname=".$this->database, $this->user, $this->pass);
            $fpdo = new FluentPDO($pdo);
        }
         
        return $fpdo;
    }
}
?>
