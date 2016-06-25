<?php
class Factura extends EntidadBase{
    private $id;
    private $numero;
    private $nif;
    private $anyo;
    private $descuento;
    private $tipo;
    private $usuario;
    private $fecha;
     
    public function __construct($adapter) {
        $table="Factura";
        parent::__construct($table,$adapter);
    }
     
    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
     
    public function getNif() {
        return $this->nif;
    }
 
    public function setNif($nif) {
        $this->nif = $nif;
    }
    
    public function getNumero(){
        return $this->numero;
    }
    
    public function setNumero($numero){
        $this->numero = $numero;
    }
    
    public function getAnyo(){
        return $this->anyo;
    }
    
    public function setAnyo($anyo){
        $this->anyo = $anyo;
    }
    
    public function getDescuento(){
        return $this->descuento;
    }
    
    public function setDescuento($descuento){
        $this->descuento = $descuento;
    }
    
    public function getTipo(){
        return $this->tipo;
    }
    
    public function setTipo($tipo){
        $this->tipo = $tipo;
    }
    
    public function getUsuario(){
        return $this->usuario;
    }
    
    public function setUsuario($usuario){
        $this->usuario = $usuario;
    }
    
    public function getFecha(){
        return $this->fecha;
    }
    
    public function setFecha($fecha){
        $this->fecha = $fecha;
    }
    
 
    public function save(){
        $query="INSERT INTO Factura (numero, nif, anyo, descuento, tipo, usuario, fecha)
                VALUES('".$this->numero."',
                       '".$this->nif."',
                       '".$this->anyo."',
                       '".$this->descuento."',
                       '".$this->tipo."',
                       '".$this->usuario."',
                       '".$this->fecha."');";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
 
}
?>