<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Justificar Ausencias</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>

<?php
include "/var/www/html/portalempleado/anexo.php";
session_start();


if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}


$conexion = conectar_bd_rrhh();
$ausencias = $conexion->query("SELECT id_ausencia, id_empleado, fecha_inicio, fecha_fin, motivo 
                               FROM ausencias WHERE justificada = FALSE")
                      ->fetch_all(MYSQLI_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ausencia = $_POST["id_ausencia"];
    $id_empleado_modificador = $_SESSION["id_usuario"];

    $mensaje = justificar_ausencia($id_ausencia, $id_empleado_modificador);

    header("Location: justificar_ausencia.php");
    exit();
}
?>

<h1>Justificar Ausencias</h1>

<?php if (empty($ausencias)): ?>
    <p>No hay ausencias pendientes de justificación.</p>
<?php else: ?>
<table>
    <tr>
        <th>ID</th><th>Empleado</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Motivo</th><th>Acción</th>
    </tr>
    <?php foreach ($ausencias as $ausencia): ?>
    <tr>
        <td><?= $ausencia['id_ausencia']; ?></td>
        <td><?= $ausencia['id_empleado']; ?></td>
        <td><?= $ausencia['fecha_inicio']; ?></td>
        <td><?= $ausencia['fecha_fin']; ?></td>
        <td><?= $ausencia['motivo']; ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="id_ausencia" value="<?= $ausencia['id_ausencia']; ?>">
                <button type="submit">Justificar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
<a href="dashboard_rrhh.php">Volver al Dashboard</a>

</body>
</html>
