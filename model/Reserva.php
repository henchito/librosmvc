<?php
class Reserva extends EntidadBase{
    private $id;
    private $numero;
    private $alumno;
    private $telefono;
    private $email;
    private $curso;
    private $anyo;
    
     
    public function __construct($adapter) {
        $table="Reserva";
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
    
    public function getAlumno(){
        return $this->alumno;
    }
    
    public function setAlumno($alumno){
        $this->alumno = $alumno;
    }
    
    public function getTelefono(){
        return $this->telefono;
    }
    
    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function setEmail($email){
        $this->email = $email;
    }
    
    public function getCurso(){
        return $this->curso;
    }
    
    public function setCurso($curso){
        $this->curso=$curso;
    }
    
    public function getAnyo(){
        return $this->anyo;
    }
    
    public function setAnyo($anyo){
        $this->anyo=$anyo;
    }
    
    public function save(){
        $query="INSERT INTO Reserva (numero, alumno, curso, telefono, email, anyo)
                VALUES('".$this->numero."',
                       '".$this->alumno."',
                       '".$this->curso."',
                       '".$this->telefono."',
                       '".$this->email."',
                       '".$this->anyo."');";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
 
}
?>