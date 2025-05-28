<?php
require_once("persistencia/EstadoCitaDAO.php");
class EstadoCita{
    private $id;
    private $valor;

    public function __construct($id="", $valor=""){
        $this -> id = $id;
        $this -> valor = $valor;
    }

    public function getId(){
        return $this -> id;
    }

    public function getValor(){
        return $this -> valor;
    }

    public function consultarRestantes(){
        $conexion = new Conexion();
        $estadoCitaDAO = new EstadoCitaDAO(id: $this -> id);
        $conexion -> abrir();
        $conexion -> ejecutar($estadoCitaDAO -> consultarRestantes());
        $restantes = array();
        while(($datos = $conexion -> registro()) != null){
            $estadoCita = new EstadoCita($datos[0], $datos[1]);
            array_push($restantes, $estadoCita);
        }
        $conexion -> cerrar();
        return $restantes;
    }
}