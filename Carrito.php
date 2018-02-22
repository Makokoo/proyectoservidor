<?php

/**
 * Created by PhpStorm.
 * User: MoLy
 * Date: 25/01/2017
 * Time: 8:03
 */

include_once 'funciones.php';
class Carrito{

    private $productos=array();
    private $cantidades=array();
    private $nombres = array();
    private $usuario;

    public function __construct($usuario){
        $this->usuario = $usuario;
    }

    public function addProducto($cod_articulo,$nom_articulo,$cantidad){
        $this->productos[]= $cod_articulo;
        $this->nombres[] = $nom_articulo;
        $this->cantidades[] = $cantidad;
    }

    public function vaciarCarrito(){
        $this->productos = array();
        $this->cantidades = array();
    }

    public function getproductos(){
        return $this->productos;
    }

    public function getCantidades(){
        return $this->cantidades;
    }

    public function __toString(){

        $precio = 0;
        $preciolinea = 0;
        $datos ="<div class='col-lg-6 col-md-offset-3'><table class='table text-center table-bordered'>";
        $datos.="<tr><td><b>Producto</b></td><td><b>Cantidad</b></td><td><b>Precio</b></td></tr>";
        for($i=0;$i<count($this->productos);$i++){
            $producto = $this->productos[$i];
            $sql = "SELECT * FROM articulos WHERE cod_articulo = $producto";
            $conexion = conectar();
            $r = $conexion->query($sql);
            $resultado = $r->fetch_assoc();
            $precio = $precio + $resultado['precio'] * $this->cantidades[$i];
            $preciolinea = $resultado['precio'] * $this->cantidades[$i];
            $datos.= "<tr><td class='table text-center'>".$this->nombres[$i]."</td><td>".$this->cantidades[$i]."</td><td>".$preciolinea."€</td></tr>";
        }



        $datos .= "</table>
<table class='table text-center table-bordered'>
<tr><th>Precio Total</th><td>".$precio."€</td></tr>
</table>
</div>";
        return $datos;
    }

}