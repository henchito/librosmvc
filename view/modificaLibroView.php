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
                <!-- Hay que probar que esto funciona bien o sino controlar el evento del enter -->
                <form action="<?php echo $helper->url("libros","buscaLibro"); ?>" method="post"  id="buscaISBNForm">
                    <input type="text" name="isbn" class="form-control" placeholder="Escriba el isbn" autofocus required maxlength="13"/>
                </form>
            </div>
            <div class="col-lg-1">
                <button class="btn btn-success" type="submit" form="buscaISBNForm" style="margin-top: 0px;">Buscar</button>
            </div>
            <div class="col-lg-2">
                 <a href="<?php echo $helper->url("", ""); ?>" target="_self" class="btn btn-lg btn-info" style="margin-top: 0px;">
                    Búsqueda manual
                </a>
            </div>
        </div>
         <div class="col-lg-12">
            <h3>Libros</h3>
            <hr/>
        </div>
        <div class="row">
            <?php if(isset($libro)){ ?>
            <form action="<?php echo $helper->url("libros", "actualizaLibro"); ?>" method="post" id="cambiaLibro" class="col-lg-8">
                <div class="row">
                    <span class="col-lg-2">ISBN:</span> 
                    <input type="text" name="isbn" class="col-lg-8" maxlength="13" required value="<?php echo $libro->id ?>" readonly/>
                </div>
                <div class="row">
                    <span class="col-lg-2">Título:</span>
                    <input type="text" name="titulo" class="col-lg-8" required value="<?php echo $libro->titulo ?>"/>
                </div>
                <div class="row">
                    <span class="col-lg-2">Edición:</span>
                    <input type="text" name="edicion" class="col-lg-8" value="<?php echo $libro->edicion ?>"/>
                </div>
                <div class="row">
                    <span class="col-lg-2">Proyecto:</span>
                    <input type="text" name="proyecto" class="col-lg-8" value="<?php echo $libro->proyecto ?>"/>
                </div>
                <div class="row">
                    <div class="col-lg-2"></div>
                <!-- Select para las editoriales -->
                    <?php if(isset($editoriales) && count($editoriales)>=1) { ?>
                        <select name="editorial" class="col-lg-8" required>
                            <?php foreach ($editoriales as $editorial) { ?>
                                <option value="<?php echo $editorial->id; ?>" 
                                <?php if ($libro->editorial==$editorial->id) echo " selected"; ?>>
                                    <?php echo $editorial->id ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </div>
                <div class="row">
                    <span class="col-lg-2">Precio:</span>
                    <input type="number" name="precio" class="col-lg-8" step="0.01" value="<?php echo $libro->precio ?>" />
                </div>
                <div class="row">
                    <span class="col-lg-3">Activo para la venta:</span>
                    <input type="checkbox" name="activo" class="col-lg-6" value="<?php echo $libro->activo ?>"
                        <?php if ($libro->activo) echo "checked"; ?>
                    />
                </div>
                <div class="row">
                    <div class="col-lg-2"></div>
                    <select name="maxDescuento" class="col-lg-8" required>
                        <option value="5" <?php if($libro->maxDescuento==5) echo " selected";  ?> >
                            2 años / Bachillerato / Lectura / Diccionario
                        </option>
                        <option value="10" <?php if($libro->maxDescuento==10) echo " selected";  ?> >
                            Infantil / EPO / ESO
                        </option>
                    </select>
                </div>
                <div class="row">
                    <!-- Checkbox para los cursos -->
                    <div class="col-lg-5">
                        <div class="list-group">
                        <?php if(isset($cursos) && count($cursos)>=1) { 
                            $i=0;
                            foreach ($cursos as $curso) { ?>
                                <div class="list-group-item">
                                    <input type="checkbox" name="curso[]" class="" value="<?php echo $curso->id; ?>"
                                    <?php 
                                        foreach ($cursosL as $clb){
                                            if($clb->idcurso==$curso->id) echo " checked";  
                                        }
                                    ?>
                                    />
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
                </div>
                <button class="btn btn-success" type="submit">Cambiar libro</button>
            </form>
            <?php } 
                if(isset($mensaje)) { ?>
                    <div class="alert alert-success sequita" role="alert" style="clear:left;top-margin:25px">
                        <strong>Exito!</strong> <?php echo $mensaje; ?>
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