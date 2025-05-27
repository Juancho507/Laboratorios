<?php
require_once("persistencia/Conexion.php"); 
require_once("persistencia/EstadoCitaDAO.php");

class EstadoCita {
    private $id;
    private $valor;
    
    public function __construct($id = 0, $valor = "") {
        $this->id = $id;
        $this->valor = $valor;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getValor() {
        return $this->valor;
    }
    
    public function consultarTodos(){
        $conexion = new Conexion();
        $estadoCitaDAO = new EstadoCitaDAO();
        $conexion->abrir();
        if (!$conexion->ejecutar($estadoCitaDAO->consultarTodos())) {
            error_log("Error al ejecutar la consulta de todos los estados de cita en EstadoCita::consultarTodos.");
            $conexion->cerrar();
            return []; 
        }
        
        $estados = array();
        while (($datos = $conexion->registro()) != null) {
            if (isset($datos[0]) && isset($datos[1])) {
                $estado = new EstadoCita($datos[0], $datos[1]);
                array_push($estados, $estado);
            } else {
                error_log("Advertencia: No se encontraron los datos esperados (id o valor) para un estado de cita. Datos: " . print_r($datos, true));
            }
        }
        $conexion->cerrar();
        return $estados;
    }
}
?>