<?php
include_once 'funciones.php';
session_start();
/**
 * Created by PhpStorm.
 * User: MoLy
 * Date: 22/02/2018
 * Time: 12:52
 */

$cod = $_POST['cod'];
$conexion = conectar();
$sql = "UPDATE pedidos SET estado='procesado' WHERE cod_pedido=$cod";
$conexion->query($sql);

header('location:verpedidos.php');