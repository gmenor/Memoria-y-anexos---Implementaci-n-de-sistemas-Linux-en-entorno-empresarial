<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar empleado</title>
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $id_departamento = $_POST["id_departamento"];
    $id_turno = $_POST["id_turno"];
    $puesto = $_POST["puesto"];
    $telefono = $_POST["telefono"];
    $uid_ldap = $_POST["uid_ldap"];
    $cuenta_pidgin = $_POST["cuenta_pidgin"];
    $fecha_contratacion = $_POST["fecha_contratacion"];

    $mensaje = insertar_empleado($nombre, $apellido, $dni, $id_departamento, $id_turno, $puesto, $telefono, $uid_ldap, $cuenta_pidgin, $fecha_contratacion);
    echo "<p>$mensaje</p>";
}
?>


<form method="POST" action="insertar_empleado.php">
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Apellido: <input type="text" name="apellido" required></label><br>
    <label>DNI: <input type="text" name="dni" required></label><br>
    <label>Departamento: <input type="number" name="id_departamento" required></label><br>
    <label>Turno: <input type="number" name="id_turno" required></label><br>
    <label>Puesto: <input type="text" name="puesto"></label><br>
    <label>Teléfono: <input type="text" name="telefono"></label><br>
    <label>UID LDAP: <input type="text" name="uid_ldap" required></label><br>
    <label>Cuenta Pidgin: <input type="text" name="cuenta_pidgin" required></label><br>
    <label>Fecha de Contratación: <input type="date" name="fecha_contratacion" required></label><br>
    <button type="submit">Insertar Empleado</button>
</form>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
