<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Reserva de libros - La Inmaculada Marillac</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"/>
        <link href="./css/gral.css" rel="stylesheet" />
    </head>
    <body>
        <header class="page-header">
            <h1 class="col-lg-6">Busqueda de Libro</h1>
            <img src="./images/logocolegio.jpg" width="200px"></img>
        </header>
        <div class="row">
            <form action="<?php echo $helper->url("devoluciones","buscaPorTitulo"); ?>" method="post" class="col-lg-12" id="libroForm">
                <input type="text" name="titulo" class="col-lg-5" placeholder="Título" required/>
                <div class="col-lg-1"></div>
                <button class="btn-res btn btn-success col-lg-1" type="submit">Seleccionar</button>
                <div class="col-lg-5"></div>
            </form>
        </div>
        <?php  if(isset($loslibros) and (count($loslibros)>0)) { ?>
        <div class="row">
            <div class="col-md-12">
                <form action="<?php echo $helper->url("devoluciones","anadeLibro"); ?>" method="post" class="col-lg-12" >
                <table class="table table-condensed">
                <thead>
                  <tr>
                    <th>&nbsp;</th>  
                    <th>Título</th>
                    <th>Editorial</th>
                    <th>Edicion</th>
                    <th>Proyecto</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                    <?php 
                       //recorremos el array de objetos y obtenemos el valor de las propiedades
                        foreach($loslibros as $lib) { 
                    ?>  
                            <tr>
                                <td><input type="radio" name="isbn" value="<?php echo $lib->id; ?>" checked /></td>
                                <td><?php echo $lib->titulo; ?></td>
                                <td><?php echo $lib->editorial; ?></td>
                                <td><?php echo $lib->edicion; ?></td>
                                <td><?php echo $lib->proyecto; ?></td>
                                <td><button class="btn btn-info" type="submit">Añadir</button></td>
                            </tr>
                    <?php   } ?>
                </tbody>
                </table>
                </form>
            </div>
        </div>
        <?php } /* if hay libros que mostrar */ ?>
        <div class="row">
            <?php if(isset($errorL)) { ?>
                    <div class="alert alert-danger sequita" role="alert" style="clear:left;top-margin:5px">
                        <strong>Error!</strong> <?php echo $errorL; ?>
                    </div>
            <?php } ?>
        </div>
        <br/>
        <footer class="col-lg-12">
            <hr/>
           WebApp Reserva de libros - David Henche - <a href="maito:david.henche@lainmaculada-marillac.com">david.henche@lainmaculada-marillac.com</a> - Copyright &copy; <?php echo  date("Y"); ?>
        </footer>
        
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="./js/sequita.js"></script>
    </body>
</html>