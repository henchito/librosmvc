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
    
    //Llevar a la vista para la creacion de un nuevo libro 
    public function precrear(){
        
    }
    
    public function crear(){
        
    }
    
    public function gestionar(){
        
    }
}
?>