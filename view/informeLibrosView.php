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
            <h2>Informe de libros</h2>
        </header>
        <div class="row">
            <div class="col-lg-5">
                <!-- Hay que probar que esto funciona bien o sino controlar el evento del enter -->
                <form action="<?php echo $helper->url("libros","librosDeEditorial"); ?>" method="post"  id="buscaEditorial">
                    <!-- Select para las editoriales -->
                    <?php if(isset($editoriales) && count($editoriales)>=1) { ?>
                        <select name="editorial" class="col-lg-8" required>
                            <option value="" default selected>Editorial</option>
                            <?php foreach ($editoriales as $editorial) { ?>
                                <option value="<?php echo $editorial->id; ?>"><?php echo $editorial->id ?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                    <button class="btn btn-success" type="submit">Listado</button>
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