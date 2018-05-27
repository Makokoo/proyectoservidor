<?php
include_once 'funciones.php';
include_once 'Carrito.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tienda</title>
    <link href="bootstrap.css" rel="stylesheet" type="text/css">
</head>
<body>


<div class="container-fluid">
    <header class="modal-header">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
            <a class="navbar-brand" href="#">PROYECTO TIENDA</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">INICIO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">OFERTAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="quienessomos.php">QUIENES SOMOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">CONTACTO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?categoria=all">PRODUCTOS</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" method="get" action="busqueda.php">
                    <input class="form-control mr-sm-2" type="text" name="campobusqueda" id="campobusqueda" placeholder="Buscar" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
                </form>
            </div>
        </nav>
    </header>

    </br>


    <div class="row">
        <div class="col col-lg-2  border">

            <h1 class="h3">PRODUCTOS</h1>
            <a class="dropdown-item" href="index.php?categoria=all">Ver Todos</a>

            <?php
            $conexion = conectar();
            $sql = "SELECT distinct categoria from articulos";

            $r = $conexion->query($sql);

            while($d = $r->fetch_assoc()) {

                for ($i = 0; $i < count($d); $i++) {
                    echo "<a class='dropdown-item' href='index.php?categoria=".$d['categoria']."'>".strtoupper($d['categoria'])."</a>";
                }
            }
            ?>



        </div>

        <div class="col col-lg-8 style='float: none;margin-left: auto;margin-right: auto;'">

            <legend></legend>
            <!--
            <h1 class="h1 col-md-offset-5">Formulario de Solicitud</h1>
            -->

            <div class="panel panel-primary">
                <div class="panel-heading">Formulario de Registro</div>
                <form class="form-horizontal" action="registro.php" method="post">
                    <fieldset>

                        <!-- Form Name -->
                        <legend></legend>



                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">Nombre</label>
                            <div class="col-md-4">
                                <input name="nombre" id="nombre" type="text" placeholder="Insertar nombre"  class="form-control input-md">
                            </div>
                        </div>


                        <!-- Usuario -->

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">Nick</label>
                            <div class="col-md-4">
                                <input name="nick" id="nick" type="text" placeholder="Insertar nick"  class="form-control input-md">
                            </div>
                        </div>

                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="passwordinput">Contraseña</label>
                            <div class="col-md-4">
                                <input  name="pass" id="pass" type="password" placeholder="Insertar contraseña"  class="form-control input-md">
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton"></label>
                            <div class="col-md-4">
                                <input class="btn btn-default" type="submit" id="enviar" name="enviar" value="Enviar Solicitud">
                            </div>
                        </div>

                    </fieldset>
                </form>

            </div>
            <?php

            if(isset($_POST['enviar'])) {
                if ($_POST['nombre']!=="" && $_POST['nick']!=="" && $_POST['pass']!=="") {
                    //if (isset($_POST['nombre']) && isset($_POST['cif']) && isset($_POST['razon']) && isset($_POST['telf']) && isset($_POST['mail']) && isset($_POST['nick']) && isset($_POST['pass'])) {


                    $nombre = $_POST['nombre'];
                    $nick = $_POST['nick'];
                    $pass = $_POST['pass'];



                    if(existenick($nick)==false){

                        $conexion = conectar();

                        if($conexion) {

                            $sql = "INSERT INTO clientes(Nombre,nick,pass) VALUES ('$nombre','$nick','$pass')";

                            $conexion ->query($sql);

                            echo "<div class='form-group'><div class='col-md-4 col-md-offset-4'>";
                            echo "<p class='alert-success'>Se ha registrado correctamente.</p>";
                            $_SESSION['nick'] = $nick;
                            echo "<a href='index.php'>Haga click aquí para comenzar su compra</a>";
                            echo "</div></div>";

                        }
                    }else{
                        echo "<div class='form-group'><div class='col-md-4 col-md-offset-4'>";
                        echo "<p class='alert-danger'>Ya existe ese nick en la base de datos, pruebe otro.</p>";
                        echo "</div></div>";
                    }



                } else {
                    echo "<div class='form-group'><div class='col-md-4 col-md-offset-4'>";
                    echo "<p class='alert-danger'>Debe rellenar todos los campos del formulario</p>";
                    echo "</div></div>";
                }

            }
            ?>

        </div>

        <div class="col col-lg-2  border">
            <div class="col col-lg-12 bg"><h2>LOGIN</h2></div>

            <?php
            if(!isset($_SESSION['nick']) || $_SESSION['nick']=="invitado") {
                if (isset($_POST['nick']) && isset($_POST['pass'])) {
                    if (logincorrecto($_POST['nick'], $_POST['pass']) == true || isset($_SESSION['nick'])) {
                        if(!isset($_SESSION['nick'])) {
                            $_SESSION['nick'] = $_POST['nick'];
                        }
                        echo "<a href='cerrarsesion.php'>Cerrar Sesión</a></br>";
                        echo "<a href='verpedidos.php'>Ver Pedidos</a></br>";

                        if(!isset($_SESSION['carrito'])) {
                            echo "<a href='ver_carrito.php'>Ver Carrito(0)</a></br>";
                        }else{
                            $_SESSION["carrito"] = new Carrito($_SESSION['nick']);
                            echo "<a href='ver_carrito.php'>Ver Carrito";
                            $prod = $_SESSION['carrito']->getproductos();
                            echo "(".count($prod).")";
                            echo "</a></br>";
                        }
                        echo "<a href='ver_perfil.php'>Ver Perfil</a></br>";
                        $conexion = conectar();
                        if(verpermiso($_SESSION['nick'],$conexion) == 3){
                            echo "<a href='gestion_clientes.php'>Gestionar Clientes</a></br>";
                        }
                        if(verpermiso($_SESSION['nick'],$conexion) == 1 || verpermiso($_SESSION['nick'],$conexion) == 3 ){
                            echo "<a href='gestion_articulos.php'>Gestionar Articulos</a></br>";
                            echo "<a href='gestion_pedidos.php'>Gestionar Pedidos</a></br>";
                            echo "<a href='ver_informes.php'>Ver Informes</a></br>";
                        }
                    }else{

                        echo "<b class='bg-danger'>Error, credenciales incorrectas</b>";
                        echo "<form action=\"index.php\" method=\"post\">
                    Usuario: <input type=\"text\" name=\"nick\" id=\"nick\">
                    Contraseña: <input type=\"password\" name=\"pass\" id=\"pass\">

                    <input type=\"submit\" name=\"login\" id=\"login\" value=\"Ingresar\" style=\"margin-top:10px\">
                </form>";
                    }
                } else {

                    ?>

                    <form action="index.php" method="post">
                        Usuario: <input type="text" name="nick" id="nick">
                        Contraseña: <input type="password" name="pass" id="pass">

                        <input type="submit" name="login" id="login" value="Ingresar" style="margin-top:10px">
                        <a href="registro.php">Registrarse</a>
                    </form>
                    <?php
                    if(isset($_SESSION['carrito'])){
                        echo "<a href='ver_carrito.php'>Ver Carrito</a></br>";
                    }
                }
            }else{
                echo "<a href='cerrarsesion.php'>Cerrar Sesión</a></br>";
                if($_SESSION['nick']!=="invitado") {
                    echo "<a href='verpedidos.php'>Ver Pedidos</a></br>";
                }
                if(!isset($_SESSION['carrito'])) {
                    echo "<a href='ver_carrito.php'>Ver Carrito</a></br>";
                }else{
                    echo "<a href='ver_carrito.php'>Ver Carrito";
                    $prod = $_SESSION['carrito']->getproductos();
                    echo "(".count($prod).")";
                    echo "</a></br>";
                }
                echo "<a href='ver_perfil.php'>Ver Perfil</a></br>";
                $conexion = conectar();
                if(verpermiso($_SESSION['nick'],$conexion) == 3){
                    echo "<a href='gestion_clientes.php'>Gestionar Clientes</a></br>";
                }
                if(verpermiso($_SESSION['nick'],$conexion) == 1 || verpermiso($_SESSION['nick'],$conexion) == 3 ){
                    echo "<a href='gestion_articulos.php'>Gestionar Articulos</a></br>";
                    echo "<a href='gestion_pedidos.php'>Gestionar Pedidos</a></br>";
                    echo "<a href='ver_informes.php'>Ver Informes</a></br>";
                }
            }
            ?>
            </br>
        </div>

    </div>

    <footer class="modal-footer">ProyectoTienda S.L. Copyright 2018</footer>
</div>









</body>
</html>