<?php
class Stock extends EntidadBase{
    private $id;
    private $anyo;
    private $recibidos;
    private $vendidos;
    private $devueltos;
     
    public function __construct($adapter) {
        $table="Stock";
        parent::__construct($table,$adapter);
    }
     
    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
     
    public function getAnyo() {
        return $this->anyo;
    }
 
    public function setAnyo($anyo) {
        $this->anyo = $anyo;
    }
 
    public function getRecibidos() {
        return $this->recibidos;
    }
 
    public function setRecibidos($recibidos) {
        $this->recibidos = $recibidos;
    }
 
    public function getVendidos() {
        return $this->vendidos;
    }
 
    public function setVendidos($vendidos) {
        $this->vendidos = $vendidos;
    }
    
    public function getDevueltos() {
        return $this->devueltos;
    }
 
    public function setDevueltos($devueltos) {
        $this->devueltos = $devueltos;
    }
 
    public function save(){
        $query="INSERT INTO Stock (id,anyo,recibidos,vendidos,devueltos)
                VALUES('".$this->id."',
                       '".$this->anyo."',
                       '".$this->recibidos."',
                       '".$this->vendidos."',
                       '".$this->devueltos."');";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
    
    public function update(){
        $query="UPDATE Stock SET
                    recibidos='".$this->recibidos."',
                    vendidos='".$this->vendidos."',
                    devueltos='".$this->devueltos."'
                WHERE id='".$this->id."' and anyo='".$this->anyo."'";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
 
}
?>