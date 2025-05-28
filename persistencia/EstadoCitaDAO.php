<?php
class EstadoCitaDAO{
    private $id;
    private $valor;
    public function __construct($id="", $valor=""){
        $this -> id = $id;
        $this -> valor = $valor;
    }
    public function consultarRestantes(){
        return "select idEstadoCita, valor from EstadoCita where idEstadoCita not in (select idEstadoCita from EstadoCita where idEstadoCita = {$this -> id})";
    }
}