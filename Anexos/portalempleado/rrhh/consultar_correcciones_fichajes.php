<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar correcciones de fichajes</title>
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


$correcciones = consultar_correcciones_fichajes_rrhh();

if ($correcciones) {
    echo "<p>Listado de correcciones de fichajes:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID Corrección</th><th>ID Fichaje</th><th>ID Modificador</th><th>Motivo</th><th>Tipo Antiguo</th><th>Tipo Nuevo</th>
                <th>Hora Antigua</th><th>Hora Nueva</th><th>Fecha Modificación</th>
            </tr>";
    foreach ($correcciones as $correccion) {
        echo "<tr>
                <td>{$correccion['id_correccion']}</td>
                <td>{$correccion['id_fichaje']}</td>
                <td>{$correccion['id_empleado_modificador']}</td>
                <td>{$correccion['motivo']}</td>
                <td>{$correccion['tipo_antiguo']}</td>
                <td>{$correccion['tipo_nuevo']}</td>
                <td>{$correccion['hora_antigua']}</td>
                <td>{$correccion['hora_nueva']}</td>
                <td>{$correccion['fecha_modificacion']}</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron correcciones de fichajes.</p>";
}
?>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
