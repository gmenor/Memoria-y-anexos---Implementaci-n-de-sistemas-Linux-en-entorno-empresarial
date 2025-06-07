<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar departamentos</title>
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


$departamentos = consultar_departamentos_rrhh();

if ($departamentos) {
    echo "<p>Listado de departamentos:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID Departamento</th><th>Nombre</th><th>ID Responsable</th>
            </tr>";
    foreach ($departamentos as $departamento) {
        echo "<tr>
                <td>{$departamento['id_departamento']}</td>
                <td>{$departamento['nombre']}</td>
                <td>" . (!empty($departamento['id_responsable']) ? $departamento['id_responsable'] : 'Sin asignar') . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron departamentos.</p>";
}
?>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
