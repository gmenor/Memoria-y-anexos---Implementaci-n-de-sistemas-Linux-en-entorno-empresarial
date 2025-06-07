<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Turno</title>
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


$turnos = consultar_turnos_rrhh();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["turnos"])) {
    foreach ($_POST["turnos"] as $id_turno) {
        echo "<p>" . borrar_turno_rrhh($id_turno) . "</p>";
    }
    header("Refresh: 0");
    exit();
}
?>


<form method="POST" action="borrar_turno.php">
    <p>Selecciona los turnos que deseas eliminar:</p>
    <table border="1">
        <tr>
            <th>Seleccionar</th><th>ID Turno</th><th>Descripción</th><th>Hora Inicio</th><th>Hora Fin</th><th>Días Semana</th><th>Horario Flexible</th>
        </tr>
        <?php foreach ($turnos as $turno): ?>
        <tr>
            <td><input type="checkbox" name="turnos[]" value="<?= $turno['id_turno']; ?>"></td>
            <td><?= $turno['id_turno']; ?></td>
            <td><?= $turno['descripcion']; ?></td>
            <td><?= $turno['hora_inicio']; ?></td>
            <td><?= $turno['hora_fin']; ?></td>
            <td><?= $turno['dias_semana']; ?></td>
            <td><?= $turno['horario_flexible'] ? 'Sí' : 'No'; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <button type="submit">Borrar Turno</button>
</form>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
