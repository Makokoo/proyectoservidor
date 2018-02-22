<?php

include_once 'funciones.php';
include_once 'Carrito.php';
include_once 'Producto.php';


session_start();

$cliente = $_POST['nick'];
$cantidad = $_POST['cantidad'];
$cod = $_POST['cod'];
$nombre = $_POST['nombre'];
$_SESSION['nick'] = $cliente;


if (!isset($_SESSION["carrito"])){
    $_SESSION["carrito"] = new Carrito($cliente);
}

$carrito = $_SESSION['carrito'];

$conexion = conectar();
$sql = "SELECT * FROM articulos WHERE cod_articulo LIKE '$cod'";




$res = $conexion->query($sql);

$linea=$res->fetch_row();
/*
0 -> cod_articulo
1 -> nombre_articulo
2 -> descripcion_articulo
3 -> imagen
4 -> precio
*/

$articulo = new Producto($linea[0],$linea[1],$linea[4]);

$carrito->addProducto($cod,$linea[1],$cantidad);
echo "El Artículo se ha añadido correctamente";

header("refresh:1; url=ver_carrito.php?cliente=$cliente");
