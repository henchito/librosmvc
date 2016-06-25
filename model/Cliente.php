<?php
class Cliente extends EntidadBase{
    private $nif;
    private $nombre;
    private $direccion;
    
     
    public function __construct($adapter) {
        $table="Cliente";
        parent::__construct($table,$adapter);
    }
     
    public function getNif() {
        return $this->nif;
    }
 
    public function setNif($nif) {
        $this->nif = $nif;
    }
    
    public function getNombre(){
        return $this->nombre;
    }
    
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    
    public function getDireccion(){
        return $this->direccion;
    }
    
    public function setDireccion($direccion){
        $this->direccion = $direccion;
    }
    
    public function save(){
        $query="INSERT INTO Cliente (nif, nombre, direccion)
                VALUES('".$this->nif."',
                       '".$this->nombre."',
                       '".$this->direccion."');";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
 
}
?>