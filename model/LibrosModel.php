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
        $query="SELECT * FROM Libro WHERE id='".$isbn."'";
        $rs=$this->ejecutarSql($query);
        return $rs;
    }
    
    public function cursosDeLibro($isbn){
        $query="SELECT idcurso FROM LibroCurso WHERE idlibro='".$isbn."'";
        $rs=$this->ejecutarSql($query);
        return $rs;
    }
    
    public function replaceLibroCurso($isbn, $cursos){
        if (count($cursos)){
            foreach ($cursos as $c){
                $query="REPLACE LibroCurso (idlibro,idcurso) VALUES (".$isbn.", ".$c.")";
            }
        }  else {
            $query="DELETE FROM LibroCurso WHERE idlibro='".$isbn."'";
        }
        $this->ejecutarSql($query);
    }
    
    public function librosDeLaEditorial($editorial){
        $query ="SELECT * from Libro WHERE editorial='".$editorial."' ORDER BY titulo";
        $rs=$this->ejecutarSql($query);
        return $rs;
    }
    
    public function librosDeCurso($idcurso){
        $query ="SELECT * FROM Libro JOIN LibroCurso ON Libro.id=LibroCurso.idlibro AND LibroCurso.idcurso=".$idcurso.";";
        //Probar esta sentencia
        $rs=$this->ejecutarSql($query);
        return $rs;
    }
}
?>