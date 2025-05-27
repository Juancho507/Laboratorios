<?php
class CitaDAO{
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
    
    public function consultar($rol, $id){
        $sentencia = "select c.idCita, c.fecha, c.hora, p.idPaciente, p.nombre, p.apellido, m.idMedico, m.nombre, m.apellido, con.idConsultorio, con.nombre, ec.idEstadoCita, ec.valor
            from Cita c
            join Paciente p ON c.Paciente_idPaciente = p.idPaciente
            join Medico m ON c.Medico_idMedico = m.idMedico
            join Consultorio con ON c.Consultorio_idConsultorio = con.idConsultorio
            join EstadoCita ec ON c.EstadoCita_idEstadoCita = ec.idEstadoCita";
        if($rol == "medico"){
            $sentencia .= " where m.idMedico = '" . $id . "'";
        }else if($rol == "paciente"){
            $sentencia .= " where p.idPaciente = '" . $id . "'";
        }
        return $sentencia;
    }
    
    
    
}


?>
