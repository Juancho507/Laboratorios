<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>
<body>
<?php
include ("presentacion/encabezado.php");
include ("presentacion/menu" . ucfirst($rol) . ".php");

$success = false;
$message = "";

if(isset($_POST["actualizar_cita"]) && isset($_POST["idCita_individual"]) && isset($_POST["estado_individual"])){
    $idCita = $_POST["idCita_individual"];
    $idEstado = $_POST["estado_individual"];

    $cita = new Cita();
    $cita->setId($idCita);
    $cita->consultarPorId();
    $estadoAnterior = $cita->getEstadoCita()->getId();
    $estadoNuevo = $idEstado;

    if($estadoAnterior != $estadoNuevo){
        $cita->setEstadoCita(new EstadoCita($estadoNuevo));
        if($cita->cambiarEstado()){
            $success = true;
            $message = "Estado de la cita #" . $idCita . " actualizado correctamente.";
        } else {
            $success = false;
            $message = "Error al actualizar la cita #" . $idCita . ".";
        }
    } else {
        $success = true;
        $message = "El estado de la cita #" . $idCita . " no ha cambiado.";
    }
}

$cita = new Cita();
$citas = $cita->consultar($rol, $id);

?>
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <?php if($message != "") { echo "<div class='alert alert-" . ($success ? "success" : "danger") . " text-center' role='alert'>" . $message . "</div>"; } ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Citas</h4>
                </div>
                <div class="card-body">
                    <table class='table table-striped table-hover'>
                        <tr>
                            <td>Id</td>
                            <td>Fecha</td>
                            <td>Hora</td>
                            <?php if($rol != "paciente"){ echo "<td>Paciente</td>"; } ?>
                            <?php if($rol != "medico"){ echo "<td>Medico</td>"; } ?>
                            <td>Consultorio</td>
                            <td>Estado</td>
                            </tr>

                        <?php foreach($citas as $cit): ?>
                        <tr>
                            <td><?= $cit->getId(); ?></td>
                            <td><?= $cit->getFecha(); ?></td>
                            <td><?= $cit->getHora(); ?></td>
                            <?php if($rol != "paciente"): ?>
                                <td><?= $cit->getPaciente()->getNombre() . " " . $cit->getPaciente()->getApellido(); ?></td>
                            <?php endif; ?>
                            <?php if($rol != "medico"): ?>
                                <td><?= $cit->getMedico()->getNombre() . " " . $cit->getMedico()->getApellido(); ?></td>
                            <?php endif; ?>
                            <td><?= $cit->getConsultorio()->getNombre(); ?></td>
                            <?php if($rol == "admin"): ?>
                                <td>
                                    <form method='POST' action='?pid=<?= base64_encode("presentacion/cita/consultarCita.php") ?>'>
                                        <input type="hidden" name="idCita_individual" value="<?= $cit->getId(); ?>">
                                        <div class="mb-2"> <select class='form-select' name="estado_individual">
                                                <option value="<?= $cit->getEstadoCita()->getId(); ?>" selected><?= $cit->getEstadoCita()->getValor(); ?></option>
                                                <?php foreach($cit->getEstadoCita()->consultarRestantes() as $ecr): ?>
                                                    <?php if($ecr->getId() != $cit->getEstadoCita()->getId()): ?>
                                                        <option value="<?= $ecr->getId(); ?>"><?= $ecr->getValor(); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <button type="submit" name="actualizar_cita" class="btn btn-sm btn-info w-100">Actualizar</button> </form>
                                </td>
                            <?php else: ?>
                                <td><?= $cit->getEstadoCita()->getValor(); ?></td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>