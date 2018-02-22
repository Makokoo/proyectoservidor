<?php
/**
 * Created by PhpStorm.
 * User: MoLy
 * Date: 17/01/2018
 * Time: 11:26
 */

function logincorrecto($nick,$pass){

    //Funcion que comprueba si existe el usuario con la contraseña correspondiente

    $conexion = conectar();
    $sql = "SELECT nick,pass,estado FROM clientes WHERE nick LIKE '$nick' AND pass LIKE '$pass'";
    $resultado= $conexion->query($sql);

    $datos = array();

    while ($data=$resultado->fetch_row()) {
        $datos = $data;
    }

    if(count($datos)==3) {
        if ($datos[0] == $nick && $datos[1] == $pass) {
            if($datos[2]=="alta") {
                return true;
            }else{
                echo "Cuenta anulada, contacta con un Administrador</br>";
                return false;
            }
        } else {
            return false;
        }
    }else{
        return false;
    }

}

define ('SERVIDOR', "localhost");
define ('USUARIO', "root");
define ('CONTRA', "");
define ('BBDD', "proyectotienda");

function totalarticulos(){
    $conexion = conectar();
    $sql = "SELECT COUNT(*) FROM articulos WHERE estado LIKE 'alta'";
    $ro = $conexion->query($sql);
    $total = $ro->fetch_assoc();
    return $total['COUNT(*)'];
}

function totalarticuloscategoria($categoria){
    $conexion = conectar();
    $sql = "SELECT COUNT(*) FROM articulos WHERE categoria LIKE '".$categoria."'";
    $ro = $conexion->query($sql);
    $total = $ro->fetch_assoc();
    return $total['COUNT(*)'];
}

function conectar(){
    @$conexion = new mysqli(SERVIDOR,USUARIO,CONTRA,BBDD);
    if($conexion -> connect_errno!=0){
        die('Atencion! Problemas de base de datos, contacte con el administrador');
    }

    return $conexion;
}

function desconectar($conexion){
    $conexion->close();
}

function verproductosoferta(){
    $conexion = conectar();


    $mostrar = "";
    $sqllineas = "SELECT * FROM articulos WHERE precio < 100 AND estado NOT LIKE 'baja' LIMIT 8";
    $ro = $conexion->query($sqllineas);
    while ($detalles = $ro->fetch_assoc()) {
        $mostrar .= "<link href=\"bootstrap.css\" rel=\"stylesheet\">
            <div class='border center-block hoover' style='float:left;padding:19px;'>";

        $nombre_fichero = "imagenes/".$detalles['cod_articulo'].".png";

        if (file_exists($nombre_fichero)) {
            $mostrar.="<img src=" . $nombre_fichero . " width='265px' height='150px'>";
        } else {
            $mostrar.="<img src=" . $detalles['imagen'] . " width='265px' height='150px'>";
        }




                $mostrar.="<div class='info'>
                    <h4 style='padding-left: 10px'>" . $detalles['nombre_articulo'] . "</h4>
                    <span class=\"description\" style='padding-left: 10px'>
                        " . $detalles['descripcion_articulo'] . "
                    </span>
                    </br>
                    <span class='h2' style='padding-left: 10px'>" . $detalles['precio'] . "€</span>
                    <a class='btn btn-info' style='float: right;margin: 20px' href='verproducto.php?codarticulo=".$detalles['cod_articulo']."'><i></i>Ver Producto</a>
                </div>
            </div>
        
    ";

    }
    return $mostrar;
}

