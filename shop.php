<?php
include_once 'funciones_tienda.php';
include_once 'Carrito.php';
include_once '../funciones.php';
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


<nav class="main-nav-outer">
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




<div class="container">
    <section class="main-section">

        <div class="row">
            <div class="col-lg-6 col-sm-7 wow fadeInLeft">


    <?php

    if(isset($_GET['categoria'])){
        if($_GET['categoria'] == 'all'){
            header('location:ver_productos.php');
        }else {
            //echo verproductosporcategoria($_GET['categoria']);
        }
    }else {
        echo "<header>OFERTAS ESTRELLA</header>";
        echo verproductosoferta();

    }
    ?>
            </div>
        </div>
    </section>
</div>




<footer class="footer">
    <div class="container">
        <div class="footer-logo"><a href="#"><img src="../img/footer-logo.png" alt=""></a></div>
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
