<?php 
/************** CUIDADO CON EL TEMA DE LA SESIÓN!!!! ***************/
class ReservasController extends ControladorBase{
    public $conectar;
    public $adapter;
    
    public function __construct() {
        parent::__construct();
        
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }
     
    public function index(){
        $this->view("reservas",array());
    }
        
        
    public function librosCurso(){
        if (isset($_POST["curso"])){
            $lm = new LibrosModel($this->adapter);
            //Recuperamos todos los libros del curso seleccionado
            $rlc = $lm->librosDeCurso($_POST["curso"]);
            if(is_object($rlc))
                $lc[]=$rlc;
            else
                $lc=$rlc;

            foreach ($lc as $libro) {
                $loslibros[] = $libro;
            }
            $this->view("reservas",array(
                "loslibros"=>$loslibros,
                "cursoE"=>$_POST["curso"]
                ));
        } else {
            $this->redirect("Reservas","index");
        }
        //Terminar -> enviar a la vista loslibros y también el cursoE
    }
    
    public function crear(){
        $rm = new ReservasModel($this->adapter);
        $lm = new LibrosModel($this->adapter);
        /*Comprobar que no se mete morralla en los post*/
        
        /* 
        $permitidosC = '/^[A-Z0-9_\-.]{1,50}$/i'; 
        
        if (!ctype_alnum ($_POST["alumno"])){
        
        */
        if (preg_match('/[[:punct:]]/',$_POST["alumno"], $coincide)){
            foreach ($_POST["elegidos"] as $isbn) {
                $loslibros[] = $lm->compruebaExiste($isbn);;
            }
            $this->view("reservas",array(
            "loslibros"=>$loslibros,  
            "cursoE"=>$_POST["curso"],
            "errorR"=>"Nombre de alumno no válido"
            ));
        }
        
        
        /* Comprobamos que no está ya hecha la reserva */
        if ($rm->compruebaSiExiste($_POST["alumno"], $_POST["curso"], date("Y"))==-1){
            /* Creamos la reserva */
            $reserva = new Reserva($this->adapter);
            $reserva->setNumero($rm->buscaUltimaReserva(date("Y")));
            $reserva->setAlumno($_POST["alumno"]);
            $reserva->setTelefono($_POST["telefono"]);
            $reserva->setEmail($_POST["correo"]);
            $reserva->setCurso($_POST["curso"]);
            $reserva->setAnyo(date("Y"));
            $reserva->save();
            $reserva->setId($rm->buscaReserva($reserva));
            /* Insertamos los libros con la reserva en la tabla ReservaLibro*/
            foreach ($_POST["elegidos"] as $isbn){
                $rm->reservaLibro($reserva->getId(), $isbn);
            }
            
            $asunto = $reserva->getNumero()."-".$reserva->getAnyo();
            /* Generamos el documento PDF de la reserva */
            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();
            //Cabecera
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(30,10,"RESERVA",0,0,'L',false);
            $pdf->Cell(30,10,$asunto,0,1,'L',false);
            //Imagen
            $pdf->Image("./images/logocolegio.jpg",10,20,45,20);
            //Datos alumno
            // Movernos a la derecha
            $pdf->Ln(13);
            $pdf->Cell(70); // con la imagen y esto estamos a 60mm
            $pdf->SetLineWidth(.5);
            $pdf->Cell(0, 7, utf8_decode("Información"), 'B', 1, 'L', false);
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70, 5, "Colegio La Inmaculada-Marillac", 0, 0, 'L', false);
            $pdf->Cell(0, 7, utf8_decode($_POST["alumno"]),0,1, 'L', false); //Alumno
            $pdf->SetY($pdf->GetY()-2, false);
            $pdf->Cell(70, 5, "R7800929G", 0, 0, 'L', false);
            $pdf->SetY($pdf->GetY()+2, false);
            $pdf->Cell(0, 7, utf8_decode($rm->textoCurso($_POST["curso"])),0,1, 'L', false); //Curso
            $pdf->SetY($pdf->GetY()-4, false);
            $pdf->Cell(70, 5, utf8_decode("C/ García de Paredes 37"), 0, 0, 'L', false);
            $pdf->SetY($pdf->GetY()+4, false);
            $pdf->Cell(0, 7, utf8_decode($_POST["correo"]),0,1, 'L', false); //Email
            $pdf->SetY($pdf->GetY()-6, false);
            $pdf->Cell(70, 5, "28010 - Madrid", 0, 0, 'L', false);
            $pdf->SetY($pdf->GetY()+6, false);
            $pdf->Cell(0, 7, utf8_decode($_POST["telefono"]),0,1, 'L', false); //Telefono
            $pdf->SetY($pdf->GetY()-8, false);
            $pdf->Cell(0, 5, "Tlf: 91 445 35 34", 0, 1, 'L', false);
            $pdf->Cell(0, 5, "Fax: 91 591 28 34", 0, 1, 'L', false);
            //Libros reservados
            $pdf->Ln(10);
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(40,7,"ISBN",'B',0,'L',false);
            $pdf->Cell(90,7,utf8_decode("Título"),'B',0,'L',false);
            $pdf->Cell(60,7,"Editorial",'B',1,'L',false);
            // Color and font restoration
            $pdf->SetTextColor(0);
            $pdf->SetFont('Arial','',10);
            $w = array(40, 90, 60);
            foreach($_POST["elegidos"] as $isbn)
            {
                $lib = $lm->compruebaExiste($isbn);
                $pdf->Cell($w[0],6,$isbn,0,0,'L',false);
                $pdf->Cell($w[1],6,utf8_decode($lib->titulo),0,0,'L',false);
                $pdf->Cell($w[2],6,utf8_decode($libro->editorial),0,1,'L',false);
            }
            
            $mensaje="Muchas gracias por realizar la reserva.<br> En el documento adjunto encontrará su reserva.<br><br>Un saludo.";
            
            /******* MAILHELPER ******/
    		$mail = new PHPMailer;
    
    		//Set PHPMailer to use SMTP.
    		$mail->isSMTP();
    		$mail->SMTPDebug = 0; //2
    		$mail->Debugoutput = ''; //html
    
    		//Set SMTP host name                         
    		$mail->Host = "smtp.gmail.com";
    		//Set this to true if SMTP host requires authentication to send email
    		$mail->SMTPAuth = true;                         
    		//Provide username and password    
    		$mail->Username = "libros@lainmaculada-marillac.com";                
    		$mail->Password = "E14h05c78";
    		//If SMTP requires TLS encryption then set it
    		$mail->SMTPSecure = "tls";
    		//Set TCP port to connect to
    		$mail->Port = 587;
    		$mail->setFrom("libros@lainmaculada-marillac.com", "Reserva de Libros");
    		//$mail>addReplyTo("libros@lainmaculada-marillac.com","Reserva de Libros");
    		$mail->addAddress("david.henche@lainmaculada-marillac.com", "David Henche");
    		//$mail->addAddress($correo);
    		//$mail->addBCC("direccionmarillac@telefonica.net");
    		$mail->isHTML(true);
    		$mail->Subject = "Numero de reserva: ".$asunto;
    		$mail->Body = $mensaje;
    		$mail->CharSet = "UTF8";
    		$enviar=$pdf->Output('S', '');
//No se adjunta el pdf!!!
    		$mail->addStringAttachment($enviar, 'Reserva'.$asunto.'.pdf', 'base64', 'application/pdf');
    		if(!$mail->send()) {
    			$message = 'Error al enviar la reserva de libros\n';
    			$message .= 'Env&iacute;e un correo a la direcci&oacute;n david.henche@lainmaculada-marillac.com con el n&uacute;mero de reserva ';
    			$message .= $num_reserva;
    			$message .= "Mailer Error: " . $mail->ErrorInfo;
    			die ($message);
    			foreach ($_POST["elegidos"] as $isbn) {
                    $loslibros[] = $lm->compruebaExiste($isbn);;
                }
    			$this->view("reservas",array(
                    "loslibros"=>$loslibros,
                    "cursoE"=>$_POST["curso"],
                    "errorR"=>"Ocurrió un error al enviar el correo electrónico a ".$_POST["correo"]
                    ));
    		} else {
    		    $this->view("reservas",array(
                    "correctoR"=>"Se ha creado la reserva correctamente, se ha enviado un correo electrónico a ".$_POST["correo"]
                ));
    		}
        } else {  //If de si existe ya la reserva
            foreach ($_POST["elegidos"] as $isbn) {
                $loslibros[] = $lm->compruebaExiste($isbn);;
            }
            $this->view("reservas",array(
            "loslibros"=>$loslibros,  
            "cursoE"=>$_POST["curso"],
            "errorR"=>"Ya se ha realizado una reserva este año para el alumno ".$_POST["alumno"]
            ));
        }
    }
}
?>