function verproductosporcategoria($categoria){
    $conexion = conectar();
    $num_filas = 8;
    $mostrar ="";
    $total_articulos = totalarticuloscategoria($categoria);
    if (isset($_GET["desplazamiento"]))
        $desplazamiento = $_GET["desplazamiento"];
    else $desplazamiento = 0;
    $sql = "SELECT * FROM articulos WHERE categoria LIKE '$categoria' ORDER BY cod_articulo  LIMIT $desplazamiento, $num_filas ";

    $ro = $conexion->query($sql);

    while($detalles = $ro->fetch_assoc()){
        if($detalles['estado']=="alta") {
            $mostrar .= "<link href=\"bootstrap.css\" rel=\"stylesheet\">
            <div class='border center-block hoover' style='float:left;padding:19px;'>";
            $nombre_fichero = "imagenes/".$detalles['cod_articulo'].".png";
            if (file_exists($nombre_fichero)) {
                $mostrar.="<img src=" . $nombre_fichero . " width='265px' height='150px'>";
            } else {
                $mostrar.="<img src=" . $detalles['imagen'] . " width='265px' height='150px'>";
            }
                $mostrar.="<div class='info'>
                    <h4 style='padding-left: 10px'>" . $detalles['nombre_articulo'] . "</h4>
                    <span class=\"description\" style='padding-left: 10px'>
                        " . $detalles['descripcion_articulo'] . "
                    </span>
                    </br>
                    <span class='h2' style='padding-left: 10px'>" . $detalles['precio'] . "€</span>
                    <a class='btn btn-info' style='float: right;margin: 20px' href=\"verproducto.php?codarticulo=" . $detalles['cod_articulo'] . "\"><i></i>Ver Producto</a>
                </div>
            </div>
        
    ";
        }

    }

    if ($desplazamiento > 0) {
        $prev = $desplazamiento - $num_filas;
        $url = $_SERVER["PHP_SELF"] . "?categoria=$categoria&desplazamiento=$prev";
        $mostrar .= "<button><A HREF='$url'>Página anterior</A></button>";
    }
    if ($total_articulos > ($desplazamiento + $num_filas)) {
        $prox = $desplazamiento + $num_filas;
        $url = "index.php?categoria=$categoria&desplazamiento=$prox";
        $mostrar .= "<button class='buttons'><A HREF='$url'>Próxima página</A></button>";
    }
    return $mostrar;

}

function verproductosporbusqueda($busqueda){
    $conexion = conectar();

    $mostrar ="";

    $sql = "SELECT * FROM articulos WHERE nombre_articulo LIKE '%$busqueda%' OR descripcion_articulo LIKE '%$busqueda%'";

    $ro = $conexion->query($sql);

    while($detalles = $ro->fetch_assoc()){
        if($detalles['estado'] == "alta") {
            $mostrar .= "<link href=\"bootstrap.css\" rel=\"stylesheet\">
            <div class='border center-block hoover' style='float:left;padding:19px;'>";

            $nombre_fichero = "imagenes/".$detalles['cod_articulo'].".png";
            if (file_exists($nombre_fichero)) {
                $mostrar.="<img src=" . $nombre_fichero . " width='265px' height='150px'>";
            } else {
                $mostrar.="<img src=" . $detalles['imagen'] . " width='265px' height='150px'>";
            }
                $mostrar.="<div class='info'>
                    <h4 style='padding-left: 10px'>" . $detalles['nombre_articulo'] . "</h4>
                    <span class=\"description\" style='padding-left: 10px'>
                        " . $detalles['descripcion_articulo'] . "
                    </span>
                    </br>
                    <span class='h2' style='padding-left: 10px'>" . $detalles['precio'] . "€</span>
                    <a class='btn btn-info' style='float: right;margin: 20px' href=\"verproducto.php?codarticulo=" . $detalles['cod_articulo'] . "\"><i></i>Ver Producto</a>
                </div>
            </div>
        
    ";
        }
    }

    return $mostrar;
}

