<?php
class Usuario extends EntidadBase{
    private $id;
    private $password;
    private $tipo;
     
    public function __construct($adapter) {
        $table="Usuario";
        parent::__construct($table,$adapter);
        $this->tipo="Vendedor";
    }
     
    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
     
    public function getPassword() {
        return $this->password;
    }
 
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function getTipo(){
        return $this->tipo;
    }
    
    public function setTipo($tipo){
        $this->tipo = $tipo;
    }
 
    public function save(){
        $query="INSERT INTO Usuario (id,password,tipo)
                VALUES('".$this->id."',
                       '".$this->password."',
                       '".$this->tipo."');";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
 
}
?>