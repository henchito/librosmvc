<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Ejemplo PHP MySQLi POO MVC</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <style>
            input{
                margin-top:5px;
                margin-bottom:5px;
            }
            .right{
                float:right;
            }
        </style>
    </head>
    <body>
        <a href="<?php echo $helper->url("libros", "precrear"); ?>" target="_self">
            Crear libro
        </a>
        
        <a href="<?php echo $helper->url("libros", "gestionar"); ?>" target="_self">
            Gestionar libros
        </a>
        <!-- 
            A lo mejor en vez de crear se puede hacer que sea Gestionar libros 
            En ese caso -- $helper->url("libros", "index");
        -->
        
        <a href="<?php echo $helper->url("facturas", "iniciar"); ?>" target="_self">
            Venta de libros
        </a>
        
        <a href="<?php echo $helper->url("",""); ?>" target="_self">
            Informe de libros
        </a>
        <!-- REVISAR onclick para hacer que aparezca el formulario -->
        <a href="#" target="_self" onclick(document->getElementById("editorialForm")->style="visibility=visible")> 
            Crear editorial
        </a>
        <form action="<?php echo $helper->url("editoriales","crear"); ?>" method="post" class="col-lg-5" id="editorialForm" style="visibility=hidden">
            Nombre: <input type="text" name="id" class="form-control"/>
            <input type="submit" value="enviar" class="btn btn-success"/>
        </form>
        
        <a href="<?php echo $helper->url("Usuarios", "salir"); ?>" target="_self">
            Cerrar sesión
        </a>
        
        <footer class="col-lg-12">
            <hr/>
           Ejemplo PHP MySQLi POO MVC - Víctor Robles - <a href="http://victorroblesweb.es">victorroblesweb.es</a> - Copyright &copy; <?php echo  date("Y"); ?>
        </footer>
    </body>
</html>