function vertodoslosproductos(){
    $conexion = conectar();
    $num_filas = 8;
    $mostrar ="";
    $total_articulos = totalarticulos();
    if (isset($_GET["desplazamiento"]))
        $desplazamiento = $_GET["desplazamiento"];
    else $desplazamiento = 0;
    $sql = "SELECT * FROM articulos WHERE estado NOT LIKE 'baja' ORDER BY cod_articulo LIMIT $desplazamiento, $num_filas";

    $ro = $conexion->query($sql);

    while($detalles = $ro->fetch_assoc()){
        $mostrar .= "<link href=\"bootstrap.css\" rel=\"stylesheet\">
            <div class='border center-block hoover' style='float:left;padding:19px;'>";

        $nombre_fichero = "imagenes/".$detalles['cod_articulo'].".png";
        if (file_exists($nombre_fichero)) {
            $mostrar.="<img src=" . $nombre_fichero . " width='265px' height='150px'>";
        } else {
            $mostrar.="<img src=" . $detalles['imagen'] . " width='265px' height='150px'>";
        }

                $mostrar.="<div class='info'>
                    <h4 style='padding-left: 10px'>" . $detalles['nombre_articulo'] . "</h4>
                    <span class=\"description\" style='padding-left: 10px'>
                        " . $detalles['descripcion_articulo'] . "
                    </span>
                    </br>
                    <span class='h2' style='padding-left: 10px'>" . $detalles['precio'] . "€</span>
                    <a class='btn btn-info' style='float: right;margin: 20px' href='verproducto.php?codarticulo=".$detalles['cod_articulo']."'><i></i>Ver Producto</a>
                </div>
            </div>
        
    ";

    }

    if ($desplazamiento > 0) {
        $prev = $desplazamiento - $num_filas;
        $url = $_SERVER["PHP_SELF"] . "?categoria=all&desplazamiento=$prev";
        $mostrar .= "</br><div class='text-center float-left'><button><A HREF='$url'>Página anterior</A></button></div>";
    }
    if ($total_articulos > ($desplazamiento + $num_filas)) {
        $prox = $desplazamiento + $num_filas;
        $url = "index.php?categoria=all&desplazamiento=$prox";
        $mostrar .= "<div class='text-center'><button class='buttons' style='float:right'><A HREF='$url'>Próxima página</A></button></div>";
    }


    return $mostrar;

}

function mostrarmenu(){

    $mostrar = "";
    $mostrar .= "<link href=\"bootstrap.css\" rel=\"stylesheet\">
<p align='center'>
<ul id=\"menu\" style='margin: auto;'>
            <li><a href=\"index.php\">Inicio</a></li>
            <li><a href=\"index.php\">Ofertas</a></li>
            <li><a href=\"quienessomos.php\">Quienes Somos</a> </li>
            <li><a href=\"contacto.php\">Contacto</a></li>";

        $mostrar .= "</ul>
        </p>";

    return $mostrar;

}

function verproducto($codigo){
    $conexion = conectar();

    if(isset($_SESSION['nick']))
        $cliente = $_SESSION['nick'];
    else{
        $cliente = "invitado";
    }


    $mostrar = "";
    $sqllineas = "SELECT * FROM articulos WHERE cod_articulo = $codigo ";
    $ro = $conexion->query($sqllineas);
    while ($detalles = $ro->fetch_assoc()) {
        $mostrar .= "<link href=\"bootstrap.css\" rel=\"stylesheet\">
            <div class='border center-block hoover' style='float:none;padding:19px;'>";

        $nombre_fichero = "imagenes/".$detalles['cod_articulo'].".png";
        if (file_exists($nombre_fichero)) {
            $mostrar.="<img src=" . $nombre_fichero . " width='450px' height='250px'>";
        } else {
            $mostrar.="<img src=" . $detalles['imagen'] . " width='450px' height='250px'>";
        }
       

                $mostrar.="<div class='info'>
                    <h4 style='padding-left: 10px'>" .$detalles['nombre_articulo'].  "</h4>
                    
                    <span class=\"description\" style='padding-left: 10px'>
                        " . $detalles['descripcion_articulo'] . "
                    </span>
                    </br>
                    <span class='h2' style='padding-left: 10px'>" . $detalles['precio'] . "€</span>
                    
                    <form method='post' action='alcarrito.php' style='float:right'>
                        Cantidad: <input type='number' name='cantidad' id='cantidad' style='width:50px;' min='1' required class='form-group'>
                        <input type='submit' class='btn btn-info'  name='anadir' id='anadir' value='Añadir al carrito'>
                        <input type='hidden' name='cod' id='cod' value=" . $detalles['cod_articulo'] . ">
                        <input type='hidden' name='nick' id='nick' value=".$cliente.">
                        <input type='hidden' name='nombre' id='nombre' value=".$detalles['nombre_articulo'].">
                       
                        </form>
                    </br>
                </div>
            </div>
        
    ";

    }
    return $mostrar;
}

