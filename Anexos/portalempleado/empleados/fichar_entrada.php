<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichar entrada</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>

<?php
include "/var/www/html/portalempleado/anexo.php";
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

if (fichar_entrada($_SESSION["id_usuario"])) {
    echo "<p>Fichaje de entrada registrado correctamente.</p>";
} else {
    echo "<p>Error al fichar entrada.</p>";
}
?>

<a href="dashboard.php">Volver al Dashboard</a>


