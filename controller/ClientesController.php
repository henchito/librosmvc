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
    
    /********** CAMBIAR LO QUE HACE LA FUNCION **************/
    public function anadeCliente(){
        $s = Session::getInstance();
        if(isset($_POST["isbn"])){
            $libro = new Libro($this->adapter);
            $l = $libro->getById((int)$_POST["isbn"]);
            if ($l != null){
                //En $l hay un resultSet así que terminamos el objeto Libro
                $s->incluidos[] = $l;
                $s->total += $l->precio;
                if ($l->maxDescuento==5) //Añadirmos al descuento de libros 5% el precio del libro
                    $s->n += $l->precio();
                elseif ($l->maxDescuento==10) //Añadirmos al descuento de libros 10% el precio del libro
                    $s->r += $l->precio;
            }
        }
        //Enviamos a la vista los datos necesarios para pintar
        $this->view("carrito",array(
                    "incluidos"=>$s->incluidos,
                    "total"=>$s->total,
                    "n"=>$s->n,
                    "r"=>$s->r));
    }
    /********** CAMBIAR LO QUE HACE LA FUNCION **************/
    public function borrarCliente(){
        
         if(isset($_GET["isbn"])){ 
            $isb=(int)$_GET["isbn"];
            // Buscamos en los incluidos el libro
            $s = Session::getInstance();
            foreach ($s->incluidos as $libro => $v){
                if ($libro->id == $isb)
                    unset($s->incluidos[$libro]); //Borramos el libro que corresponde
            }
            /* Esto se haría si hay que borrarlo de la base de datos
            $libro=new Libro($this->adapter);
            $libro->deleteById($isbn); */
        }
        //Redirigimos a Facturas->anadeLibro para pintar los libros que quedan
        $this->redirect("Facturas", "anadeLibro");
    }
    
    
}