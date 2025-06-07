<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar departamento</title>
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
    $nombre = $_POST["nombre"];
    $id_responsable = !empty($_POST["id_responsable"]) ? $_POST["id_responsable"] : null;

    $mensaje = insertar_departamento_rrhh($nombre, $id_responsable);
    echo "<p>$mensaje</p>";
}
?>


<form method="POST" action="insertar_departamento.php">
    <label>Nombre del Departamento: <input type="text" name="nombre" required></label><br>
    <label>ID Responsable (Opcional): <input type="number" name="id_responsable"></label><br>
    <button type="submit">Insertar Departamento</button>
</form>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
