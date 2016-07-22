<?php
class StockModel extends ModeloBase{
    private $table;
     
    public function __construct($adapter){
        $this->table="Stock";
        parent::__construct($this->table,$adapter);
    }
     
    //Metodos de consulta
    public function buscaStock($id, $anyo){
        $query="SELECT * FROM Stock WHERE id='".$id."' and anyo='".$anyo."'";
        $rs=$this->ejecutarSql($query);
        return $rs;
    }
    
    public function anadeDevuelto(){
        
    }
}
?>