<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Empleados</title>
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


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["empleados"])) {
    foreach ($_POST["empleados"] as $id_empleado) {
        echo "<p>" . borrar_empleado_rrhh($id_empleado) . "</p>";
    }
    header("Location:Refresh: 0.php");
    exit();
}
?>

<form method="POST" action="borrar_empleado.php">
    <p>Selecciona los empleados que deseas eliminar:</p>
    <table border="1">
        <tr>
            <th>Seleccionar</th><th>ID</th><th>Nombre</th><th>Apellido</th><th>DNI</th>
        </tr>
        <?php foreach ($empleados as $empleado): ?>
        <tr>
            <td><input type="checkbox" name="empleados[]" value="<?= $empleado['id_empleado']; ?>"></td>
            <td><?= $empleado['id_empleado']; ?></td>
            <td><?= $empleado['nombre']; ?></td>
            <td><?= $empleado['apellido']; ?></td>
            <td><?= $empleado['dni']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <button type="submit">Borrar Empleado</button>
</form>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
