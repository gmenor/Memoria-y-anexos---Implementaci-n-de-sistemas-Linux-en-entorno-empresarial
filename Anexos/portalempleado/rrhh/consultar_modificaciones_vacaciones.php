<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar modificaciones vacaciones</title>
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


$modificaciones = consultar_modificaciones_estado_vacaciones_rrhh();

if ($modificaciones) {
    echo "<p>Listado de modificaciones en solicitudes de vacaciones:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID Modificación</th><th>ID Solicitud</th><th>ID Modificador</th><th>Estado Anterior</th>
                <th>Estado Nuevo</th><th>Motivo</th><th>Fecha Modificación</th>
            </tr>";
    foreach ($modificaciones as $modificacion) {
        echo "<tr>
                <td>{$modificacion['id_modificacion']}</td>
                <td>{$modificacion['id_solicitud']}</td>
                <td>{$modificacion['id_empleado_modificador']}</td>
                <td>{$modificacion['estado_anterior']}</td>
                <td>{$modificacion['estado_nuevo']}</td>
                <td>{$modificacion['motivo']}</td>
                <td>{$modificacion['fecha_modificacion']}</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron modificaciones en solicitudes de vacaciones.</p>";
}
?>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
