<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar turnos</title>
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


$turnos = consultar_turnos_rrhh();

if ($turnos) {
    echo "<p>Listado de turnos:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID Turno</th><th>Descripción</th><th>Hora Inicio</th><th>Hora Fin</th><th>Días Semana</th><th>Horario Flexible</th>
            </tr>";
    foreach ($turnos as $turno) {
        echo "<tr>
                <td>{$turno['id_turno']}</td>
                <td>{$turno['descripcion']}</td>
                <td>{$turno['hora_inicio']}</td>
                <td>{$turno['hora_fin']}</td>
                <td>{$turno['dias_semana']}</td>
                <td>" . ($turno['horario_flexible'] ? 'Sí' : 'No') . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron turnos.</p>";
}
?>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
