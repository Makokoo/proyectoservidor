<?php
include_once 'funciones.php';
include_once 'Carrito.php';
include_once 'Producto.php';
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

            if(isset($_SESSION['nick'])){

                function sacarcodigocliente($usuario, $conexion)
                {
                    $consulta = "SELECT cod_cliente from clientes WHERE nick LIKE '$usuario'";
                    $res = $conexion->query($consulta);
                    $numvend = $res->fetch_row();

                    return $numvend;
                }

                function sacarClave($conexion)
                {
                    $sql = "SELECT MAX(cod_pedido) FROM pedidos";
                    $res = $conexion->query($sql);
                    $clave = $res->fetch_row();

                    return $clave;
                }

                function sacarPrecioCompra($producto,$conexion){
                    $consulta = "SELECT precio FROM articulos WHERE cod_articulo LIKE '$producto'";
                    $res = $conexion->query($consulta);
                    $precio = $res->fetch_row();

                    return $precio;
                }

                function sacarnombreprod ($codigo,$conexion){
                    $consulta = "SELECT nombre_articulo FROM articulos WHERE cod_articulo=$codigo";
                    $res = $conexion->query($consulta);
                    $nombre = $res->fetch_row();

                    return $nombre;
                }

                function sacarimagen ($codigo,$conexion){
                    $consulta = "SELECT imagen FROM articulos WHERE cod_articulo=$codigo";
                    $res = $conexion->query($consulta);
                    $imagen = $res->fetch_row();

                    return $imagen;
                }

                $conexion = conectar();
                $carrito = $_SESSION['carrito'];
                $ultimaClave = sacarClave($conexion);
                $ultimaClave = $ultimaClave[0] + 1;
                $usuario = $_SESSION['nick'];


                $cod_cliente = sacarcodigocliente($usuario, $conexion);

                $productos = $carrito->getProductos();
                $cantidades = $carrito->getCantidades();
                $fecha = getdate();
                $ano = $fecha['year'];
                $mes = $fecha['mon'];
                $dia = $fecha['mday'];


                $insertpedido = "INSERT INTO pedidos (cod_pedido,cod_cliente,fecha,estado) VALUES ($ultimaClave,$cod_cliente[0],CURDATE(),'procesando')";

                $res = $conexion->query($insertpedido);

                $contador = 0;

                for ($i = 0; $i < count($productos); $i++) {
                    $contador++;
                    $precio = sacarPrecioCompra($productos[$i], $conexion);
                    $nombre = sacarnombreprod($productos[$i], $conexion);
                    $imagen = sacarimagen($productos[$i],$conexion);
                    $insert = "INSERT INTO lineas_pedidos (num_linea_pedido,cod_pedido,cod_articulo,cantidad,estado) VALUES ($contador, $ultimaClave,$productos[$i],$cantidades[$i],'pedido')";

                    $res = $conexion->query($insert);
                }

                $carrito->vaciarCarrito();
                echo "<div class='alert-success'>El pedido se ha registrado correctamente</div>";

            }else {
                echo "<header>OFERTAS ESTRELLA</header>";
                echo verproductosoferta();
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




