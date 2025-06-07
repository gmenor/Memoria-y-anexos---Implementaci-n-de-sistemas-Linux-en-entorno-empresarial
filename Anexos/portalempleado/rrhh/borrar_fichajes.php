<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Borrar Fichajes</title>
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


$conexion = conectar_bd_rrhh();
$fichajes = $conexion->query("SELECT id_fichaje, id_empleado, fecha, hora, tipo FROM fichajes")
                     ->fetch_all(MYSQLI_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_fichaje = $_POST["id_fichaje"];
    $id_empleado_modificador = $_SESSION["id_usuario"];

    $mensaje = borrar_fichaje($id_fichaje, $id_empleado_modificador);
    header("Location: borrar_fichajes.php");
    exit();
}
?>

<h1>Borrar Fichajes</h1>
<table>
    <tr>
        <th>ID</th><th>Empleado</th><th>Fecha</th><th>Hora</th><th>Tipo</th><th>Acción</th>
    </tr>
    <?php foreach ($fichajes as $fichaje): ?>
    <tr>
        <td><?= $fichaje['id_fichaje']; ?></td>
        <td><?= $fichaje['id_empleado']; ?></td>
        <td><?= $fichaje['fecha']; ?></td>
        <td><?= $fichaje['hora']; ?></td>
        <td><?= ucfirst($fichaje['tipo']); ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="id_fichaje" value="<?= $fichaje['id_fichaje']; ?>">
                <button type="submit">Borrar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>

</body>
</html>
