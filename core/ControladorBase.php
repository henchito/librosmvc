<?php
class ControladorBase{
 
    public function __construct() {
        require_once 'Conectar.php';
        require_once 'EntidadBase.php';
        require_once 'ModeloBase.php';
        require_once 'Session.php';
        require_once 'PDF.php';
        require_once 'Mailer/class.phpmailer.php';
	    require_once 'Mailer/PHPMailerAutoload.php';
	    require_once 'Mailer/class.phpmaileroauthgoogle.php';
         
        //Incluir todos los modelos
        foreach(glob("model/*.php") as $file){
            require_once $file;
        }
    }
     
    //Plugins y funcionalidades
     
/*
* Este método lo que hace es recibir los datos del controlador en forma de array
* los recorre y crea una variable dinámica con el indice asociativo y le da el 
* valor que contiene dicha posición del array, luego carga los helpers para las
* vistas y carga la vista que le llega como parámetro. En resumen un método para
* renderizar vistas.
*/
    public function view($vista,$datos){
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor; 
        }
        if ($vista!="login") {
            //Recuperamos las editoriales y cursos para que se puedan pintar
            $nmE = new Editorial($this->adapter);
            $editoriales = $nmE->getAll();
            $nmC = new Curso($this->adapter);
            $cursos = $nmC->getAll();    
        }
        require_once 'core/AyudaVistas.php';
        $helper=new AyudaVistas();
     
        require_once 'view/'.$vista.'View.php';
    }
     
    public function redirect($controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO){
        header("Location:index.php?controller=".$controlador."&action=".$accion);
    }
    
}
?>