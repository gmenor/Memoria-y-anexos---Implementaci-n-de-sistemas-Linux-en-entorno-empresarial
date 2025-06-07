<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar estado de Vacaciones</title>
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


$id_empleado_modificador = $_SESSION["id_usuario"];


$conexion = conectar_bd_rrhh();
$solicitudes = $conexion->query("SELECT id_solicitud, id_empleado, fecha_inicio, fecha_fin, estado_solicitud 
                                 FROM solicitudes_vacaciones WHERE estado_solicitud = 'pendiente'")
                        ->fetch_all(MYSQLI_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_solicitud = $_POST["id_solicitud"];
    $accion = $_POST["accion"];

    if ($accion == "aprobar") {
        $mensaje = aprobar_solicitud_vacaciones($id_solicitud, $id_empleado_modificador);
    } elseif ($accion == "denegar") {
        $motivo = $_POST["motivo"];
        $mensaje = denegar_solicitud_vacaciones($id_solicitud, $id_empleado_modificador, $motivo);
    }

    header("Location: cambiar_estado_vacaciones.php");
    exit();
}
?>
<h1>Modificar Estado de Vacaciones</h1>
<?php if (empty($solicitudes)): ?>
    <p>No hay solicitudes de vacaciones pendientes.</p>
<?php else: ?>
<!-- 🔹 Listado de solicitudes de vacaciones pendientes -->
<p>Solicitudes de vacaciones pendientes:</p>
<table border="1">
    <tr>
        <th>ID Solicitud</th>
        <th>ID Empleado</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Estado</th>
        <th>Acción</th>
    </tr>
    <?php foreach ($solicitudes as $solicitud): ?>
    <tr>
        <td><?= $solicitud['id_solicitud']; ?></td>
        <td><?= $solicitud['id_empleado']; ?></td>
        <td><?= $solicitud['fecha_inicio']; ?></td>
        <td><?= $solicitud['fecha_fin']; ?></td>
        <td><?= ucfirst($solicitud['estado_solicitud']); ?></td>
        <td>
            <form method="POST" action="cambiar_estado_vacaciones.php">
                <input type="hidden" name="id_solicitud" value="<?= $solicitud['id_solicitud']; ?>">
                <input type="hidden" name="id_empleado_modificador" value="<?= $id_empleado_modificador; ?>">
                <button type="submit" name="accion" value="aprobar">Aprobar</button>
                <button type="submit" name="accion" value="denegar">Denegar</button>
                <input type="text" name="motivo" placeholder="Motivo (si se deniega)">
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
