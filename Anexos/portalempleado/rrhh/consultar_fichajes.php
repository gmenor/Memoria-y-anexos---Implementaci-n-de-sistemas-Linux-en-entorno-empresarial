<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar fichajes</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>
<?php
include "/var/www/html/portalempleado/anexo.php";

session_start(); // Inicio de sesión


if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}


$fichajes = consultar_fichajes_rrhh();

if ($fichajes) {
    echo "<p>Listado de fichajes:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID Fichaje</th><th>ID Empleado</th><th>Fecha</th><th>Hora</th><th>Tipo</th><th>Modificado</th><th>ID Modificador</th>
            </tr>";
    foreach ($fichajes as $fichaje) {
        echo "<tr>
                <td>{$fichaje['id_fichaje']}</td>
                <td>{$fichaje['id_empleado']}</td>
                <td>{$fichaje['fecha']}</td>
                <td>{$fichaje['hora']}</td>
                <td>{$fichaje['tipo']}</td>
                <td>" . ($fichaje['fichaje_modificado'] ? 'Sí' : 'No') . "</td>
                <td>" . (!empty($fichaje['id_empleado_modificador']) ? $fichaje['id_empleado_modificador'] : 'Sin modificaciones') . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron fichajes.</p>";
}
?>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
