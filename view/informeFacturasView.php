<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Venta de libros - Marillac</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"/>
        <link href="./css/gral.css" rel="stylesheet" />
    </head>
    <body>
        <header class="page-header">
            <h2>Modificación de libros</h2>
        </header>
        <div class="row">
            <div class="col-lg-5">
                <form action="<?php echo $helper->url("facturas","buscaFacturas"); ?>" method="post"  id="buscaFacturas">
                    <!-- Seleccion de fechas -->
                    Fecha de inicio:
                    <input type="date" name="fechaIni" min="2016-01-01" /><br/>
                    Fecha final:
                    <input type="date" name="fechaFin" max="<?php date("Y-m-d"); ?>" /><br/>
                    
                    <button class="btn btn-success" type="submit">Listado</button>
                </form>
            </div>
        </div>
        <?php if(isset($mensaje)) { ?>
                <div class="alert alert-danger sequita" role="alert" style="clear:left;top-margin:5px">
                    <strong>Error!</strong> <?php echo $mensaje; ?>
                </div>
        <?php } ?>
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