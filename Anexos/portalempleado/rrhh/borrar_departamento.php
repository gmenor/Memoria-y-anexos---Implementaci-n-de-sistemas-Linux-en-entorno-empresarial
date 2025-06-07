<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Departamentos</title>
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


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["departamentos"])) {
    foreach ($_POST["departamentos"] as $id_departamento) {
        echo "<p>" . borrar_departamento_rrhh($id_departamento) . "</p>";
    }
    header("Refresh: 0");
    exit();
}
?>

<form method="POST" action="borrar_departamento.php">
    <p>Selecciona los departamentos que deseas eliminar:</p>
    <table border="1">
        <tr>
            <th>Seleccionar</th><th>ID Departamento</th><th>Nombre</th><th>ID Responsable</th>
        </tr>
        <?php foreach ($departamentos as $departamento): ?>
        <tr>
            <td><input type="checkbox" name="departamentos[]" value="<?= $departamento['id_departamento']; ?>"></td>
            <td><?= $departamento['id_departamento']; ?></td>
            <td><?= $departamento['nombre']; ?></td>
            <td><?= !empty($departamento['id_responsable']) ? $departamento['id_responsable'] : 'Sin asignar'; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <button type="submit">Borrar Departamento</button>
</form>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
