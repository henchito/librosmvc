<?php
class EditorialesController extends ControladorBase{
    public $conectar;
    public $adapter;
    
    public function __construct() {
        parent::__construct();
        
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }
     
    public function crear(){
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->view("login",array());
        } else {
            if(isset($_POST["idEditorial"])){
                $e = new Editorial($this->adapter);
                
                $e->setId($_POST["idEditorial"]);
                $e->save();
                $correcto = "Editorial ".$e->getId()." creada correctamente";
            } else {
                $incorrecto = "Error al crear la editorial";
            }
            $this->view("menu",array(
                "editoC"=>$correcto,
                "editoI"=>$incorrecto
            ));
        }
    }
}