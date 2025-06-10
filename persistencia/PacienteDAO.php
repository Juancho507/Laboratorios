<?php

class PacienteDAO{
    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $clave;
    private $fechaNacimiento;

    public function __construct($id = 0, $nombre = "", $apellido = "", $correo = "", $clave = "", $fechaNacimiento = ""){
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
        $this -> correo = $correo;
        $this -> clave = $clave;
        $this -> fechaNacimiento = $fechaNacimiento;
    }

       
    public function autenticar(){
        return "select idPaciente
                from Paciente
                where correo = '" . $this -> correo . "' and clave = '" . md5($this -> clave) . "'";
    }
    
    public function consultar(){
        return "select p.nombre, p.apellido, p.correo, p.fechaNacimiento  
                from Paciente p
                where idPaciente = '" . $this -> id . "'";
    }

    public function buscar($filtro){
        $condicion = array_map(function($palabra) {
            $palabras = trim($palabra);
            return "(p.nombre like '%" . $palabras. "%' or p.apellido like '%" . $palabras . "%')";
        }, array_filter(explode(" ", $filtro)));
            if (empty($condicion)) {
            return "select p.idPaciente, p.nombre, p.apellido, p.correo from Paciente p";
        } else {
            return "select p.idPaciente, p.nombre, p.apellido, p.correo
                    from Paciente p
                    where " . implode(" and ", $condicion);
        }
    }
}