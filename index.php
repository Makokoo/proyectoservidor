<?php
include_once 'funciones_tienda.php';
include_once 'Carrito.php';
session_start();
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale=1">

    <title>Homepage</title>
    <link rel="icon" href="../favicon.png" type="image/png">
    <link rel="shortcut icon" href="../favicon.ico" type="img/x-icon">

    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,800italic,700italic,600italic,400italic,300italic,800,700,600' rel='stylesheet' type='text/css'>

    <link href="../css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <link href="../css/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="../css/responsive.css" rel="stylesheet" type="text/css">
    <link href="../css/magnific-popup.css" rel="stylesheet" type="text/css">

</head>

<body>


<nav class="main-nav-outer" id="test">
    <!--main-nav-start-->
    <div class="container">
        <ul class="main-nav">
            <li><a href="../home.php">INICIO</a></li>
            <li><a href="../tournaments.php">TORNEOS</a></li>
            <li><a href="quienessomos.php">TIENDA</a></li>
            <li class="small-logo"><a href="../home.php"><img src="../img/small-logo.png" alt=""></a></li>
            <li><a href="quienessomos.php">QUIENES SOMOS</a></li>

            <?php
            if(!isset($_SESSION['usuario'])) {
                ?>

                <li><a href='../login.php'>Iniciar Sesión</a></li>

                <?php
            }else{
                ?>

                <li><a href="../profile.php">Mi Perfil</a></li>
                <li><a href="../logout.php">Cerrar Sesión</a></li>

                <?php
            }
            ?>
        </ul>

        <a class="res-nav_click" href="#"><i class="fa fa-bars"></i></a>
    </div>
</nav>
<!--main-nav-end-->

















        <div class="col col-lg-8 style='float: none;margin-left: auto;margin-right: auto;'">
        <?php

        if(isset($_GET['categoria'])){
            if($_GET['categoria'] == 'all'){
                header('location:ver_productos.php');
            }else {
                echo verproductosporcategoria($_GET['categoria']);
            }
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
                    $conexion = conectar_tienda();
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
            $conexion = conectar_tienda();
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

<footer class="footer">
    <div class="container">
        <div class="footer-logo"><a href="#"><img src="img/footer-logo.png" alt=""></a></div>
        <span class="copyright">&copy; RandomTournament. All Rights Reserved</span>
        <div class="credits">
            <!--
      All the links in the footer should remain intact.
      You can delete the links only if you purchased the pro version.
      Licensing information: https://bootstrapmade.com/license/
      Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Knight
    -->
            RandomTournament by Sergio Molina
        </div>
    </div>
</footer>




</body>

</html>
