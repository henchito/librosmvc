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
            <h2>Venta de libros</h2>
        </header>
        <div class="row">
            <div class="col-lg-5">
                <!-- Hay que probar que esto funciona bien o sino controlar el evento del enter -->
                <form action="<?php echo $helper->url("devoluciones","anadeLibro"); ?>" method="post"  id="buscaISBNForm">
                    <input type="text" name="isbn" class="form-control" placeholder="Escriba el isbn" autofocus required maxlength="13"/>
                </form>
            </div>
            <div class="col-lg-1">
                <button class="btn btn-success" type="submit" form="buscaISBNForm" style="margin-top: 0px;">Buscar</button>
            </div>
            <div class="col-lg-2">
                 <a href="<?php echo $helper->url("devoluciones", "inicioBuscaManual"); ?>" target="_self" class="btn btn-lg btn-info" style="margin-top: 0px;">
                    Búsqueda manual
                </a>
            </div>
        </div>
        <div class="row">
            <?php if(isset($errorL)) { ?>
                    <div class="alert alert-danger sequita" role="alert" style="clear:left;top-margin:5px">
                        <strong>Error!</strong> <?php echo $errorL; ?>
                    </div>
            <?php } ?>
        </div>
         <div class="col-lg-7">
            <h3>Devolución</h3>
            <hr/>
        </div>
        <div class=row>
            <div class="col-md-12">
                <table class="table table-condensed">
                <thead>
                  <tr>
                    <th>Título</th>
                    <th>Editorial</th>
                    <th>Precio</th>
                    <th>Clase</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                    <?php 
                        if(isset($incluidos) and (count($incluidos)>0)) { 

                            //recorremos el array de objetos y obtenemos el valor de las propiedades
                            foreach($incluidos as $lib) { 
                    ?>  
                            <tr>
                                <td><?php echo $lib->titulo; ?></td>
                                <td><?php echo $lib->editorial; ?></td>
                                <td><?php echo $lib->precio*-1; ?></td>
                                <td>
                                    <?php 
                                        if ($lib->maxDescuento==5)
                                            echo "N";
                                        elseif ($lib->maxDescuento==10)
                                            echo "R";
                                        else
                                            echo "O";
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo $helper->url("devoluciones","borrarLibro"); ?>&isbn=<?php echo $lib->id; ?>" class="btn btn-danger">Borrar</a>
                                </td>
                            </tr>
                    <?php   } ?>
                    <?php } else { ?>
                            <tr>
                                <td> - - </td><td></td><td></td><td></td><td></td>
                            </tr>
                    <?php } ?>
                    <!-- Final de la tabla con el total -->
                    <?php if(isset($n)) { ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            Descuento 5% (N)
                        </td>
                        <td><?php echo number_format($n*0.05, 2); ?></td>
                        <td colspan="2"></td>
                    </tr>
                    <?php 
                        } 
                        if(isset($r)) { 
                    ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            Descuento 10% (R)
                        </td>
                        <td><?php echo number_format($r*0.1, 2); ?></td>
                        <td colspan="2"></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th colspan="2" style="text-align: right;">
                            TOTAL
                        </th>
                        <th><?php echo number_format($total-number_format($n*0.05,2)-number_format($r*0.1,2), 2); ?></th>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-7">
            <h4>Cliente</h4>
            <hr/>
            <div class="col-lg-4">
                <form action="<?php echo $helper->url("clientes","buscaClienteDevolucion"); ?>" method="post" class="col-lg-12" id="nifCliente">
                        <input type="text" name="nif" class="form-control col-lg-8" placeholder="NIF" pattern="[A-Za-z0-9]{9}" required
                            <?php if (isset($nifC)) echo "value='".$nifC."'"; ?>
                        />
                        <button class="btn btn-success" type="submit">Buscar</button>
                </form>
            </div>
            <div class="col-lg-8">
                <!-- Habria que intentar hacer una función que cuando se teclee un nif, busque automáticamente el cliente AJAX!! -->
                <form action="<?php echo $helper->url("devoluciones","crearFactura"); ?>" method="post" class="col-lg-12" id="facturaTicket">
                        Número de factura
                        <input type="number" name="numero" class="form-control" placeholder="Número de factura" required 
                            <?php 
                                if(isset($numFact)) 
                                    echo " value='".$numFact."'"; 
                                /*if(isset($activado) and !$activado)
                                    echo "readonly";*/
                            ?> 
                        />
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre y apellidos" required
                            <?php 
                                if(isset($activado) and $activado) 
                                    echo " readonly";
                                if(isset($nombreC))
                                    echo " value='".$nombreC."'";
                            ?>
                        />
                        <input type="text" name="direccion" class="form-control" placeholder="Domicilio" required
                            <?php 
                                if(isset($activado) and $activado) 
                                    echo " readonly"; 
                                if(isset($direccionC))
                                    echo " value='".$direccionC."'";
                            ?>
                        />
                        <div class="row">
                            <div class="col-lg-2">Descuento: </div>
                            <div class="col-lg-2"></div>
                            <?php if (isset($n)) { ?>
                                <div class="col-lg-2">5 %</div>
                                <input type="checkbox" name="descN" value="5" class="col-lg-2" checked />
                            <?php } if (isset($r)) { ?>
                                <div class="col-lg-2">10 %</div>
                                <input type="checkbox" name="descR" value="10" class="col-lg-2" checked />
                            <?php } ?>
                        </div>
                        <div class="row">
                            <button class="btn btn-success" type="submit">Facturar</button>
                        </div>
                </form>
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