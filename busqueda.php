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


            <?php

            if(isset($_GET['campobusqueda'])){
                echo verproductosporbusqueda($_GET['campobusqueda']);
            }else {
                echo verproductosoferta();
            }
            ?>
        </div>
    </div>
    </div>




<?php
include_once '../footer.php';