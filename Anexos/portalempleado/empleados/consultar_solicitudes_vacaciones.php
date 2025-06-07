<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de vacaciones del Empleado</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>

<?php
include "/var/www/html/portalempleado/anexo.php";
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}


$solicitudes = consultar_solicitudes_vacaciones_empleado($_SESSION["id_usuario"]);

if ($solicitudes) {
    echo "<p>Solicitudes de vacaciones encontradas:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID</th><th>Empleado</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Estado</th>
            </tr>";
    foreach ($solicitudes as $solicitud) {
        echo "<tr>
                <td>{$solicitud['id_solicitud']}</td>
                <td>{$solicitud['id_empleado']}</td>
                <td>{$solicitud['fecha_inicio']}</td>
                <td>{$solicitud['fecha_fin']}</td>
                <td>{$solicitud['estado_solicitud']}</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron solicitudes de vacaciones.</p>";
}
?>

<a href="dashboard.php">Volver al Dashboard</a>
