<?php
include "/var/www/html/portalempleado/anexo.php";
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
obtener_id_empleado($_SESSION["usuario"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Empleados</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION["usuario"]; ?></h1>
    <p>Selecciona una acción:</p>

    <h2>Inserciones</h2>
        <a href="fichar_entrada.php">Fichar entrada</a></br>
        <a href="fichar_salida.php">Fichar salida</a></br>
        <a href="notificar_ausencia.php">Notificar futura ausencia</a></br>
        <a href="solicitar_vacaciones.php">Solicitar vacaciones</a></br>

    <h2>Consultas</h2>
        <a href="consultar_fichajes.php">Consultar fichajes</a></br>
        <a href="consultar_ausencias.php">Consultar ausencias</a></br>
        <a href="consultar_solicitudes_vacaciones.php">Consultar solicitudes de vacaciones</a></br>
        <a href="consultar_datos_personales.php">Consultar datos personales</a></br>

    <h2>Sesión</h2>
        <a href="../../logout.php">Cerrar sesión</a></br>
</body>
</html>
