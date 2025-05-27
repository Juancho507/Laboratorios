<?php
class EstadoCitaDAO {
    private $id;
    private $valor;
    
    public function __construct($id = 0, $valor = "") {
        $this -> id = $id;
        $this -> valor = $valor;
    }
    public function consultarTodos(){
        return "select idEstadoCita, valor from EstadoCita";
    }
    
}
    