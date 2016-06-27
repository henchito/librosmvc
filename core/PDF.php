<?php
require('Fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    /*
    global $title;
    // Logo
    //$this->Image('logo_pb.png',10,8,33);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(80);
    // Título
    $this->Cell(30,10,$title,1,0,'C');
    // Salto de línea
    $this->Ln(20);
    */
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,utf8_decode('Pág. '.$this->PageNo().'/{nb}'),0,0,'C');
}

public function arriba($tipo, $numero, $anyo, $fecha){
    $this->SetFont('Arial','B',14);
    $this->Cell(20,10,"");
    $this->Cell(30,10,"FACTURA",0,0,'L',false);
    $this->Cell(30,10,$tipo." ".$numero." / ".$anyo,0,0,'L',false);
    $this->SetFillColor(0);
    $this->Cell(5,10,"",0,0,'C',false);
    $this->Cell(30,10,"FECHA",0,0,'L',false);
    $this->Cell(0,10,$fecha,0,1,'L',false);
}

public function cliente($nombre, $nif, $direccion ){
    //Imagen
    $this->Image("./images/logocolegio.jpg",10,20,45,20);
        
    //Cabecera Cliente
    // Movernos a la derecha
    $this->Ln(13);
    $this->Cell(70); // con la imagen y esto estamos a 60mm
    $this->SetLineWidth(.5);
    $this->Cell(0, 7, "Cliente", 'B', 1, 'L', false);
    $this->SetFont('Arial','',10);
    $this->Cell(70, 5, "Colegio La Inmaculada-Marillac", 0, 0, 'L', false);
    $this->Cell(0, 7, utf8_decode($nombre),0,1, 'L', false);
    $this->SetY($this->GetY()-2, false);
    $this->Cell(70, 5, "R7800929G", 0, 0, 'L', false);
    $this->SetY($this->GetY()+2, false);
    $this->Cell(0, 7, $nif,0,1, 'L', false);
    $this->SetY($this->GetY()-4, false);
    $this->Cell(70, 5, utf8_decode("C/ García de Paredes 37"), 0, 0, 'L', false);
    $this->SetY($this->GetY()+4, false);
    $this->Cell(0, 7, utf8_decode($direccion),0,1, 'L', false);
    $this->SetY($this->GetY()-6, false);
    $this->Cell(0, 5, "28010 - Madrid", 0, 1, 'L', false);
    $this->Cell(0, 5, "Tlf: 91 445 35 34", 0, 1, 'L', false);
    $this->Cell(0, 5, "Fax: 91 591 28 34", 0, 1, 'L', false);
}

public function libros($incluidos, $tipo){
    $this->Ln(10);
    $this->SetFont('Arial','B',12);
    $this->Cell(20,7,"Cant.",'B',0,'L',false);
    $this->Cell(70,7,"Libro",'B',0,'L',false);
    $this->Cell(50,7,"Editorial",'B',0,'L',false);
    $this->Cell(40,7,"Precio",'B',0,'L',false);
    $this->Cell(10,7,"",'B',1,'',false); //Columna de tipo
    // Color and font restoration
    $this->SetTextColor(0);
    $this->SetFont('Arial','',10);
    // Libros a facturar
    $w = array(20, 70, 50, 40, 10);
    foreach($incluidos as $libro)
    {
        $this->Cell($w[0],6,number_format("1"),0,0,'L',false);
        $this->Cell($w[1],6,utf8_decode($libro->titulo),0,0,'L',false);
        $this->Cell($w[2],6,utf8_decode($libro->editorial),0,0,'L',false);
        if ($tipo=="D")
            $this->Cell($w[3],6,number_format($libro->precio*-1,2)." ".chr(128),0,0,'R',false);
        else
            $this->Cell($w[3],6,number_format($libro->precio,2)." ".chr(128),0,0,'R',false);
        if ($libro->maxDescuento==5)
            $d='N';
        elseif ($libro->maxDescuento==10)
            $d='R';
        else
            $d='';
        $this->Cell($w[4],6,$d,0,1,'R',false);
    }
}