function sacarcodcliente($nombre, $conexion)
{
    $sql = "SELECT cod_cliente FROM clientes WHERE nick LIKE '$nombre'";
    $res = $conexion->query($sql);
    $dato = $res->fetch_assoc();
    return $dato['cod_cliente'];
}

function sacardatoarticulo($cod,$conexion){
    $sql = "SELECT * FROM articulos WHERE cod_articulo = $cod";
    $res = $conexion->query($sql);
    $dato = $res->fetch_assoc();
    return $dato;
}

function verpedidos($nombre)
{
    $conexion = conectar();




    $codigo = sacarcodcliente($nombre, $conexion);

    $sql = "SELECT * FROM pedidos WHERE cod_cliente = $codigo";

    $resultado = $conexion->query($sql);
    $mostrar = "";
    echo "<h1>Pedidos del cliente: " . $nombre . "</h1>";
    while ($linea = $resultado->fetch_assoc()) {

        $mostrar .= "<table class='table table-bordered text-center '><tr>
    <th class='text-center label-primary btn-warning'>CODIGO PEDIDO</th>
    <th class='text-center label-primary btn-warning'>CODIGO CLIENTE</th>
    <th class='text-center label-primary btn-warning'>FECHA PEDIDO</th>";
        if($linea['estado']=="procesando") {
            $mostrar.="<th class='text-center label-primary btn-warning' > MODIFICAR</th >";
        }
        $conexion = conectar();
        if(verpermiso($_SESSION['nick'],$conexion)==1 || verpermiso($_SESSION['nick'],$conexion)==3){
            if($linea['estado']=="procesando") {
                $mostrar .= "<th class='text-center label-primary btn-warning' >PROCESAR</th >";
            }
        }
    $mostrar.="</tr>";


        $mostrar .= "<tr><td>" . $linea['cod_pedido'] . "</td><td>" . $linea['cod_cliente'] . "</td><td>" . $linea['fecha'] .
            "</td>";

        if($linea['estado']=="procesando") {
            $mostrar.="<td><form method='post' action='modificar_pedido.php'>";
            $mostrar.= " <input class='btn btn-primary' type='submit' name='modificar' id='modificar' value='Modificar'>
                        <input type='hidden' name='cod' id='cod' value=" . $linea['cod_pedido'] . ">
                                              
                        </form>
                    </td>";
        }
        if(verpermiso($_SESSION['nick'],$conexion)==1 || verpermiso($_SESSION['nick'],$conexion)==3){
            if($linea['estado']=="procesando") {
                $mostrar .= "<td><form method='post' action='procesar_pedido.php'>";
                $mostrar .= " <input class='btn btn-danger' type='submit' name='procesar' id='procesar' value='Procesar'>
                        <input type='hidden' name='cod' id='cod' value=" . $linea['cod_pedido'] . ">
                                              
                        </form>
                    </td>";
            }
        }
                    

                     




        $sqllineas = "SELECT * FROM lineas_pedidos WHERE cod_pedido=" . $linea['cod_pedido'];

        $ro = $conexion->query($sqllineas);
        while ($linea_pedido = $ro->fetch_assoc()) {
            $datosproducto = sacardatoarticulo($linea_pedido['cod_articulo'],$conexion);
            $mostrar .= "</table><table class='table table-bordered text-center'><tr>
    <th class='text-center'>LINEA</th>
    <th class='text-center'>IMAGEN</th>
    <th class='text-center'>ARTICULO</th>
    <th class='text-center'>CANTIDAD</th>
   
    </tr>
    <td>" . $linea_pedido['num_linea_pedido'] . "</td>
    <td><img src='" . $datosproducto['imagen'] . "' width='10%'></td>
    <td>" . $datosproducto['nombre_articulo'] . "</td>
    <td>" . $linea_pedido['cantidad'] . "</td>
    
    </table>";
        }

        $mostrar .= "</table><hr>";
    }

    return $mostrar;
}

