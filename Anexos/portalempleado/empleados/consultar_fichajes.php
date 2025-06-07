<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichajes del Empleado</title>
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

$fichajes = consultar_fichajes_empleado($_SESSION["id_usuario"]);

if ($fichajes) {
    echo "<p>Fichajes encontrados:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID</th><th>Empleado</th><th>Fecha</th><th>Hora</th><th>Tipo</th><th>Modificado</th>
            </tr>";
    foreach ($fichajes as $fichaje) {
        echo "<tr>
                <td>{$fichaje['id_fichaje']}</td>
                <td>{$fichaje['id_empleado']}</td>
                <td>{$fichaje['fecha']}</td>
                <td>{$fichaje['hora']}</td>
                <td>{$fichaje['tipo']}</td>
                <td>" . ($fichaje['fichaje_modificado'] ? 'Sí' : 'No') . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron fichajes.</p>";
}
?>

<a href="dashboard.php">Volver al Dashboard</a>
