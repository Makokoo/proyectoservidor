<?php
include_once 'funciones.php';
include_once 'Carrito.php';
include_once '../funciones.php';
include_once '../header.php';


?>

    <div class="container">
        

        <div class="col col-lg-12">
            <br><br>
            <div class="form">

                <form action="busqueda.php" method="get" role="form" class="contactForm">
                    <div class="form-group">
                        <input type="text" name="campobusqueda" class="form-control input-text" id="campobusqueda" required placeholder="Realizar búsqueda" />
                        <div class="validation"></div>
                    </div>
                                        <div class="text-center"><button type="submit" class="input-btn">Buscar</button></div>
                </form>
            </div>
        <?php

        if(isset($_GET['categoria'])){
            if($_GET['categoria'] == 'all'){
                header('location:ver_productos.php');
            }else {
                echo verproductosporcategoria($_GET['categoria']);
            }
        }else {
            echo "<br><br>";
            echo "<h2>OFERTAS ESTRELLA</h2>";
            echo "<div class='col-md-12' style='margin-left: 15px'>";
            echo verproductosoferta();
            echo "</div>";
        }
        ?>
    </div>
</div>

        <?php
        include_once '../footer.php';