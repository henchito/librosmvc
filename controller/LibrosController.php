<?php
class LibrosController extends ControladorBase{
    public $conectar;
    public $adapter;
    
    public function __construct() {
        parent::__construct();
        
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }
     
    public function index(){
    }
    /* FUNCIONES PARA CREAR NUEVOS LIBROS */
    public function crear(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            //Habría que comprobar que el libro no existe ya
            $lm = new LibrosModel($this->adapter);
            $e = $lm->compruebaExiste($_POST["id"]);
            if (!is_array($e) and !is_object($e)){
                $libro = new Libro($this->adapter);
                $libro->setId($_POST["id"]);
                $libro->setTitulo($_POST["titulo"]);
                $libro->setEdicion($_POST["edicion"]);
                $libro->setProyecto($_POST["proyecto"]);
                $libro->setEditorial($_POST["editorial"]);
                $libro->setPrecio($_POST["precio"]);
                $libro->setMaxDescuento($_POST["maxDescuento"]);
                $libro->setActivo(true);
                $libro->save();
                //Añadir a la tabla LibroCurso
                foreach ($_POST["curso"] as $c){
                    $lm->libroCurso($_POST["id"], $c);
                }
                $correcto=" Libro creado correctamente.";
            } else {
                $incorrecto=" El libro ya existe.";
            }
            
            $this->view("menu",array(
                "tipoU"=>$s->usuario->getTipo(),
                "libritoC"=>$correcto,
                "libritoI"=>$incorrecto
            ));
        }
    }
    
    /* FUNCIONES PARA GESTIONAR Y MODIFICAR LIBROS */
    public function gestionar(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            $this->view("modificaLibro",array());
        }
    }
    
    public function buscaLibro(){
        
        $lm = new LibrosModel($this->adapter);
        $e = $lm->compruebaExiste($_POST["isbn"]);
        if ($e===true) {
            $this->view("modificaLibro",array(
                "errorL"=>"No se ha encontrado ningún libro por ese ISBN"
                ));
        } else {
            $crl = $lm->cursosDeLibro($_POST["isbn"]);
            if (is_object($crl))
                $cr[] = $crl;
            if (is_object($e)) {
                $this->view("modificaLibro", array(
                    "libro"=>$e,
                    "cursosL"=>$cr
                    ));
            }
        }
    }
    
    public function actualizaLibro(){
        $libro = new Libro($this->adapter);
        $libro->setId($_POST["isbn"]);
        $libro->setTitulo($_POST["titulo"]);
        $libro->setEdicion($_POST["edicion"]);
        $libro->setEditorial($_POST["editorial"]);
        $libro->setProyecto($_POST["proyecto"]);
        $libro->setPrecio($_POST["precio"]);
        $libro->setMaxDescuento($_POST["maxDescuento"]);
        if(isset($_POST["activo"]))
            $libro->setActivo(true);
        else
            $libro->setActivo(false);
        $libro->update();
        //Cambiar lo que sea necesario en la tabla LibroCurso
        $lm = new LibrosModel($this->adapter);
        $lm->replaceLibroCurso($_POST["isbn"], $_POST["curso"]);
        $this->view("modificaLibro",array(
            "mensaje"=>"Libro modificado correctamente"
            ));
    }
    
    
    
    /* FUNCIONES PARA MOSTRAR EL INFORME DE LIBROS */
    public function informe(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            $this->view("informeLibros",array());
        }
    }
    
    public function librosDeEditorial(){
        $lm = new LibrosModel($this->adapter);
        $rle=$lm->librosDeLaEditorial($_POST["editorial"]);
        if(is_object($rle))
            $rs[]=$rle;

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
         
        //Cabecera
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(30,10,"EDITORIAL",0,0,'L',false);
        $pdf->Cell(0,10,utf8_decode($_POST["editorial"]),0,1,'L',false);
        //Tabla de libros
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,6,"ISBN",'B',0,'L',false);
        $pdf->Cell(55,6,"Titulo",'B',0,'L',false);
        $pdf->Cell(35,6,"Edicion",'B',0,'L',false);
        $pdf->Cell(35,6,"Proyecto",'B',0,'L',false);
        $pdf->Cell(15,6,"Precio",'B',0,'L',false);
        $pdf->Cell(10,6,"Desc.",'B',0,'L',false); //Columna de tipo
        $pdf->Cell(10,6,"Activo",'B',1,'L',false);
        // Color and font restoration
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',10);
        $pdf->SetFillColor(224,235,255);
        $fill = false;
        // Libros a facturar
        $w = array(30, 55, 35, 35, 15, 10, 10);
        foreach($rs as $libro)
        {
            $pdf->Cell($w[0],6,$libro->id,0,0,'L',false);
            $pdf->Cell($w[1],6,utf8_decode($libro->titulo),0,0,'L',$fill);
            $pdf->Cell($w[2],6,utf8_decode($libro->edicion),0,0,'L',$fill);
            $pdf->Cell($w[3],6,utf8_decode($libro->proyecto),0,0,'L',$fill);
            $pdf->Cell($w[4],6,number_format($libro->precio,2),0,0,'R',$fill);
            $pdf->Cell($w[5],6,$libro->maxDescuento." %",0,0,'R',$fill);
            if ($libro->activo)
                $a = "Sí";
            else 
                $a = "";
            $pdf->Cell($w[6],6,utf8_decode($a),0,1,'R', $fill);
            $fill = !$fill;
        }
        $pdf->Output('I', $_POST["editorial"].'.pdf', false);
    }
}
?>