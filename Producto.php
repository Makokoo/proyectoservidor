<?php

class Producto{

    private $imagen,$codigo,$nombre,$precio;

    public function __construct($codigo, $nombre, $precio){
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->precio = $precio;
    }

    function __toString(){
      return "Numero: ".$this->codigo." Nombre: ".$this->nombre." Precio: ".$this->precio;
    }


}