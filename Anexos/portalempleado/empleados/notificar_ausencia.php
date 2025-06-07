<?php
include "/var/www/html/portalempleado/anexo.php";
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_inicio = $_POST["fecha_inicio"];
    $fecha_fin = $_POST["fecha_fin"];
    $motivo = $_POST["motivo"];
    $justificada = isset($_POST["justificada"]) ? 1 : 0;

    if (notificar_ausencia($_SESSION["id_usuario"], $fecha_inicio, $fecha_fin, $motivo, $justificada)) {
        echo "<p>Ausencia notificada correctamente.</p>";
    } else {
        echo "<p>Error al notificar ausencia.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificar Ausencia</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>
    <h1>Notificar Ausencia</h1>
    <form method="post">
        <label for="fecha_inicio">Fecha de inicio:</label>
        <input type="date" name="fecha_inicio" required><br>

        <label for="fecha_fin">Fecha de fin:</label>
        <input type="date" name="fecha_fin" required><br>

        <label for="motivo">Motivo:</label>
        <textarea name="motivo" required></textarea><br>

        <label for="justificada">¿Está justificada?</label>
        <input type="checkbox" name="justificada"><br>

        <input type="submit" value="Notificar Ausencia">
    </form>

    <a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>
