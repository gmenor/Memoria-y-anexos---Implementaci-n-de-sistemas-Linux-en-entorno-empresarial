<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar datos personales</title>
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


$empleados = consultar_datos_personales_rrhh();

if ($empleados) {
    echo "<p>Listado de empleados:</p>";
    echo "<table border='1'>
            <tr>
                <th>ID</th><th>Nombre</th><th>Apellido</th><th>DNI</th><th>Departamento</th><th>Turno</th>
                <th>Puesto</th><th>Teléfono</th><th>UID LDAP</th><th>Cuenta Pidgin</th><th>Fecha de Contratación</th>
                <th>Días Vacaciones Disponibles</th>
            </tr>";
    foreach ($empleados as $empleado) {
        echo "<tr>
                <td>{$empleado['id_empleado']}</td>
                <td>{$empleado['nombre']}</td>
                <td>{$empleado['apellido']}</td>
                <td>{$empleado['dni']}</td>
                <td>{$empleado['id_departamento']}</td>
                <td>{$empleado['id_turno']}</td>
                <td>{$empleado['puesto']}</td>
                <td>{$empleado['telefono']}</td>
                <td>{$empleado['uid_ldap']}</td>
                <td>{$empleado['cuenta_pidgin']}</td>
                <td>{$empleado['fecha_contratacion']}</td>
                <td>{$empleado['dias_vacaciones_disponibles']}</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron empleados.</p>";
}
?>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
