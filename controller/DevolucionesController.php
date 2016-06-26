<?php
class DevolucionesController extends ControladorBase{
    public $conectar;
    public $adapter;
    
    public function __construct() {
        parent::__construct();
        
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }
     
    public function iniciar(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            $this->destruyeFactura();
            //Buscamos el último número de factura
            $fm = new FacturasModel($this->adapter);
            $s->numFact = $fm->buscaUltimaFactura();
            $this->view("carritoDevolucion",array("numFact"=>$s->numFact));
        }
    }
    
    public function anadeLibro(){
        $s = Session::getInstance();
        if(isset($_POST["isbn"])){
            $libro = new Libro($this->adapter);
            $l = $libro->getBy("id", $_POST["isbn"]);
            if (isset($l[0])){
                $s->meteEnArray("incluidos", $l[0]);
                $s->total += $l[0]->precio;
                if ($l[0]->maxDescuento==5){ //Añadirmos al descuento de libros 5% el precio del libro
                    $s->n += $l[0]->precio;
                } elseif ($l[0]->maxDescuento==10){ //Añadirmos al descuento de libros 10% el precio del libro
                    $s->r += $l[0]->precio;
                }
            }
        }
        //Enviamos a la vista los datos necesarios para pintar
        $this->view("carritoDevolucion",array(
                    "incluidos"=>$s->incluidos,
                    "numFact"=>$s->numFact,
                    "total"=>$s->total*-1,
                    "n"=>$s->n*-1,
                    "r"=>$s->r*-1));
    }
    
    public function borrarLibro(){
        
         if(isset($_GET["isbn"])){ 
            $isb=$_GET["isbn"];
            // Buscamos en los incluidos el libro
            $s = Session::getInstance();
            foreach ($s->incluidos as $libro => $v){
                if ($v->id == $isb){
                    $s->quitaDeArray("incluidos", $libro); //Borramos el libro que corresponde
                    $s->total -= $v->precio;
                    if ($v->maxDescuento==5) //Añadirmos al descuento de libros 5% el precio del libro
                        $s->n -= $v->precio;
                    elseif ($v->maxDescuento==10) //Añadirmos al descuento de libros 10% el precio del libro
                        $s->r -= $v->precio;
                    
                }
            }

            /* Esto se haría si hay que borrarlo de la base de datos
            $libro=new Libro($this->adapter);
            $libro->deleteById($isbn); */
        }
        // pintar los libros que quedan
        $this->view("carritoDevolucion",array(
                    "incluidos"=>$s->incluidos,
                    "numFact"=>$s->numFact,
                    "total"=>$s->total*-1,
                    "n"=>$s->n*-1,
                    "r"=>$s->r*-1));
    }
    
    public function crearFactura(){
        $descuento=0;
        if(isset($_POST["descN"])){
            $descuento += 5;
        }
        if(isset($_POST["descR"])){
            $descuento += 10;
        }
        $s = Session::getInstance();
        //Comprobamos que no esta el cliente en la sesión para añadirlo a la BBDD
        if (!isset($s->cliente)){
            $cliente = new Cliente($this->adapter);
            $cliente->setNif($s->nif);
            $cliente->setNombre($_POST["nombre"]);
            $cliente->setDireccion($_POST["direccion"]);
            $cliente->save();
        }
         
        $factura = new Factura($this->adapter);
        $factura->setNumero($_POST["numero"]);
        $factura->setNif($s->nif);
        $factura->setAnyo(date("Y"));
        $factura->setDescuento($descuento);
        $factura->setTipo("D");
        $factura->setUsuario($s->usuario->getId());
        $factura->setFecha(date("Y-m-d"));
        $factura->save();
        //Recuperamos el id de factura
        $fm = new FacturasModel($this->adapter);
        $factura->setId($fm->getIdDeFactura($factura));
        //Recorremos la lista de libros incluidos en la factura y los insertamos en la tabla FacturaLibro
        foreach ($s->incluidos as $libro => $v){
             $fm->facturaLibro($v->id, $factura->getId());
        }

        //Aquí habría que general el PDF y redirigir para mostrarlo y poder imprimir
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        
        //Cabecera
        $pdf->arriba("D", $factura->getNumero(), $factura->getAnyo(), date("d-m-Y",strtotime($factura->getFecha())));
        
        //Cliente
        $pdf->cliente($_POST["nombre"], $s->nif, $_POST["direccion"]);
        
        //Libros
        $pdf->libros($s->incluidos, "D");
        
        //Sumas
        $pdf->totales($s->total, $descuento, $s->n, $s->r, "D");
        $texto = "D".$_POST["numero"].'_'.$factura->getAnyo().'.pdf';
        $this->destruyeFactura();
        $pdf->Output('I', $texto, false);
       
        //Añadir un elemento devuelto en Stock!
        
        //Vaciamos todos los datos de la sesión excepto usuario
        $this->destruyeFactura();
    }
    
    public function destruyeFactura(){
        $s = Session::getInstance();
        unset($s->incluidos);
        unset($s->total);
        unset($s->n);
        unset($s->r);
        unset($s->nif);
        unset($s->cliente);
    }
}