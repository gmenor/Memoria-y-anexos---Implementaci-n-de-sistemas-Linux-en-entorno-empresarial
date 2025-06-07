<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar turno</title>
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST["descripcion"];
    $hora_inicio = $_POST["hora_inicio"];
    $hora_fin = $_POST["hora_fin"];
    $dias_semana = implode(",", $_POST["dias_semana"]); // Convierte array en SET MySQL
    $horario_flexible = isset($_POST["horario_flexible"]) ? 1 : 0;

    $mensaje = insertar_turno($descripcion, $hora_inicio, $hora_fin, $dias_semana, $horario_flexible);
    echo "<p>$mensaje</p>";
}
?>


<form method="POST" action="insertar_turno.php">
    <label>Descripción: <input type="text" name="descripcion" required></label><br>
    <label>Hora de Inicio: <input type="time" name="hora_inicio" required></label><br>
    <label>Hora de Fin: <input type="time" name="hora_fin" required></label><br>
    <label>Días de la Semana:</label><br>
    <input type="checkbox" name="dias_semana[]" value="Lunes"> Lunes
    <input type="checkbox" name="dias_semana[]" value="Martes"> Martes
    <input type="checkbox" name="dias_semana[]" value="Miércoles"> Miércoles
    <input type="checkbox" name="dias_semana[]" value="Jueves"> Jueves
    <input type="checkbox" name="dias_semana[]" value="Viernes"> Viernes
    <input type="checkbox" name="dias_semana[]" value="Sábado"> Sábado
    <input type="checkbox" name="dias_semana[]" value="Domingo"> Domingo<br>
    <label>Horario Flexible: <input type="checkbox" name="horario_flexible"></label><br>
    <button type="submit">Insertar Turno</button>
</form>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
