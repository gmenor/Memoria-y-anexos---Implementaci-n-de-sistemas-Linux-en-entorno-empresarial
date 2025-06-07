<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar turno</title>
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_turno = $_POST["id_turno"];

    // Filtrar valores para evitar sobrescribir con NULL
    $descripcion = isset($_POST["descripcion"]) && $_POST["descripcion"] !== "" ? $_POST["descripcion"] : null;
    $hora_inicio = isset($_POST["hora_inicio"]) && $_POST["hora_inicio"] !== "" ? $_POST["hora_inicio"] : null;
    $hora_fin = isset($_POST["hora_fin"]) && $_POST["hora_fin"] !== "" ? $_POST["hora_fin"] : null;
    $dias_semana = isset($_POST["dias_semana"]) ? implode(",", $_POST["dias_semana"]) : null;
    $horario_flexible = isset($_POST["horario_flexible"]) ? 1 : 0;

    $mensaje = modificar_turno_rrhh($id_turno, $descripcion, $hora_inicio, $hora_fin, $dias_semana, $horario_flexible);
    
    header("Location: modificar_turno.php");
    exit();
}
?>

<!-- Listado de turnos -->
<p>Selecciona un turno para modificar:</p>
<table border="1">
    <tr>
        <th>ID</th><th>Descripción</th><th>Hora Inicio</th><th>Hora Fin</th><th>Días de Semana</th><th>Horario Flexible</th>
    </tr>
    <?php foreach ($turnos as $turno): ?>
    <tr>
        <td><?= $turno['id_turno']; ?></td>
        <td><?= $turno['descripcion']; ?></td>
        <td><?= $turno['hora_inicio']; ?></td>
        <td><?= $turno['hora_fin']; ?></td>
        <td><?= $turno['dias_semana']; ?></td>
        <td><?= $turno['horario_flexible'] ? 'Sí' : 'No'; ?></td>
    </tr>
    <?php endforeach; ?>
</table>


<form method="POST" action="modificar_turno.php">
    <label>ID Turno a Modificar: <input type="number" name="id_turno" required></label><br>
    <label>Descripción: <input type="text" name="descripcion"></label><br>
    <label>Hora Inicio: <input type="time" name="hora_inicio"></label><br>
    <label>Hora Fin: <input type="time" name="hora_fin"></label><br>

    <label>Días de la Semana:</label><br>
    <input type="checkbox" name="dias_semana[]" value="Lunes"> Lunes
    <input type="checkbox" name="dias_semana[]" value="Martes"> Martes
    <input type="checkbox" name="dias_semana[]" value="Miércoles"> Miércoles
    <input type="checkbox" name="dias_semana[]" value="Jueves"> Jueves
    <input type="checkbox" name="dias_semana[]" value="Viernes"> Viernes
    <input type="checkbox" name="dias_semana[]" value="Sábado"> Sábado
    <input type="checkbox" name="dias_semana[]" value="Domingo"> Domingo<br>

    <label>Horario Flexible: <input type="checkbox" name="horario_flexible"></label><br>
    <button type="submit">Modificar Turno</button>
</form>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