function verpermiso($nombre,$conexion){
    /*
     * 0: Cliente normal
     * 1: Empleado
     * 3: SuperUsuario
     */
    $sql = "SELECT permiso FROM clientes WHERE nick LIKE '$nombre'";
    $res = $conexion->query($sql);
    $dato = $res->fetch_assoc();
    return $dato['permiso'];
}

function verclientes()
{
    $conexion = conectar();



    $sql = "SELECT * FROM clientes WHERE permiso < 3";

    $resultado = $conexion->query($sql);
    $mostrar = "";

    while ($linea = $resultado->fetch_assoc()) {

        $mostrar .= "<table class='table table-bordered text-center '><tr>
        <th class='text-center label-primary btn-info'>CODIGO CLIENTE</th>
        <th class='text-center label-primary btn-info'>NOMBRE</th>
        <th class='text-center label-primary btn-info'>NICK</th>
        <th class='text-center label-primary btn-info'>ESTADO</th>
        <th class='text-center label-primary btn-info'>CAMBIAR ESTADO</th>
        <th class='text-center label-primary btn-info'>MODIFICAR</th>
    </tr>";


        $mostrar .= "<tr><td>" . $linea['cod_cliente'] . "</td><td>" . $linea['Nombre'] . "</td><td>" . $linea['nick'] .
            "</td>";
        if($linea['estado'] == 'alta') {
            $mostrar .= "<td class='alert-success'>".$linea['estado']."</td>";
        }else{
            $mostrar .= "<td class='alert-warning'>".$linea['estado']."</td>";
        }

        $mostrar .="<td><form method='post' action='cambiarestadocliente.php'>

                <input class='btn btn-primary' type='submit' name='modificar' id='modificar' value='Cambiar Estado'>
                <input type='hidden' name='cod' id='cod' value=" . $linea['cod_cliente'] . ">

            </form>
        </td><td><form method='post' action='modificarcliente.php'>

                <input class='btn btn-primary' type='submit' name='modificar' id='modificar' value='Modificar'>
                <input type='hidden' name='cod' id='cod' value=" . $linea['cod_cliente'] . ">

            </form>";


        $mostrar .= "</td></table>";

    }

    return $mostrar;

}

function cambiarestadocliente ($cod, $conexion){
    $estadoinicial = "";
    $sql = "SELECT estado FROM clientes WHERE cod_cliente = ".$cod;
    $resultado = $conexion->query($sql);
    $sql2 = "";

    while ($linea = $resultado->fetch_assoc()) {
        $estadoinicial = $linea['estado'];
    }

    if($estadoinicial=="alta"){
        $sql2 = "UPDATE clientes SET estado = 'baja' WHERE cod_cliente=$cod ";
    }else{
        $sql2 = "UPDATE clientes SET estado = 'alta' WHERE cod_cliente=$cod ";
    }

    $conexion->query($sql2);


}

function cambiarestadoproducto ($cod, $conexion){
    $estadoinicial = "";
    $sql = "SELECT estado FROM articulos WHERE cod_articulo = ".$cod;
    $resultado = $conexion->query($sql);
    $sql2 = "";

    while ($linea = $resultado->fetch_assoc()) {
        $estadoinicial = $linea['estado'];
    }

    if($estadoinicial=="alta"){
        $sql2 = "UPDATE articulos SET estado = 'baja' WHERE cod_articulo=$cod ";
    }else{
        $sql2 = "UPDATE articulos SET estado = 'alta' WHERE cod_articulo=$cod ";
    }

    $conexion->query($sql2);


}

