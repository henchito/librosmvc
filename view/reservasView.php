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
            <h1 class="col-lg-6">Nueva reserva</h1>
            <img src="./images/logocolegio.jpg" width="200px"></img>
        </header>
        <?php if(isset($correctoR)) { ?>
            <div class="row">
                <div class="alert alert-success" role="alert" style="clear:left;top-margin:25px">
                    <strong>Éxito!</strong> <?php echo $correctoR; ?>
                </div>
                <a href="http://www.lainmaculada-marillac.com" target="_self" class="btn btn-lg btn-info">
                        Terminar
                </a>
            </div>
        <?php } else { ?>
        <div class="row">
            <form action="<?php echo $helper->url("reservas","librosCurso"); ?>" method="post" class="col-lg-12" id="libroForm">
                <?php if(isset($cursos) && count($cursos)>=1) { ?>
                    <select name="curso" class="col-lg-5" required style="margin-top: 10px;">
                        <?php if (!isset($cursoE)) { ?>
                            <option value="" default selected>Seleccione el curso</option>
                        <?php }
                        foreach ($cursos as $curso) { ?>
                            <option value="<?php echo $curso->id; ?>"
                                <?php 
                                    if (isset($cursoE) and $cursoE==$curso->id)
                                        echo " selected "
                                ?>
                            >
                                <?php echo $curso->numero." ".$curso->ciclo ?>
                            </option>
                        <?php } ?>
                    </select>
                <?php } ?>
                <div class="col-lg-1"></div>
                <button class="btn-res btn btn-success col-lg-1" type="submit">Seleccionar</button>
                <div class="col-lg-5"></div>
            </form>
        </div>
        <?php  if(isset($loslibros) and (count($loslibros)>0)) { ?>
        <div class=row>
            <div class="col-md-12">
                <table class="table table-condensed">
                <thead>
                  <tr>
                    <th>&nbsp;</th>  
                    <th>Título</th>
                    <th>Editorial</th>
                    <th>Edicion</th>
                    <th>Proyecto</th>
                  </tr>
                </thead>
                <tbody>
                    <?php 
                       //recorremos el array de objetos y obtenemos el valor de las propiedades
                        foreach($loslibros as $lib) { 
                    ?>  
                            <tr>
                                <td><input type="checkbox" name="elegidos[]" value="<?php echo $lib->id; ?>" checked form="alumnoForm"/></td>
                                <td><?php echo $lib->titulo; ?></td>
                                <td><?php echo $lib->editorial; ?></td>
                                <td><?php echo $lib->edicion; ?></td>
                                <td><?php echo $lib->proyecto; ?></td>
                            </tr>
                    <?php   } ?>
                </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <form action="<?php echo $helper->url("reservas","crear"); ?>" method="post" class="col-lg-12" id="alumnoForm">
                <div class="row">
                    <input type="hidden" name="curso" value="<?php echo $cursoE; ?>" />
                </div>
                <div class="row">
                    <input type="text" name="alumno" placeholder="Nombre del alumno" required class="col-lg-5" />
                </div>
                <div class="row">
                    <input type="text" name="telefono" placeholder="Teléfono" required maxlength="9" pattern="[0-9]{9}" class="col-lg-3" />
                </div>
                <div class="row">
                    <input type="email" name="correo" placeholder="Correo electrónico" required class="col-lg-5"/>
                </div>
                <div class="row">
                    <button class="btn btn-success row" type="submit">Reservar</button>
                </div>
            </form>
        </div>
            <?php if(isset($errorR)) { ?>
                    <div class="alert alert-danger sequita" role="alert" style="clear:left;top-margin:5px">
                        <strong>Error!</strong> <?php echo $errorR; ?>
                    </div>
            <?php } ?>
        <?php } // if ya se ha seleccionado el curso 
        } /*else de que se ha creado la reserva correctamente*/ ?> 
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