<?php
class FacturasModel extends ModeloBase{
    private $table;
     
    public function __construct($adapter){
        $this->table="Factura";
        parent::__construct($this->table,$adapter);
    }
     
    //Metodos de consulta
    public function getIdDeFactura($factura){
        $query="SELECT id FROM Factura WHERE numero=".$factura->getNumero()." AND nif='".$factura->getNif()."' AND  anyo='".$factura->getAnyo()."' 
            AND descuento=".(int)$factura->getDescuento()." AND tipo='".$factura->getTipo()."' AND usuario='".$factura->getUsuario()."'";
        $id=$this->ejecutarSql($query);

        if ($id==false)
            return -1;
        else
            return $id->id;
    }
    
    public function facturaLibro($isbn, $id){
        $query="INSERT INTO FacturaLibro (idlibro,idfactura)
                VALUES(".$isbn.",
                       ".$id.");";
        $this->ejecutarSql($query);
        //Controlar qué devuelve??
    }
    
    public function buscaUltimaFactura(){
        $query="SELECT numero FROM Factura ORDER BY numero ASC";
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
}
?>