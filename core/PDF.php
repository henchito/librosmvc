<?php
require('./core/Fpdf/fpdf.php');

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