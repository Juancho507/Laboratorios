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
    
    public function __construct($id="", $fecha="", $hora="", $paciente="", $medico="", $consultorio="", $estadoCita=""){
        $this -> id = $id;
        $this -> fecha = $fecha;
        $this -> hora = $hora;
        $this -> paciente = $paciente;
        $this -> medico = $medico;
        $this -> consultorio = $consultorio;
        $this -> estadoCita = $estadoCita;
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
            $estadoCita = new EstadoCita ($datos[11], $datos[12]);
            $cita = new Cita($datos[0], $datos[1], $datos[2], $paciente, $medico, $consultorio, $estadoCita);
            array_push($citas, $cita);
        }
        $conexion -> cerrar();
        return $citas;
    }
    
    public function actualizarEstado($idCita, $idNuevoEstado){
        $conexion = new Conexion();
        $conexion -> abrir();
        $citaDAO = new CitaDAO();
        $sentenciaSQL = $citaDAO -> actualizarEstado($idCita, $idNuevoEstado); // Obtener la sentencia
        // --- INICIO DE DEPURACIÓN ---
        error_log("SQL de actualización: " . $sentenciaSQL); // Imprime la sentencia en el log de errores de PHP
        // --- FIN DE DEPURACIÓN ---
        $exito = $conexion -> ejecutar($sentenciaSQL);
        $conexion -> cerrar();
        return $exito;
    }
}
?>