public function totales($total, $descuento, $n, $r, $tipo){
    $this->Cell(140,6,"Suma    ",'T',0, 'R', false);
    if ($tipo=="V")
        $this->Cell(40, 6,number_format($total,2)." ".chr(128), 'T',0, 'R', false);
    else
        $this->Cell(40, 6,number_format($total*-1,2)." ".chr(128), 'T',0, 'R', false);
    $this->Cell(10,6,"",'T',1,'L',false);
    //Descuento 5%
    if ($descuento==5 or $descuento==15) {
        $d5 = number_format($n*0.05, 2);
        $this->Cell(140,6,"Descuento 5% (N)    ",0,0, 'R', false);
        if ($tipo=="V")
            $this->Cell(40, 6, $d5." ".chr(128), 0,1, 'R', false);
        else 
            $this->Cell(40, 6, ($d5*-1)." ".chr(128), 0,1, 'R', false);
    }
    //Descuento 10%
    if ($descuento==10 or $descuento==15) {
        $d10 = number_format($r*0.1, 2);
        $this->Cell(140,6,"Descuento 10% (R)    ",0,0, 'R', false);
        if ($tipo="V")
            $this->Cell(40, 6, $d10." ".chr(128), 0,1, 'R', false);
        else
            $this->Cell(40, 6, ($d10*-1)." ".chr(128), 0,1, 'R', false);
    }
    //Subtotal
    $st = number_format($total-$d5-$d10, 2);
    $this->Cell(140, 6, "Subtotal    ", 0, 0, 'R', false);
    if($tipo=="V")
        $this->Cell(40, 6, $st." ".chr(128), 0, 1, 'R', false);
    else
        $this->Cell(40, 6, ($st*-1)." ".chr(128), 0, 1, 'R', false);
    //IVA
    $iva = number_format($st*0.04, 2);
    $this->Cell(140, 6, utf8_decode("IVA 4%    "), 0, 0, 'R', false);
    if ($tipo=="V")
        $this->Cell(40, 6, $iva." ".chr(128), 0, 1, 'R', false);
    else
        $this->Cell(40, 6, ($iva*-1)." ".chr(128), 0, 1, 'R', false);
    //Gastos de gestion
    $gestion = 2;
    $this->Cell(140, 6, utf8_decode("Gastos de gestión    "), 0, 0, 'R', false);
    if ($tipo=="V")
        $this->Cell(40, 6, $gestion." ".chr(128), 0, 1, 'R', false);
    else
        $this->Cell(40, 6, ($gestion*-1)." ".chr(128), 0, 1, 'R', false);
    //TOTAL
    $tt = number_format($st+$iva+$gestion, 2);
    $this->SetFont('Arial','B',14);
    $this->Cell(140, 10, "TOTAL    ", 0, 0, 'R', false);
    if ($tipo=="V")
        $this->Cell(40, 10, $tt." ".chr(128), 0, 1, 'R', false);
    else
        $this->Cell(40, 10, ($tt*-1)." ".chr(128), 0, 1, 'R', false);
}

//Tabla de libros
function FancyTable($libros)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    /* Cell (
        widht, en mm
        height, en mm
        String to print,
        border, [0->no][1->frame] "[L][T][R][B]"->left, top, right, bottom
        position after, [0->right][1->begining next line][2->below]     1== 0 and Ln()
        align, [L->left][C->center][R->right]
        fill, true / false
        link
        )*/
    $this->Cell(40,7,"Cantidad",1,0,'C',true);
    $this->Cell(35,7,"Libro",1,0,'C',true);
    $this->Cell(40,7,"Editorial",1,0,'C',true);
    $this->Cell(45,7,"Precio",1,0,'C',true);
    $this->Cell(10,7,"",0,0,'',false); //Columna de tipo
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    $w = array(40, 35, 40, 45, 10);
    foreach($libros as $libro)
    {
        $this->Cell($w[0],6,number_format("1"),'LR',0,'L',$fill);
        $this->Cell($w[1],6,$libro->getTitulo(),'LR',0,'L',$fill);
        $this->Cell($w[2],6,$libro->getEditorial(),'LR',0,'L',$fill);
        $this->Cell($w[3],6,number_format($libro->getPrecio()),'LR',0,'R',$fill);
        if ($libro->getMaxDescuento()==5)
            $d='B';
        elseif ($libro->getMaxDescuento()==10)
            $d='C';
        else
            $d='A';
        $this->Cell($w[4],6,$d,'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}



}