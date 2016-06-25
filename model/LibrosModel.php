<?php
class LibrosModel extends ModeloBase{
    private $table;
     
    public function __construct($adapter){
        $this->table="Libro";
        parent::__construct($this->table,$adapter);
    }
     
    //Metodos de consulta
    public function libroCurso($isbn, $id){
        $query="INSERT INTO LibroCurso (idlibro,idcurso)
                VALUES(".$isbn.",
                       ".$id.");";
        $this->ejecutarSql($query);
        //Controlar qué devuelve??
    }
    
    public function compruebaExiste ($isbn){
        $query="SELECT id FROM Libro WHERE id='".$isbn."'";
        $rs=$this->ejecutarSql($query);
        if (isset($rs[0])){
            return true;
        }
        else {
            return false;
        }
    }
}
?>