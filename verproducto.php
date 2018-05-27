<?php
include_once 'funciones.php';
include_once 'Carrito.php';
include_once '../funciones.php';
include_once '../header.php';

header("Content-Type: text/html;charset=utf-8");
if(!isset($_POST['message'])) {
    ?>


    <div class="container">
    <div class="col col-lg-8 style='float: none;margin-left: auto;margin-right: auto;'">
    <?php

    if (isset($_GET['codarticulo'])) {
        //echo verproducto($_GET['codarticulo']);
        $id_articulo = $_GET['codarticulo'];
        $conexion = conectar();
        $acentos = $conexion->query("SET NAMES 'utf8'");
        $datos_articulo = sacardatoarticulo($_GET['codarticulo'], $conexion);
        $valoraciones = getValoraciones($_GET['codarticulo']);
        $nombre_fichero = "imagenes/$id_articulo.png";
        $imagen = "";
        if (file_exists($nombre_fichero)) {
            $imagen="..admin/$nombre_fichero";
        } else {
            $imagen=$datos_articulo['imagen'];
        }

        ?>


        <!-- Page Content -->
        <div class="container">
            <br><br>

            <div class="row">

                <div class="col-md-12">

                    <div class="thumbnail">
                        <img class="img-responsive" style="max-height: 300px" src="<?=$imagen?>" alt="">
                        <div class="caption-full">
                            <h2 class="text-left"><?= $datos_articulo['nombre_articulo'] ." - ". $datos_articulo['precio']."€"?></h2>
                            <h3><p><?= $datos_articulo['descripcion_articulo'] ?></p></h3>
                        </div>
                        <br>
                        <p>
                        <?php
                        if($datos_articulo['stock'] > 0){
                            echo "<label style='color:green'>En stock</label><label> - Entrega en 2/3 días laborales</label>";
                        }else{
                            echo "<label style='color:darkred'>No disponible</label>";
                        }
                        ?>
                        </p>
                        <div class="ratings" style="margin-top: 15px">
                            <p class="pull-right"><?= count($valoraciones) . " reviews" ?></p>
                            <p>
                                <?php
                                $valoracionmedia = getValoracionMedia($_GET['codarticulo']);
                                $contador = 0;
                                echo "Valoración media: ";
                                for ($i = 0; $i < $valoracionmedia; $i++) {
                                    echo "<span class=\"fa fa-star\"></span>";
                                    $contador++;
                                }

                                if ($contador < 5) {
                                    for ($i = $contador; $i < 5; $i++) {
                                        echo "<span class=\"fa fa-star-o\"></span>";
                                    }
                                }
                                //echo $valoracionmedia;
                                //echo "      " . round($valoracionmedia, 0) . " estrellas";
                                ?>


                            </p>
                        </div>
                    </div>

                    <div class="well">

                        <?php

                        for ($i = 0;
                        $i < count($valoraciones);
                        $i++){

                        $dato = $valoraciones[$i];
                        $nombre_usuario = getNombreId($dato['id_usuario']);
                        $contador = 0;
                        $idusuario = $dato['id_usuario'];
                        $datos_usuario = getDatosUsuario($nombre_usuario);
                        $avatar = "../img/default-avatar.png";

                        if (!$datos_usuario['avatar'] == null || !$datos_usuario['avatar'] == "") {
                            $avatar = "../avatar/" . $datos_usuario['id_usuario'] . ".png";
                        }

                        echo "<div class=\"row\">
                                        <div class=\"col-md-12\"><img style='max-height: 20px; margin-right: 15px;' src='$avatar'>$nombre_usuario&nbsp;-&nbsp;";

                        for ($j = 0; $j < $dato['valoracion']; $j++) {
                            echo "<span class=\"fa fa-star\"></span>";
                            $contador++;
                        }

                        if ($contador < 5) {
                            for ($j = $contador; $j < 5; $j++) {

                                echo "<span class=\"fa fa-star-o\"></span>";
                            }
                        }
                        ?>

                        <p><?= $dato['comentario'] ?></p>
                    </div>
                </div>

                <hr>


                <?php
                }
                if (isset($_SESSION['usuario'])) {
                    echo "<div class=\"row clearfix\">
    <div class=\"col-md-12 column\">
      
        <div class=\"panel-heading\">
          NUEVA REVIEW
        </div>
        
                 
<form action=\"verproducto.php\" method=\"post\" role=\"form\" class=\"contactForm\">
							<div class=\"form-group\">
								<textarea class=\"form-control input-text text-area\" name=\"message\" id=\"message\" required ></textarea>
							    Valoración: <input type='number' min='0' max='5' id='valoracion' name='valoracion' required>
							</div>
                            <input type='hidden' name='idarticulo' id='idarticulo' value='$id_articulo' >
							<div class=\"text-center\"><button type=\"submit\" class=\"input-btn\">Enviar Review</button></div>
						</form>

        

      </div>
    
  </div><hr>";


                    echo "</div>";

                } else {
                    echo "<hr><div class=\"row clearfix\">
    <div class=\"col-md-12 column\">
      
        <div class=\"panel-heading\">
          DEBES INICIAR SESIÓN PARA ENVIAR TU PROPIA REVIEW
        </div>

      </div>
    
  </div><hr>";


                    echo "</div>";
                }

                ?>


            </div>

        </div>

        </div>

        </div>
        <!-- /.container -->


        <?php
    } else {
        echo "<header>OFERTAS ESTRELLA</header>";
        echo verproductosoferta();
    }
}else{
    $conexion = conectar();
    $valoracion = $_POST['valoracion'];
    $mensaje = $_POST['message'];
    $id_articulo = $_POST['idarticulo'];
    $id_usuario = getid($_SESSION['usuario']);
    $mensaje = mysqli_real_escape_string($conexion, $mensaje);
    $sql = "INSERT INTO valoraciones (id_usuario,id_articulo,valoracion,comentario) VALUES ($id_usuario,$id_articulo,$valoracion, '$mensaje')";
    $conexion->query($sql);
    if ($conexion->affected_rows > 0) {
        echo "<div class=\"container\" style='margin: 250px'>";
        echo "<h2 class='text-center'>Review guardada correctamente</h2>";
        echo "</div>";
        header("Refresh:3; url='verproducto.php?codarticulo=$id_articulo'");
    }else{
        echo "<div class=\"container\" style='margin: 250px'>";
        echo "<h2 class='text-center'>No se ha podido guardar la review</h2>";
        echo "</div>";
        header("Refresh:3; url='verproducto.php?codarticulo=$id_articulo'");
    }
}
            ?>
        </div>
</div>

<?php
include_once '../footer.php';