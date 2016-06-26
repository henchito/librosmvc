<?php
class Libro extends EntidadBase{
    private $id;
    private $titulo;
    private $edicion;
    private $editorial;
    private $precio;
    private $proyecto;
    private $maxDescuento;
    private $activo;
     
    public function __construct($adapter) {
        $table="Libro";
        parent::__construct($table,$adapter);
    }
     
    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
     
    public function getTitulo() {
        return $this->titulo;
    }
 
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
    
    public function getEdicion(){
        return $this->edicion;
    }
    
    public function setEdicion($edicion){
        $this->edicion = $edicion;
    }
    
    public function getEditorial(){
        return $this->editorial;
    }
    
    public function setEditorial($editorial){
        $this->editorial = $editorial;
    }
    
    public function getPrecio(){
        return $this->precio;
    }
    
    public function setPrecio($precio){
        $this->precio = $precio;
    }
    
    public function getProyecto(){
        return $this->proyecto;
    }
    
    public function setProyecto($proyecto){
        $this->proyecto = $proyecto;
    }
    
    public function getMaxDescuento(){
        return $this->maxDescuento;
    }
    
    public function setMaxDescuento($maxDescuento){
        $this->maxDescuento = $maxDescuento;
    }
    
    public function getActivo(){
        return $this->activo;
    }
    
    public function setActivo($activo){
        $this->activo = $activo;
    }
    
 
    public function save(){
        $query="INSERT INTO Libro (id,titulo,edicion,editorial,precio,proyecto,maxDescuento, activo)
                VALUES('".$this->id."',
                       '".$this->titulo."',
                       '".$this->edicion."',
                       '".$this->editorial."',
                       '".$this->precio."',
                       '".$this->proyecto."',
                       '".$this->maxDescuento."',
                       '".$this->activo."');";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
    
    public function update(){
        $query="UPDATE Libro SET 
                    id='".$this->id."',
                    titulo='".$this->titulo."',
                    edicion='".$this->edicion."',
                    editorial='".$this->editorial."',
                    precio='".$this->precio."',
                    proyecto='".$this->proyecto."',
                    maxDescuento='".$this->maxDescuento."',
                    activo='".$this->activo."'
                WHERE id='".$this->id."';";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
 
}
?>