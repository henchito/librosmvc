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
    
    public function crear(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            //Habría que comprobar que el libro no existe ya
            $lm = new LibrosModel($this->adapter);
            if (!$lm->compruebaExiste($_POST["id"])){
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
                "libritoC"=>$correcto,
                "libritoI"=>$incorrecto
            ));
        }
    }
    
    public function gestionar(){
        
    }
}
?>