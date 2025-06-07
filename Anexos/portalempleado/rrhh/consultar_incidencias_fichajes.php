<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar incidencias fichajes</title>
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


$incidencias = consultar_incidencias_fichajes();

if ($incidencias) {
    echo "<p>Listado de incidencias de fichajes:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID Incidencia</th><th>ID Fichaje</th><th>Descripción</th>
            </tr>";
    foreach ($incidencias as $incidencia) {
        echo "<tr>
                <td>{$incidencia['id_incidencia']}</td>
                <td>{$incidencia['id_fichaje']}</td>
                <td>{$incidencia['descripcion']}</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron incidencias de fichajes.</p>";
}
?>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
