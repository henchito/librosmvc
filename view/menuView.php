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
            <h1>Menú</h1>
        </header>
        <div class="row">
            <div class="col-md-2">
                <a href="<?php echo $helper->url("facturas", "iniciar"); ?>" target="_blank" class="btn btn-lg btn-success">
                    Venta de libros
                </a>
            </div>
            <div class="col-md-2">
                <a href="<?php echo $helper->url("libros", "gestionar"); ?>" target="_blank" class="btn btn-lg btn-warning">
                    Gestionar libros
                </a>
            </div>
            <div class="col-md-2">
                <a href="<?php echo $helper->url("",""); ?>" target="_blank" class="btn btn-lg btn-info">
                    Informe de libros
                </a>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-2">
                <a href="<?php echo $helper->url("Usuarios", "salir"); ?>" target="_self" class="btn btn-lg btn-danger">
                    Cerrar sesión
                </a>
            </div>
        </div>
         <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                      <h3 class="panel-title">Crear Libro</h3>
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo $helper->url("libros","crear"); ?>" method="post" class="col-lg-12" id="libroForm">
                            <input type="text" name="id" class="col-lg-8" placeholder="ISBN" required maxlength="13"/>
                            <input type="text" name="titulo" class="col-lg-8" placeholder="Título" required/>
                            <input type="text" name="edicion" class="col-lg-8" placeholder="Edición" />
                            <input type="text" name="proyecto" class="col-lg-8" placeholder="Proyecto" />
                            <!-- Select para las editoriales -->
                            <?php if(isset($editoriales) && count($editoriales)>=1) { ?>
                                <select name="editorial" class="col-lg-8" required>
                                    <option value="" default selected>Editorial</option>
                                    <?php foreach ($editoriales as $editorial) { ?>
                                        <option value="<?php echo $editorial->id; ?>"><?php echo $editorial->id ?></option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                            <input type="number" name="precio" class="col-lg-8" placeholder="Precio" step="0.01" />
                            <select name="maxDescuento" class="col-lg-8" required>
                                <option value="" default selected>Tipo de libro</option>
                                <option value="5">2 años / Bachillerato / Lectura / Diccionario</option>
                                <option value="10">Infantil / EPO / ESO</option>
                            </select>
                            <!-- Checkbox para los cursos -->
                            <div class="col-lg-5">
                                <div class="list-group">
                            <?php if(isset($cursos) && count($cursos)>=1) { 
                                $i=0;
                                foreach ($cursos as $curso) { ?>
                                    <div class="list-group-item">
                                        <input type="checkbox" name="curso[]" class="" value="<?php echo $curso->id; ?>"/>
                            <?php
                                        echo $curso->numero.' '.$curso->ciclo;
                                        $i++;
                            ?>
                                        
                                    </div>
                            <?php 
                                    if ($i%10==0)
                                        echo "</div></div><div class='col-lg-6'><div class='list-group'>";
                                }
                            } ?>
                                </div>
                            </div>
                            
                           
                            <button class="btn btn-success" type="submit">Crear libro</button>
                        </form>
                        <?php if(isset($libritoC)) { ?>
                            <div class="alert alert-success sequita" role="alert" style="clear:left;top-margin:25px">
                                <strong>Exito!</strong> <?php echo $libritoC; ?>
                            </div>
                        <?php } 
                            if(isset($libritoI)) { ?>
                            <div class="alert alert-danger sequita" role="alert" style="clear:left;top-margin:5px">
                                <strong>Error!</strong> <?php echo $libritoI; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Crear editorial</h3>
                            </div>
                            <div class="panel-body">
                                <form action="<?php echo $helper->url("editoriales","crear"); ?>" method="post" class="col-lg-7" id="editorialForm">
                                        <input type="text" name="idEditorial" class="form-control" placeholder="Nombre de la editorial"/>
                                        <button class="btn btn-success" type="submit">Crear editorial</button>
                                </form>
                                <?php if(isset($editoC)) { ?>
                                    <div class="alert alert-success sequita" role="alert" style="clear:left;top-margin:25px">
                                        <strong>Éxito!</strong> <?php echo $editoC; ?>
                                    </div>
                                <?php } 
                                    if(isset($editoI)) { ?>
                                    <div class="alert alert-danger sequita" role="alert" style="clear:left;top-margin:5px">
                                        <strong>Error!</strong> <?php echo $editoI; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Este formulario sólo se debe mostrar a un administrador -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Crear usuario</h3>
                            </div>
                            <div class="panel-body">
                                <form action="<?php echo $helper->url("usaurios","crear"); ?>" method="post" class="col-lg-7" id="usuarioForm">
                                       <input type="text" name="idUsuario" class="form-control" placeholder="Nombre del usuario"/>
                                        <button class="btn btn-success" type="submit">Crear editorial</button>
                                </form>
                                <?php if(isset($editoC)) { ?>
                                    <div class="alert alert-success sequita" role="alert" style="clear:left;top-margin:25px">
                                        <strong>Éxito!</strong> <?php echo $editoC; ?>
                                    </div>
                                <?php } 
                                    if(isset($editoI)) { ?>
                                    <div class="alert alert-danger sequita" role="alert" style="clear:left;top-margin:5px">
                                        <strong>Error!</strong> <?php echo $editoI; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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