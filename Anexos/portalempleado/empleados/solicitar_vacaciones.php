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

    $mensaje = solicitar_vacaciones($_SESSION["id_usuario"], $fecha_inicio, $fecha_fin);
    echo "<p>$mensaje</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Vacaciones</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>
    <h1>Solicitar Vacaciones</h1>
    <form method="post">
        <label for="fecha_inicio">Fecha de inicio:</label>
        <input type="date" name="fecha_inicio" required><br>

        <label for="fecha_fin">Fecha de fin:</label>
        <input type="date" name="fecha_fin" required><br>

        <input type="submit" value="Solicitar Vacaciones">
    </form>

    <a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>
