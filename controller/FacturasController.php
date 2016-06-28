<?php
class FacturasController extends ControladorBase{
    public $conectar;
    public $adapter;
    
    public function __construct() {
        parent::__construct();
        
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }
    
    /*** Función para iniciar el carrito para la creación de Facturas ***/
    public function iniciar(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            $this->destruyeFactura();
            //Buscamos el último número de factura
            $fm = new FacturasModel($this->adapter);
            $s->numFact = $fm->buscaUltimaFactura();
            $this->view("carrito",array("numFact"=>$s->numFact));
        }
    }
    
    /*** Función para añadir un libro al carrito ***/
    public function anadeLibro(){
        $s = Session::getInstance();
        if(isset($_POST["isbn"])){
            $libro = new Libro($this->adapter);
            $l = $libro->getBy("id", $_POST["isbn"]);
            if (isset($l[0])){
                $s->meteEnArray("incluidos", $l[0]);
                $s->total += $l[0]->precio;
                if ($l[0]->maxDescuento==5) //Añadirmos al descuento de libros 5% el precio del libro
                    $s->n += $l[0]->precio;
                elseif ($l[0]->maxDescuento==10) //Añadirmos al descuento de libros 10% el precio del libro
                    $s->r += $l[0]->precio;
            }
        }
        //Enviamos a la vista los datos necesarios para pintar
        $this->view("carrito",array(
                    "incluidos"=>$s->incluidos,
                    "numFact"=>$s->numFact,
                    "total"=>$s->total,
                    "n"=>$s->n,
                    "r"=>$s->r));
    }
    
    /*** Función para eliminar un libro del carrito ***/
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
        }
        // pintar los libros que quedan
        $this->view("carrito",array(
                    "incluidos"=>$s->incluidos,
                    "numFact"=>$s->numFact,
                    "total"=>$s->total,
                    "n"=>$s->n,
                    "r"=>$s->r));
    }
    
    /*** Función para crear la Factura ***/
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
        /*** Creación de la Factura en la BBDD ***/ 
        $factura = new Factura($this->adapter);
        $factura->setNumero($_POST["numero"]);
        $factura->setNif($s->nif);
        $factura->setAnyo(date("Y"));
        $factura->setDescuento($descuento);
        $factura->setTipo("V");
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

        /*** Inicio de Generar el PDF ***/
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
         
        //Cabecera
        $pdf->arriba("V", $factura->getNumero(), $factura->getAnyo(), date("d-m-Y",strtotime($factura->getFecha())));
        
        //Cliente
        $pdf->cliente($_POST["nombre"], $s->nif, $_POST["direccion"]);
        
        //Libros
        $pdf->libros($s->incluidos, "V");
        
        //Sumas
        $pdf->totales($s->total, $descuento, $s->n, $s->r, "V");
        $texto = "V".$_POST["numero"].'_'.$factura->getAnyo().'.pdf';
        //Vaciamos todos los datos de la sesión excepto usuario
        $this->destruyeFactura();
        //Imprimimos el PDF
        $pdf->Output('I', $texto, false);

        //Añadir un elemento vendido en Stock!
        
        
        
    }
    
    /* Funcion para borrar los datos de la Factura guardados en la sesión */
    public function destruyeFactura(){
        $s = Session::getInstance();
        unset($s->incluidos);
        unset($s->total);
        unset($s->n);
        unset($s->r);
        unset($s->nif);
        unset($s->cliente);
    }
    
    /* Funcion para iniciar la impresión de Facturas entre fechas */
    public function informe(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            $this->view("informeFacturas",array());
        }
    }
    
    /* Funcion para la impresión de Facturas entre fechas */
    public function buscaFacturas(){
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $lm = new LibrosModel($this->adapter);
        $cm = new ClientesModel($this->adapter);
        $fm = new FacturasModel($this->adapter);
        
        //Comprobar que la fecha inicial es menor que la final
        
        
        //Buscamos las facturas entre fechas
        $rsf=$fm->buscaEntreFechas($_POST["fechaIni"],$_POST["fechaFin"]);
        if (!is_bool($rsf)){
            if(is_object($rsf))
                $rs[]=$rsf;
            else
                $rs=$rsf;
            foreach ($rs as $factura) {
                $pdf->AddPage();
                $total=0;
                $n=0;
                $r=0;
                $rl=array();
                $libros=array();
                //Buscamos todos los datos de cada libro
                $rlf = $fm->buscaLibrosDeFactura($factura->id);
                if(is_object($rlf))
                    $rl[]=$rlf;
                else
                    $rl=$rlf;
                foreach ($rl as $l){
                    $uno = $lm->compruebaExiste($l->idlibro);
                    $libros[]=$uno;
                    $total += $uno->precio;
                    if (($uno->maxDescuento == 5) and (($factura->descuento==15) or $factura->descuento==5))
                        $n += $uno->precio;
                    if (($uno->maxDescuento == 10) and (($factura->descuento==15) or $factura->descuento==10))
                        $r += $uno->precio;
                }
                //Buscamos los datos del cliente
                $rc = $cm->buscaClienteDeFactura($factura->nif);
                
                /* Creamos el PDF de esta factura */
                //Cabecera
                $pdf->arriba($factura->tipo, $factura->numero, $factura->anyo, date("d-m-Y",strtotime($factura->fecha)));
                //Cliente
                $pdf->cliente($rc->nombre, $factura->nif, $rc->direccion);
                //Libros
                $pdf->libros($libros, $factura->tipo);
                //Sumas
                $pdf->totales($total, $factura->descuento, $n, $r, $factura->tipo);

            }
            $pdf->Output('I', 'InformeFacturas'.'.pdf', false);
        } else {
            $this->view("informeFacturas",array(
                "mensaje"=>"No existe ninguna factura entre las fechas seleccionadas"));
        }
    }
    
    public function inicioBuscaManual(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            $this->view("buscaLibro",array());
        }
    }
    
    public function buscaPorTitulo() {
        $lm = new LibroModel($this->adapter);
        $l = $lm->libroPorTitulo($_POST["titulo"]);
        if($l===true){
            $this->view("buscaLibro",array(
                "errorL"=>"No se ha encontrado ningún libro por ese título"
                ));
        } else{
            if (is_object($l))
                $libros[]=$l;
            if (is_array($l))
                $libros=$l;
            $this->view("buscaLibro",array(
                "loslibros"=>$libros
                ));
        }
    }
}