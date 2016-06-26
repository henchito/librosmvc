<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Venta de libros - Marillac</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"/>
        <link href="./css/signin.css" rel="stylesheet" />
    </head>
    <body>
        <div class="container">
            <form class="form-signin" action="<?php echo $helper->url("usuarios","nuevaPassword"); ?>" method="post" onSubmit="validaPss()" id="formContra">
                <h2 class="form-signin-heading">Elija su contraseña</h2>
                <input type="hidden" name="idusuario" value="<?php echo $elnuevo;?>"/>
                <input type="password" id="inputP1" name="password1" class="form-control" placeholder="Elija una contraseña" required autofocus>
                
                <input type="password" id="inputP2" name="password2" class="form-control" placeholder="Introduzca de nuevo su contraseña" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Crear contraseña</button>
            </form>
             <?php if(isset($errorLogin)) { ?>
                <div class="alert alert-danger sequita" role="alert" style="clear:left;top-margin:5px">
                    <strong>Error!</strong> <?php echo $errorLogin; ?>
                </div>
            <?php } ?>
        </div>
        <footer class="col-lg-12">
            <hr/>
           WebApp Venta de libros - David Henche - <a href="maito:david.henche@lainmaculada-marillac.com">david.henche@lainmaculada-marillac.com</a> - Copyright &copy; <?php echo  date("Y"); ?>
        </footer>
        
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="./js/sequita.js"></script>
        <script>
            function validaPss(){
                var p1 = document.getElementById("inputP1").value;
                var p2 = document.getElementById("inputP2").value;
                var espacios = false;
                var cont = 0;
         
                while (!espacios && (cont < p1.length)) {
                    if (p1.charAt(cont) == " ")
                        espacios = true;
                    cont++;
                }
         
                if (espacios) {
                    alert ("La contraseña no puede contener espacios en blanco");
                    return false;
                }
                if (p1 != p2) {
                  alert("Las passwords deben de coincidir");
                  return false;
                } else {
                  document.getElementById("formContra").submit();
                  return true; 
                }
            }
        </script>
    </body>
</html>