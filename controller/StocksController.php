<?php
class StocksController extends ControladorBase{
    public $conectar;
    public $adapter;
    
    public function __construct() {
        parent::__construct();
        
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }
    
    public function librosEditorial(){
        
        //Buscar los libros por el $_POST["editorial"]
        $lm = new LibrosModel($this->adapter);
        $rl = $lm->librosDeLaEditorial($_POST["editorial"]);
        if (is_object($rl))
            $loslibros[] = $rl;
        elseif(is_array($rl))
            $loslibros = $rl;
        
        $st = new StockModel($this->adapter);
        //Añadir a cada elemento del resultset el campo "recibidos", "vendidos", "devueltos"
        foreach ($loslibros as $libro => $l) {
            //Buscar el Stock para cada libro
            $rst = $st->buscaStock($l->id, date("Y"));
            /* Si no hay stock creado, recibidos=0, vendidos=0, devueltos=0 */
            /* No estoy muy seguro de esto!! */
            $loslibros[$l]["recibidos"] = $rst->recibidos;
            $loslibros[$l]["vendidos"] = $rst->vendidos;
            $loslibros[$l]["devueltos"] = $rst->devueltos;
        }
        
        $this->view("stocks",array(
                "loslibros"=>$loslibros
            ));
    }
    
    public function actualizaRecibidos(){
        //Comprobar si hay dato creado en Stock, si no crear uno nuevo para ese libro/año
    }
    
    public function imprimir(){
        
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
                "tipoU"=>$s->usuario->getTipo(),
                "editoC"=>$correcto,
                "editoI"=>$incorrecto
            ));
        }
    }
}