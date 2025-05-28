<?php
require_once("persistencia/Conexion.php");
require_once ("persistencia/CitaDAO.php");

class Cita{
    private $id;
    private $fecha;
    private $hora;
    private $paciente;
    private $medico;
    private $consultorio;
    private $estadoCita;
    public function __construct($id="", $fecha="", $hora="", $paciente="", $medico="", $consultorio="", $estadoCita=null){
        $this -> id = $id;
        $this -> fecha = $fecha;
        $this -> hora = $hora;
        $this -> paciente = $paciente;
        $this -> medico = $medico;
        $this -> consultorio = $consultorio;
        $this -> estadoCita = $estadoCita;
    }

    public function setId($id){
        $this -> id = $id;
    }

    public function getId(){
        return $this -> id;
    }
    
    public function getFecha(){
        return $this -> fecha;
    }
    
    public function getHora(){
        return $this -> hora;
    }
    
    public function getPaciente(){
        return $this -> paciente;
    }
    
    public function getMedico(){
        return $this -> medico;
    }
    
    public function getConsultorio(){
        return $this -> consultorio;
    }
    
    public function getEstadoCita(){
        return $this -> estadoCita;
    }

    public function setEstadoCita($estadoCita){
        $this -> estadoCita = $estadoCita;
    }
    public function consultar($rol="", $id=""){
        $conexion = new Conexion();
        $citaDAO = new CitaDAO();
        $conexion -> abrir();
        $conexion -> ejecutar($citaDAO -> consultar($rol, $id));
        $citas = array();
        while(($datos = $conexion -> registro()) != null){
            $paciente = new Paciente($datos[3], $datos[4], $datos[5]);
            $medico = new Medico($datos[6], $datos[7], $datos[8]);
            $consultorio = new Consultorio($datos[9], $datos[10]);            
            $estadoCita = new EstadoCita($datos[11], $datos[12]);
            $cita = new Cita($datos[0], $datos[1], $datos[2], $paciente, $medico, $consultorio, $estadoCita);
            array_push($citas, $cita);
        }
        $conexion -> cerrar();
        return $citas;
    }


    public function consultarPorId(){
        $conexion = new Conexion();
        $citaDAO = new CitaDAO($this -> id);
        $conexion -> abrir();
        $conexion -> ejecutar($citaDAO -> consultarPorId());
        $datos = $conexion -> registro();
        if ($datos != null) { 
            $this -> fecha = $datos[0];
            $this -> hora = $datos[1];
            $this -> paciente = new Paciente($datos[2], $datos[3], $datos[4]);
            $this -> medico = new Medico($datos[5], $datos[6], $datos[7]);
            $this -> consultorio = new Consultorio($datos[8], $datos[9]);
            $this -> estadoCita = new EstadoCita($datos[10], $datos[11]);
            $conexion -> cerrar();
            return true; 
        }
        $conexion -> cerrar();
        return false; 
    }
    
    public function cambiarEstado(){
        $conexion = new Conexion();
        $citaDAO = new CitaDAO(id: $this->id, estadoCita: $this->estadoCita->getId());       
        $conexion->abrir();
        $sentencia = $citaDAO->cambiarEstado();       
        if (empty($sentencia)) {
            $conexion->cerrar();
            return false;
        }       
        $conexion->ejecutar($sentencia);
        $idEstadoEsperado = $this->estadoCita->getId();
        $conexion->cerrar();
        $citaActualizada = new Cita($this->id);
        if ($citaActualizada->consultarPorId()) {
            $idEstadoActual = $citaActualizada->getEstadoCita()->getId();
            return $idEstadoActual == $idEstadoEsperado;
        }      
        return false; 
    }
}
?>