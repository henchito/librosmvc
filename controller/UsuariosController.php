<?php
class UsuariosController extends ControladorBase{
    public $conectar;
    public $adapter;
    
    public function __construct() {
        parent::__construct();
        
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }
     
    public function index(){
        /*** AQUI HAY QUE MOSTRAR EL LOGIN ***/ 
        //Creamos el objeto usuario
        $usuario=new Usuario($this->adapter); //No debería ser UsuarioModel??
         
        //Conseguimos todos los usuarios
        $allusers=$usuario->getAll();
        
        //Producto
        /*
        $producto=new Producto($this->adapter);
        $allproducts=$producto->getAll();
        */
        //Cargamos la vista index y le pasamos valores
        $this->view("index",array(
            "allusers"=>$allusers,
            "allproducts" => null,//$allproducts,
            "Hola"    =>"Soy Víctor Robles"
        ));
    }
    
    public function salir(){
        //Cerramos la conexión con la base de datos;
        $this->adapter->close();
        //Destruimos la sesión
        $s = Session::getInstance();
        $s->destroy();
        
    }
    
    public function novalido(){
        
    }
    
    public function principal(){
        //Recuperamos la sesion
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            $this->redirect("Usuarios", "index");
        }
        $this->view("menu",array( ));
        
    }
    
    
    public function comprobar(){
        if(isset($_POST["id"])){
            $usuario=new Usuario($this->adapter);
            $u = $usuario->getById($_POST["id"]);
            if ($u!=null){
                //Comprobar si la password coincide
                if (password_verify ($_POST["password"], $u->password))
                    $usuario->setId($_POST["id"]);
                    $s = Session::getInstance();
                    $s->usuario = $usuario;
                    $this->redirect("Usuarios", "principal");
                else 
                    $this->redirect("Usuarios", "novalido");
            }
        }
    }
    
    
    
    /* Para más adelante */
    public function crear(){
        if(isset($_POST["id"])){
             
            //Creamos un usuario
            $usuario=new Usuario($this->adapter);
            
            //Comprobar que no se mete morralla en los datos para sqlexploit
            
            $usuario->setId($_POST["id"]);
            $usuario->setPassword(password_hash($_POST["password"],PASSWORD_DEFAULT));
            $save=$usuario->save();
        }
        $this->redirect("Usuarios", "index");
    }
     
    public function borrar(){
        if(isset($_GET["id"])){ 
            $id=(int)$_GET["id"];
             
            $usuario=new Usuario($this->adapter);
            $usuario->deleteById($id); 
        }
        //Redirigimos por defecto a Usuario index
        $this->redirect();
    }
     
     
    public function hola(){
        $usuarios=new UsuariosModel($this->adapter);
        $usu=$usuarios->getUnUsuario();
        var_dump($usu);
    }
 
}
?>