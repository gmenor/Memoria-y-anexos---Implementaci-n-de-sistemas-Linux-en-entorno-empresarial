<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar empleado</title>
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_empleado = $_POST["id_empleado"];

    // Filtrar solo los valores ingresados para evitar sobrescribir con NULL
    $nombre = isset($_POST["nombre"]) && $_POST["nombre"] !== "" ? $_POST["nombre"] : null;
    $apellido = isset($_POST["apellido"]) && $_POST["apellido"] !== "" ? $_POST["apellido"] : null;
    $dni = isset($_POST["dni"]) && $_POST["dni"] !== "" ? $_POST["dni"] : null;
    $id_departamento = isset($_POST["id_departamento"]) && $_POST["id_departamento"] !== "" ? $_POST["id_departamento"] : null;
    $id_turno = isset($_POST["id_turno"]) && $_POST["id_turno"] !== "" ? $_POST["id_turno"] : null;
    $puesto = isset($_POST["puesto"]) && $_POST["puesto"] !== "" ? $_POST["puesto"] : null;
    $telefono = isset($_POST["telefono"]) && $_POST["telefono"] !== "" ? $_POST["telefono"] : null;
    $uid_ldap = isset($_POST["uid_ldap"]) && $_POST["uid_ldap"] !== "" ? $_POST["uid_ldap"] : null;
    $cuenta_pidgin = isset($_POST["cuenta_pidgin"]) && $_POST["cuenta_pidgin"] !== "" ? $_POST["cuenta_pidgin"] : null;
    $fecha_contratacion = isset($_POST["fecha_contratacion"]) && $_POST["fecha_contratacion"] !== "" ? $_POST["fecha_contratacion"] : null;

    $mensaje = modificar_empleado_rrhh($id_empleado, $nombre, $apellido, $dni, $id_departamento, $id_turno, $puesto, $telefono, $uid_ldap, $cuenta_pidgin, $fecha_contratacion);
    echo "<p>$mensaje</p>";
    header("Refresh: 0");
}
?>


<p>Selecciona un empleado para modificar:</p>
<table border="1">
    <tr>
        <th>ID</th><th>Nombre</th><th>Apellido</th><th>DNI</th><th>Departamento</th><th>Turno</th><th>Puesto</th><th>Teléfono</th><th>UID LDAP</th><th>Cuenta Pidgin</th><th>Fecha de Contratación</th>
    </tr>
    <?php foreach ($empleados as $empleado): ?>
    <tr>
        <td><?= $empleado['id_empleado']; ?></td>
        <td><?= $empleado['nombre']; ?></td>
        <td><?= $empleado['apellido']; ?></td>
        <td><?= $empleado['dni']; ?></td>
        <td><?= $empleado['id_departamento']; ?></td>
        <td><?= $empleado['id_turno']; ?></td>
        <td><?= $empleado['puesto']; ?></td>
        <td><?= $empleado['telefono']; ?></td>
        <td><?= $empleado['uid_ldap']; ?></td>
        <td><?= $empleado['cuenta_pidgin']; ?></td>
        <td><?= $empleado['fecha_contratacion']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>


<form method="POST" action="modificar_empleado.php">
    <label>ID Empleado a Modificar: <input type="number" name="id_empleado" required></label><br>
    <label>Nombre: <input type="text" name="nombre"></label><br>
    <label>Apellido: <input type="text" name="apellido"></label><br>
    <label>DNI: <input type="text" name="dni"></label><br>
    <label>ID Departamento: <input type="number" name="id_departamento"></label><br>
    <label>ID Turno: <input type="number" name="id_turno"></label><br>
    <label>Puesto: <input type="text" name="puesto"></label><br>
    <label>Teléfono: <input type="text" name="telefono"></label><br>
    <label>UID LDAP: <input type="text" name="uid_ldap"></label><br>
    <label>Cuenta Pidgin: <input type="text" name="cuenta_pidgin"></label><br>
    <label>Fecha Contratación: <input type="date" name="fecha_contratacion"></label><br>
    <button type="submit">Modificar Empleado</button>
</form>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
