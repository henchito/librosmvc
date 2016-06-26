<?php
class ClientesController extends ControladorBase{
    public $conectar;
    public $adapter;
    
    public function __construct() {
        parent::__construct();
        
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }
    
    public function buscaCliente(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            if(isset($_POST["nif"])){
                $s->nif = $_POST["nif"];
                //Buscamos si existe el cliente
                $cl = new Cliente ($this->adapter);
                $c = $cl->getBy("nif", $_POST["nif"]);
                if (isset($c[0])){
                    /* Guardamos en la sesión el objeto Cliente
                    $cl->setNif($c->nif);
                    $cl->setNombre($c->nombre);
                    $cl->setDireccion($c->direccion);
                    */
                    //Guardamos en la sesión una variable para indicar que el cliente existe
                    $s->cliente = true;
                    $this->view("carrito",array(
                        "incluidos"=>$s->incluidos,
                        "total"=>$s->total,
                        "n"=>$s->n,
                        "r"=>$s->r,
                        "numFact"=>$s->numFact,
                        "nombreC"=>$c[0]->nombre,
                        "direccionC"=>$c[0]->direccion,
                        "nifC" =>$s->nif,
                        "activado"=>true));    
                } else {
                    $this->view("carrito", array(
                        "incluidos"=>$s->incluidos,
                        "total"=>$s->total,
                        "n"=>$s->n,
                        "r"=>$s->r,
                        "nifC"=>$s->nif,
                        "numFact"=>$s->numFact,
                        "activado"=>false));
                }
                
            }
        }
    }
    
    public function buscaClienteDevolucion(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            if(isset($_POST["nif"])){
                $s->nif = $_POST["nif"];
                //Buscamos si existe el cliente
                $cl = new Cliente ($this->adapter);
                $c = $cl->getBy("nif", $_POST["nif"]);
                if (isset($c[0])){
                    /* Guardamos en la sesión el objeto Cliente
                    $cl->setNif($c->nif);
                    $cl->setNombre($c->nombre);
                    $cl->setDireccion($c->direccion);
                    */
                    //Guardamos en la sesión una variable para indicar que el cliente existe
                    $s->cliente = true;
                    $this->view("carritoDevolucion",array(
                        "incluidos"=>$s->incluidos,
                        "total"=>$s->total,
                        "n"=>$s->n,
                        "r"=>$s->r,
                        "numFact"=>$s->numFact,
                        "nombreC"=>$c[0]->nombre,
                        "direccionC"=>$c[0]->direccion,
                        "nifC" =>$s->nif,
                        "activado"=>true));    
                } else {
                    $this->view("carritoDevolucion", array(
                        "incluidos"=>$s->incluidos,
                        "total"=>$s->total,
                        "n"=>$s->n,
                        "r"=>$s->r,
                        "nifC"=>$s->nif,
                        "numFact"=>$s->numFact,
                        "activado"=>false));
                }
                
            }
        }
    }
    
}