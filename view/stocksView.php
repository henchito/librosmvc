<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Venta de libros - La Inmaculada Marillac</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"/>
        <link href="./css/gral.css" rel="stylesheet" />
    </head>
    <body>
        <header class="page-header">
            <h2 class="col-lg-6">Control de stockaje</h2>
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
        
        <!-- Que se pueda seleccionar el stock por año para imprimir informes de ventas -->
        
        <div class="row">
            <form action="<?php echo $helper->url("stocks","librosEditorial"); ?>" method="post" class="col-lg-12" id="libroForm">
                <!-- Select para las editoriales -->
                <?php if(isset($editoriales) && count($editoriales)>=1) { ?>
                    <select name="editorial" class="col-lg-8" required>
                        <option value="" default selected>Editorial</option>
                        <?php foreach ($editoriales as $editorial) { ?>
                            <option value="<?php echo $editorial->id; ?>"><?php echo $editorial->id ?></option>
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
                    <th>ISBN</th>
                    <th>Título</th>
                    <th>Edicion</th>
                    <th>Proyecto</th>
                    <th>Recibidos</th>
                    <th>Vendidos</th>
                    <th>Devueltos</th>
                  </tr>
                </thead>
                <tbody>
                    <?php 
                       //recorremos el array de objetos y obtenemos el valor de las propiedades
                        foreach($loslibros as $lib) { 
                    ?>  
                            <tr>
                                <td><?php echo $lib->id; ?></td>
                                <td><?php echo $lib->titulo; ?></td>
                                <td><?php echo $lib->edicion; ?></td>
                                <td><?php echo $lib->proyecto; ?></td>
                                <td>
                                    <form action="<?php echo $helper->url("stocks","actualizaRecibidos"); ?>" method="post">
                                        <input type="number" name="recibidos" value="<?php echo $lib->recibidos ?>"/>
                                        <button class="btn-res btn btn-success col-lg-1" type="submit">Actualizar</button>
                                    </form>
                                </td>
                                <td><?php echo $lib->vendidos; ?></td>
                                <td><?php echo $lib->devueltos; ?></td>
                            </tr>
                    <?php   } ?>
                </tbody>
                </table>
            </div>
        </div>
        
        <!-- Hasta aquí puedo leeer!!!! -->
        <div class="row">
            <form action="<?php echo $helper->url("stocks","imprimir"); ?>" method="post" class="col-lg-12" id="alumnoForm">
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