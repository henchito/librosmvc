<?php
class ReservasModel extends ModeloBase{
    private $table;
     
    public function __construct($adapter){
        $this->table="Reserva";
        parent::__construct($this->table,$adapter);
    }
     
    //Metodos de consulta
    public function buscaUltimaReserva($anyo){
        $query="SELECT numero FROM Reserva WHERE anyo='".$anyo."'ORDER BY numero ASC";
        $rs = $this->ejecutarSql($query);
        if (is_array($rs))
            $n = (int) array_pop($rs)->numero;
        elseif (is_object($rs))
            $n = 1;
        else
            $n = 0;
        //Devuelve el último número de factura +1
        return $n+1;
    }
    
    public function reservaLibro($id, $isbn){
        $query="INSERT INTO ReservaLibro (idreserva,idlibro)
                VALUES(".$id.",
                       ".$isbn.");";
        $this->ejecutarSql($query);
        //Controlar qué devuelve??
    }
    
    public function buscaReserva($reserva){
        $query="SELECT id FROM Reserva WHERE numero=".$reserva->getNumero()." AND alumno='".$reserva->getAlumno()."' AND 
            telefono=".$reserva->getTelefono()." AND email='".$reserva->getEmail()."' AND curso=".$reserva->getCurso()." AND 
            anyo='".$reserva->getAnyo()."'";
        $id=$this->ejecutarSql($query);
        if (is_bool($id) and $id==true)
            $dev=-1;
        else
            $dev=$id->id;
        return $dev;
    }
    
    public function compruebaSiExiste($alumno, $curso, $anyo){
        $query="SELECT id FROM Reserva WHERE alumno='".$alumno."' AND curso=".$curso." AND anyo='".$anyo."'";
        $id=$this->ejecutarSql($query);
        if (is_bool($id) and $id==true)
            $dev=-1;
        else
            $dev=$id->id;
        return $dev;
    }
    
    public function textoCurso($curso){
        $query="SELECT numero, ciclo FROM Curso WHERE id=".$curso;
        $id=$this->ejecutarSql($query);
        return $id->numero." ".$id->ciclo;
    }
}
?>