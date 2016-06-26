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
        //Recuperamos la sesion
        $s = Session::getInstance();
        if (!isset($s->usuario)){
            //Usuario no logeado
            $this->view("login",array());
        } else {
            //Usuario logeado
            $this->view("menu",array("tipoU"=>$s->usuario->getTipo()));
        }
        
        
        
        /*
        //Creamos el objeto usuario
        $usuario=new Usuario($this->adapter);
         
        //Conseguimos todos los usuarios
        $allusers=$usuario->getAll();
        
        //Producto
        
        $producto=new Producto($this->adapter);
        $allproducts=$producto->getAll();
        
        //Cargamos la vista index y le pasamos valores
        $this->view("index",array(
            "allusers"=>$allusers,
            "allproducts" => null,//$allproducts,
            "Hola"    =>"Soy Víctor Robles"
        ));
        */
    }
    
    public function salir(){
        //Cerramos la conexión con la base de datos;
        $this->adapter->close();
        //Destruimos la sesión
        $s = Session::getInstance();
        $s->destroy();
        $this->redirect("Usuarios", "login");
        
    }
    
    public function novalido(){
        $this->view("login",array(
            "errorLogin"=>"Usuario o contraseña incorrectos"
            ));
    }
    
    public function comprobar(){
        if(isset($_POST["id"])){
            $usuario=new Usuario($this->adapter);
            $u = $usuario->getBy("id", $_POST["id"]);
            if ($u!=null){
                //Si no tiene contraseña, mandar a un formulario para crear contraseña!!!
                if ($u[0]->password==null){
                    $this->view("contrasena",array(
                        "elnuevo"=>$u[0]->id
                    ));
                } else {
                    //Comprobar si la password coincide
                    if (password_verify ($_POST["password"], $u[0]->password)) {
                        $usuario->setId($_POST["id"]);
                        $usuario->setTipo($u[0]->tipo);
                        $s = Session::getInstance();
                        $s->usuario = $usuario;
                        $this->redirect("Usuarios", "index");
                    } else
                        $this->redirect("Usuarios", "novalido");
                }
            } else
                $this->redirect("Usuarios", "novalido");
        }
    }
    
    public function nuevaPassword(){
        if ($_POST["password1"]==$_POST["password2"]){

            $usuario = new Usuario($this->adapter);
            $usuario->setId($_POST["idusuario"]);
            $usuario->setPassword(password_hash($_POST["password1"],PASSWORD_DEFAULT));
            $usuario->update();
            $this->view("login",array(
                "contraSi"=>"Contraseña creada correctamente"
                ));
        } else{
            $this->view("contrasena",array(
                        "elnuevo"=>$_POST["idusuario"],
                        "errorLogin"=>"Las contraseñas no coinciden"
                    ));
        }
    }
    
    public function crear(){
        $s = Session::getInstance();
        if(isset($_POST["idUsuario"])){
            //Creamos un usuario
            $usuario=new Usuario($this->adapter);
            $usuario->setId($_POST["idUsuario"]);
            //$usuario->setPassword(null);
            $usuario->setTipo($_POST["tipo"]);
            //$usuario->setPassword(password_hash($_POST["password"],PASSWORD_DEFAULT));
            $usuario->save();
            $correcto="Usuario '".$_POST["idUsuario"]."' creado correctamente";
        } else
            $incorrecto="No se ha podido crear el usuario";
        $this->view("menu",array(
            "tipoU"=>$s->usuario->getTipo(),
            "usuarioC"=>$correcto,
            "usuarioI"=>$incorrecto
            ));
    }
    
     /* Para más adelante */ 
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