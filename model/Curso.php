<?php
class Curso extends EntidadBase{
    private $id;
    private $numero;
    private $ciclo;
     
    public function __construct($adapter) {
        $table="Curso";
        parent::__construct($table,$adapter);
    }
     
    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getNumero(){
        return $this->numero;
    }
    
    public function setNumero($numero){
        $this->numero=$numero;
    }
    
    public function getCiclo(){
        return $this->ciclo;
    }
    
    public function setCiclo($ciclo){
        $this->ciclo = $ciclo;
    }
    
    public function save(){
        $query="INSERT INTO Curso (numero, ciclo) VALUES(
                '".$this->numero."',
                '".$this->ciclo."'
                );";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
}
?>