<?php
require("logica/Persona.php");
require("logica/Paciente.php");

$filtro = $_GET["filtro"] ;
$paciente = new Paciente();
$resultados = $paciente->buscar($filtro);
$palabras = array_filter(explode(" ", $filtro));

function resaltarPalabras($texto, $palabras) {
    $palabras = array_filter($palabras);
    if (empty($palabras)) return $texto;
    
    $patrones = array_map(function($palabra) {
        return preg_quote($palabra, '/');
    }, $palabras);
        
        $pattern = '/' . implode('|', $patrones) . '/i';
        
        $texto = preg_replace_callback($pattern, function($matches) {
            return "<strong>" . $matches[0] . "</strong>";
        }, $texto);
            
            return $texto;
}
    

if (!empty($resultados)) {
    echo "<table class='table table-striped table-hover mt-3'>";
    echo "<tr><th>Id</th><th>Nombre</th><th>Apellido</th><th>Correo</th></tr>";
    
    foreach ($resultados as $pac) {
        echo "<tr>";
        echo "<td>" . $pac->getId() . "</td>";
        echo "<td>" . resaltarPalabras($pac->getNombre(), $palabras) . "</td>";
        echo "<td>" . resaltarPalabras($pac->getApellido(), $palabras) . "</td>";
        echo "<td>" . $pac->getCorreo() . "</td>";       
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<div class='alert alert-danger mt-3'>No hay resultados</div>";
}
?>

