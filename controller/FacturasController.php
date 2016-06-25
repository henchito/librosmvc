<?php
class FacturasController extends ControladorBase{
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
            $this->view("carrito",array("numFact"=>$s->numFact));
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
        $this->view("carrito",array(
                    "incluidos"=>$s->incluidos,
                    "numFact"=>$s->numFact,
                    "total"=>$s->total,
                    "n"=>$s->n,
                    "r"=>$s->r));
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
        $factura->setDescuento=$descuento;
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

        //Aquí habría que general el PDF y redirigir para mostrarlo y poder imprimir
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
         
        //Cabecera
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(20,10,"");
        $pdf->Cell(30,10,"FACTURA",0,0,'L',false);
        $pdf->Cell(30,10,$factura->getNumero()." / ".$factura->getAnyo(),0,0,'L',false);
        $pdf->SetFillColor(0);
        $pdf->Cell(5,10,"",0,0,'C',false);
        $pdf->Cell(30,10,"FECHA",0,0,'L',false);
        $pdf->Cell(0,10,date("d-m-Y",strtotime($factura->getFecha())),0,1,'L',false);
        //Imagen
        $pdf->Image("./images/logocolegio.jpg",10,20,45,20);
        
        //Cabecera Cliente
        // Movernos a la derecha
        $pdf->Ln(13);
        $pdf->Cell(70); // con la imagen y esto estamos a 60mm
        $pdf->SetLineWidth(.5);
        $pdf->Cell(0, 7, "Cliente", 'B', 1, 'L', false);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(70, 5, "Colegio La Inmaculada-Marillac", 0, 0, 'L', false);
        $pdf->Cell(0, 7, utf8_decode($_POST["nombre"]),0,1, 'L', false);
        $pdf->SetY($pdf->GetY()-2, false);
        $pdf->Cell(70, 5, "R7800929G", 0, 0, 'L', false);
        $pdf->SetY($pdf->GetY()+2, false);
        $pdf->Cell(0, 7, $s->nif,0,1, 'L', false);
        $pdf->SetY($pdf->GetY()-4, false);
        $pdf->Cell(70, 5, utf8_decode("C/ García de Paredes 37"), 0, 0, 'L', false);
        $pdf->SetY($pdf->GetY()+4, false);
        $pdf->Cell(0, 7, utf8_decode($_POST["direccion"]),0,1, 'L', false);
        $pdf->SetY($pdf->GetY()-6, false);
        $pdf->Cell(0, 5, "28010 - Madrid", 0, 1, 'L', false);
        $pdf->Cell(0, 5, "Tlf: 91 445 35 34", 0, 1, 'L', false);
        $pdf->Cell(0, 5, "Fax: 91 591 28 34", 0, 1, 'L', false);
        //Tabla de libros
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(20,7,"Cant.",'B',0,'L',false);
        $pdf->Cell(70,7,"Libro",'B',0,'L',false);
        $pdf->Cell(50,7,"Editorial",'B',0,'L',false);
        $pdf->Cell(40,7,"Precio",'B',0,'L',false);
        $pdf->Cell(10,7,"",'B',1,'',false); //Columna de tipo
        // Color and font restoration
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',10);
        // Libros a facturar
        $w = array(20, 70, 50, 40, 10);
        foreach($s->incluidos as $libro)
        {
            $pdf->Cell($w[0],6,number_format("1"),0,0,'L',false);
            $pdf->Cell($w[1],6,utf8_decode($libro->titulo),0,0,'L',false);
            $pdf->Cell($w[2],6,utf8_decode($libro->editorial),0,0,'L',false);
            $pdf->Cell($w[3],6,number_format($libro->precio,2)." ".chr(128),0,0,'R',false);
            if ($libro->maxDescuento==5 and $descuento!=0)
                $d='N';
            elseif ($libro->maxDescuento==10 and $descuento!=0)
                $d='R';
            else
                $d='';
            $pdf->Cell($w[4],6,$d,0,1,'R',false);
        }
        //Suma
        $pdf->Cell(140,6,"Suma    ",'T',0, 'R', false);
        $pdf->Cell(40, 6,number_format($s->total,2)." ".chr(128), 'T',1, 'R', false);
        //Descuento 5%
        if ($descuento==5 or $descuento==15) {
            $d5 = number_format($s->n*0.05, 2);
            $pdf->Cell(140,6,"Descuento 5% (N)    ",0,0, 'R', false);
            $pdf->Cell(40, 6, $d5." ".chr(128), 0,1, 'R', false);
        }
        //Descuento 10%
        if ($descuento==10 or $descuento==15) {
            $d10 = number_format($s->r*0.1, 2);
            $pdf->Cell(140,6,"Descuento 10% (R)    ",0,0, 'R', false);
            $pdf->Cell(40, 6, $d10." ".chr(128), 0,1, 'R', false);
        }
        //Subtotal
        $st = number_format($s->total-$d5-$d10, 2);
        $pdf->Cell(140, 6, "Subtotal    ", 0, 0, 'R', false);
        $pdf->Cell(40, 6, $st." ".chr(128), 0, 1, 'R', false);
        //IVA
        $iva = number_format($st*0.04, 2);
        $pdf->Cell(140, 6, utf8_decode("IVA 4%    "), 0, 0, 'R', false);
        $pdf->Cell(40, 6, $iva." ".chr(128), 0, 1, 'R', false);
        //Gastos de gestion
        $gestion = 2;
        $pdf->Cell(140, 6, utf8_decode("Gastos de gestión    "), 0, 0, 'R', false);
        $pdf->Cell(40, 6, $gestion." ".chr(128), 0, 1, 'R', false);
        //TOTAL
        $tt = number_format($st+$iva+$gestion, 2);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(140, 10, "TOTAL    ", 0, 0, 'R', false);
        $pdf->Cell(40, 10, $tt." ".chr(128), 0, 1, 'R', false);
        $pdf->Output('I', $_POST["numero"].'_'.$factura->getAnyo().'.pdf', false);
        //$pdf->Output('D', $_POST["numero"]."_".$factura->getAnyo().".pdf");
        //Añadir un elemento vendido en Stock!
        
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