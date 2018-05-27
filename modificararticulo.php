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
            <?php

            $conexion = conectar();
            if(verpermiso($_SESSION['nick'],$conexion) == 3 || verpermiso($_SESSION['nick'],$conexion) == 1){
                if(isset($_POST['cod'])) {

                    $cod = $_POST['cod'];
                    $desc = $_POST['descripcion'];
                    $precio = $_POST['precio'];
                    $cat = $_POST['categoria'];
                    $nombre = $_POST['nombre'];


                    $sql = "SELECT * FROM articulos where cod_articulo =" . $_POST['cod'];

                    $ro = $conexion->query($sql);
                    $datos = $ro->fetch_assoc();
                    echo "<h1>Modificando el articulo con codigo '" . $datos['cod_articulo'] . "'</h1>";
                    ?>
                    <table class="table text-center">
                        <form action="guardarcambiosprod.php" method="post" enctype="multipart/form-data">
                            <td>Nombre:</td>
                            <td><input type="text" id="nombre" name="nombre" value="<?= $datos['nombre_articulo'] ?>">
                            </td>
                            <tr></tr>
                            <td>Descripcion:</td>
                            <td><input type="text" id="descripcion" name="descripcion"
                                       value="<?= $datos['descripcion_articulo'] ?>"></td>
                            <tr></tr>
                            <td>Precio:</td>
                            <td><input type="number" id="precio" name="precio" step=".01"
                                       value="<?= $datos['precio'] ?>"></td>
                            <tr></tr>

                            <?php
                            $sql = "SELECT categoria FROM categorias";
                            $r = $conexion->query($sql);
                            $te = array();
                            while($dat = $r->fetch_row()){
                                 $te[] = $dat[0];

                            }

                            ?>

                            <td>Categoria:</td>
                            <td><select id="categoria" name="categoria">
                                    <?php
                                    for($i=0;$i<count($te);$i++){
                                        echo "<option value='$te[$i]'>$te[$i]</option>";
                                    }



                                    ?>
                                </select>
                            </td>
                            <tr></tr>
                            <td>Añadir Imagen:</td>
                            <td><input type="file" name="imagen" id="imagen"></td>
                            <tr></tr>
                            <input type="hidden" name="cod" id="cod" value="<?= $datos['cod_articulo'] ?>">
                            <td><input type="submit" name="modificar" id="modificar" value="Actualizar"></td>
                        </form>

                    </table>


                    <?php
                }else{
                    ?>

            <table class="table text-center">
                <form action="nuevoproducto.php" method="post" enctype="multipart/form-data">
                    <td>Nombre:</td>
                    <td><input type="text" id="nombre" name="nombre" required>
                    </td>
                    <tr></tr>
                    <td>Descripcion:</td>
                    <td><input type="text" id="descripcion" name="descripcion"></td>
                    <tr></tr>
                    <td>Precio:</td>
                    <td><input type="number" id="precio" name="precio" step=".01" required></td>
                    <tr></tr>

                    <?php
                    $sql = "SELECT categoria FROM categorias";
                    $r = $conexion->query($sql);
                    $te = array();
                    while($dat = $r->fetch_row()){
                        $te[] = $dat[0];

                    }

                    ?>

                    <td>Categoria:</td>
                    <td><select id="categoria" name="categoria">
                            <?php
                            for($i=0;$i<count($te);$i++){
                                echo "<option value='$te[$i]'>$te[$i]</option>";
                            }



                            ?>
                        </select>
                    </td>
                    <tr></tr>
                    <td>Imagen via Archivo:</td>
                    <td><input type="file" name="imagen" id="imagen"></td>
                    <tr></tr>
                    <td>Imagen via URL: </td><td><input type="text" id="url" name="url" required></td>
                    <tr></tr>
                    <td><input type="submit" name="modificar" id="modificar" value="Actualizar"></td>
                </form>

            </table>
            <?php

                }
            }else{
                echo "Solo los administradores y empleados pueden acceder a esta página";
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