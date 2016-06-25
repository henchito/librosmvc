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
            <form class="form-signin" action="<?php echo $helper->url("usuarios","comprobar"); ?>" method="post">
                <h2 class="form-signin-heading">Inicie sesión</h2>
                <label for="inputNombre" class="sr-only">Usuario</label>
                <input type="text" id="inputNombre" name="id" class="form-control" placeholder="Nombre de usuario" required autofocus>
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Contraseña">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar sesión</button>
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
    </body>
</html>