function verlistaproductos()
{
    $conexion = conectar();


    echo "<a href='modificararticulo.php'><button class='btn btn-success'>Crear Nuevo Producto</button></a>";
    echo "<a href='crearcategoria.php'><button class='btn btn-success'>Crear Nueva Categoria</button></a>";
    $sql = "SELECT * FROM articulos";

    $resultado = $conexion->query($sql);
    $mostrar = "";
    $mostrar .= "<table class='table table-bordered text-center '><tr>
        <th class='text-center label-primary btn-info'>NOMBRE</th>
        <th class='text-center label-primary btn-info'>IMAGEN</th>
        <th class='text-center label-primary btn-info'>PRECIO</th>
        <th class='text-center label-primary btn-info'>ESTADO</th>
        <th class='text-center label-primary btn-info'>CAMBIAR ESTADO</th>
        <th class='text-center label-primary btn-info'>MODIFICAR ARTÍCULO</th>
        
    </tr><tr>";

    while ($linea = $resultado->fetch_assoc()) {

        $nombre_fichero = "imagenes/".$linea['cod_articulo'].".png";
        if (file_exists($nombre_fichero)) {
            $mostrar.="<td><img src='" . $nombre_fichero ."' style='width: 30px'></td>";
        } else {
            $mostrar.="<td><img src='" . $linea['imagen'] ."' style='width: 30px'></td>";
        }


        $mostrar .= "<td>" . $linea['nombre_articulo'] . "</td>
             </td><td>".$linea['precio']."</td>";
        if($linea['estado'] == 'alta') {
            $mostrar .= "<td class='alert-success'>".$linea['estado']."</td>";
        }else{
            $mostrar .= "<td class='alert-warning'>".$linea['estado']."</td>";
        }

        $mostrar .="<td><form method='post' action='cambiarestadoproducto.php'>

                <input class='btn btn-primary' type='submit' name='modificar' id='modificar' value='Cambiar Estado'>
                <input type='hidden' name='cod' id='cod' value=" . $linea['cod_articulo'] . ">

            </form>
        </td>";

        $mostrar .="<td><form method='post' action='modificararticulo.php'>

                <input class='btn btn-primary' type='submit' name='modificar' id='modificar' value='Modificar'>
                <input type='hidden' name='cod' id='cod' value=" . $linea['cod_articulo'] . ">
                <input type='hidden' name='precio' id='precio' value=" . $linea['precio'] . ">
                <input type='hidden' name='nombre' id='nombre' value=" . $linea['nombre_articulo'] . ">
                <input type='hidden' name='descripcion' id='descripcion' value=" . $linea['descripcion_articulo'] . ">
                <input type='hidden' name='categoria' id='categoria' value=" . $linea['categoria'] . ">

            </form>
        </td>";




        $mostrar .= "</tr>";

    }
    $mostrar.= "</table>";
    return $mostrar;

}

function existenick($nick){
    $sql = "SELECT * from clientes WHERE nick LIKE '$nick'";
    $conexion = conectar();
    $res = $conexion->query($sql);
    $dato = $res->num_rows;

    if($dato==1){
        return true;
    }else{
        return false;
    }

}

function mismonick($original,$nuevo){
    $sql = "SELECT nick from clientes WHERE nick LIKE '$original'";
    $conexion = conectar();
    $res = $conexion->query($sql);
    $dato = $res->fetch_assoc();

    if($dato['nick']==$nuevo){
        return true;
    }else{
        return false;
    }

}

function getnick($cod){
    $sql = "SELECT nick from clientes WHERE cod_cliente=$cod";
    $conexion = conectar();
    $res = $conexion->query($sql);
    $dato = $res->fetch_assoc();

    return $dato['nick'];

}






