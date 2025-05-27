<?php 
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>
<body>
<?php
include ("presentacion/encabezado.php");
include ("presentacion/menu" . ucfirst($rol) . ".php");
?>
<div class="container">
	<div class="row mt-3">
		<div class="col">
			<div class="card">
				<div class="card-header"><h4>Citas</h4></div>
				<div class="card-body">
    				<?php
    				$cita = new Cita();
    				$citas = $cita -> consultar($rol, $id);

    				$todosLosEstadosCita = [];
    				if ($rol == "admin") {
    				    $estadoCitaObj = new EstadoCita();

    				    $todosLosEstadosCita = $estadoCitaObj->consultarTodos();
    			
    				}

    				echo "<table class='table table-striped table-hover'>";
    				echo "<tr><td>Id</td><td>Fecha</td><td>Hora</td>";
    				if($rol != "paciente"){
    				    echo "<td>Paciente</td>";
    				}
    				if($rol != "medico"){
    				    echo "<td>Medico</td>";
    				}
                    echo "<td>Consultorio</td>";
                    echo "<td>Estado</td>";
                  
                    if ($rol == "admin") {
                        echo "<td>Acción</td>";
                    }
                  
                    echo "</tr>";
    				foreach($citas as $cit){
    				    echo "<tr>";
    				    echo "<td>" . $cit -> getId() . "</td>";
    				    echo "<td>" . $cit -> getFecha() . "</td>";
    				    echo "<td>" . $cit -> getHora() . "</td>";
    				    if($rol != "paciente"){
        				    echo "<td>" . $cit -> getPaciente() -> getNombre() . " " . $cit -> getPaciente() -> getApellido() . "</td>";
    				    }
    				    if($rol != "medico"){
    				        echo "<td>" . $cit -> getMedico() -> getNombre() . " " . $cit -> getMedico() -> getApellido() . "</td>";
    				    }
                        echo "<td>" . $cit -> getConsultorio() -> getNombre() . "</td>";
    				    echo "<td>" . $cit -> getEstadoCita() -> getValor() . "</td>";

    				    if ($rol == "admin") {
    				        echo "<td>";
    				        echo "<form method='POST' action='" . $_SERVER['PHP_SELF'] . "'>";
    				        echo "<input type='hidden' name='idCita' value='" . $cit->getId() . "'>";
    				        echo "<select name='idNuevoEstado' class='form-control'>";
    				   
    				        foreach ($todosLosEstadosCita as $estado) {
    				            $selected = ($cit->getEstadoCita()->getIdEstadoCita() == $estado->getIdEstadoCita()) ? 'selected' : '';
    				            echo "<option value='" . $estado->getIdEstadoCita() . "' " . $selected . ">" . $estado->getValor() . "</option>";
    				        }
    				        echo "</select>";
    				        echo "<button type='submit' class='btn btn-sm btn-primary mt-1'>Actualizar</button>";
    				        echo "</form>";
    				        echo "</td>";
    				    }
               
    				    echo "</tr>";
    				}
    				echo "</table>";
    				?>
				</div>
			</div>
		</div>
	</div>
</div>
</body>