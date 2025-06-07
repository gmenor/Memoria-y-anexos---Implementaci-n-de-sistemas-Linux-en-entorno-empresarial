<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar ausencias</title>
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


$ausencias = consultar_ausencias_rrhh();

if ($ausencias) {
    echo "<p>Listado de ausencias:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID Ausencia</th><th>ID Empleado</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Motivo</th><th>Justificada</th>
            </tr>";
    foreach ($ausencias as $ausencia) {
        echo "<tr>
                <td>{$ausencia['id_ausencia']}</td>
                <td>{$ausencia['id_empleado']}</td>
                <td>{$ausencia['fecha_inicio']}</td>
                <td>{$ausencia['fecha_fin']}</td>
                <td>{$ausencia['motivo']}</td>
                <td>" . ($ausencia['justificada'] ? 'Sí' : 'No') . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron ausencias.</p>";
}
?>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
