<?php
include "/var/www/html/portalempleado/anexo.php";
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
obtener_id_empleado_rrhh($_SESSION["usuario"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard RRHH</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION["usuario"]; ?> </h1>
    <p>Selecciona una acción:</p>

    <h2>Inserciones</h2>
        <a href="insertar_empleado.php">Insertar empleado</a></br>
        <a href="insertar_turno.php">Insertar turno</a></br>
        <a href="insertar_departamento.php">Insertar departamento</a></br>

    <h2>Modificaciones</h2>
        <a href="modificar_empleado.php">Modificar empleado</a></br>
        <a href="modificar_turno.php">Modificar turno</a></br>
        <a href="modificar_departamento.php">Modificar departamento</a></br>
        <a href="modificar_fichaje.php">Modificar fichaje</a></br>
        <a href="cambiar_estado_vacaciones.php">Cambiar estado de solicitudes de vacaciones</a></br>
	<a href="justificar_ausencia.php">Justificar Ausencias</a></br>

    <h2>Borrado</h2>
        <a href="borrar_empleado.php">Borrar empleado</a></br>
        <a href="borrar_departamento.php">Borrar departamento</a></br>
        <a href="borrar_turno.php">Borrar turno</a></br>
	<a href="borrar_fichajes.php">Borrar Fichajes</a></br>

    <h2>Consultas</h2>
        <a href="consultar_fichajes.php">Consultar fichajes</a></br>
	<a href="consultar_turnos.php">Consultar turnos</a></br>
	<a href="consultar_departamentos.php">Consultar departamentos</a></br>
        <a href="consultar_ausencias.php">Consultar ausencias</a></br>
        <a href="consultar_solicitudes_vacaciones.php">Consultar solicitudes de vacaciones</a></br>
        <a href="consultar_datos_personales.php">Consultar datos personales</a></br>
        <a href="consultar_incidencias_fichajes.php">Consultar incidencias de fichajes</a></br>
        <a href="consultar_correcciones_fichajes.php">Consultar correcciones de fichajes</a></br>
        <a href="consultar_modificaciones_vacaciones.php">Consultar modificaciones de vacaciones</a></br>

    <h2>Sesión</h2>
        <a href="../../logout.php">Cerrar sesión</a></br>
</body>
</html>
