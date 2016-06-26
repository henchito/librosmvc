<?php
class ClientesModel extends ModeloBase{
    private $table;
     
    public function __construct($adapter){
        $this->table="Cliente";
        parent::__construct($this->table,$adapter);
    }
     
    //Metodos de consulta
    public function buscaClienteDeFactura($nif){
        $query="SELECT * FROM Cliente WHERE nif='".$nif."'";
        $rs=$this->ejecutarSql($query);
        return $rs;
    }
}
?>