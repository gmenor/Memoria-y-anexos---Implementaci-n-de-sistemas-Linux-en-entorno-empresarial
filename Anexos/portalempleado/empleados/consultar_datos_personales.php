<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos personales del Empleado</title>
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

$datos = consultar_datos_personales_empleado($_SESSION["id_usuario"]);

if ($datos) {
    echo "<p>Datos personales:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID</th><th>Nombre</th><th>Apellido</th><th>DNI</th><th>Departamento</th><th>Turno</th>
                <th>Puesto</th><th>Teléfono</th><th>UID LDAP</th><th>Cuenta Pidgin</th><th>Fecha Contratación</th>
                <th>Días Vacaciones Disponibles</th>
            </tr>";

    echo "<tr>
            <td>{$datos['id_empleado']}</td>
            <td>{$datos['nombre']}</td>
            <td>{$datos['apellido']}</td>
            <td>{$datos['dni']}</td>
            <td>{$datos['id_departamento']}</td>
            <td>{$datos['id_turno']}</td>
            <td>{$datos['puesto']}</td>
            <td>{$datos['telefono']}</td>
            <td>{$datos['uid_ldap']}</td>
            <td>{$datos['cuenta_pidgin']}</td>
            <td>{$datos['fecha_contratacion']}</td>
            <td>{$datos['dias_vacaciones_disponibles']}</td>
        </tr>";

    echo "</table>";
} else {
    echo "<p>No se encontraron datos personales.</p>";
}
?>

<a href="dashboard.php">Volver al Dashboard</a>
