<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar fichaje</title>
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


if (!isset($_SESSION["id_usuario"]) || empty($_SESSION["id_usuario"])) {
    die("Error: No se pudo obtener el ID del usuario que realiza la modificación.");
}


$id_empleado_modificador = $_SESSION["id_usuario"];


$fichajes = consultar_fichajes_rrhh();
$conexion = conectar_bd_rrhh(); 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_fichaje = $_POST["id_fichaje"];


    $consulta = $conexion->prepare("SELECT fecha, hora, tipo FROM fichajes WHERE id_fichaje = ?");
    $consulta->bind_param("i", $id_fichaje);
    $consulta->execute();
    $resultado = $consulta->get_result();
    $fila = $resultado->fetch_assoc();

    $fecha = isset($_POST["fecha"]) && $_POST["fecha"] !== "" ? $_POST["fecha"] : $fila["fecha"];
    $hora = isset($_POST["hora"]) && $_POST["hora"] !== "" ? $_POST["hora"] : $fila["hora"];
    $tipo = isset($_POST["tipo"]) && $_POST["tipo"] !== "" ? $_POST["tipo"] : $fila["tipo"];

    $consulta->close();


    if (empty($id_empleado_modificador)) {
        $id_empleado_modificador = 1; 
    }


    error_log("ID Usuario en sesión: " . ($_SESSION["id_usuario"] ?? "No definido"));
    error_log("ID Empleado Modificador recibido en POST: " . ($_POST["id_empleado_modificador"] ?? "No definido"));
    error_log("ID Empleado Modificador antes de llamar al procedimiento: " . ($id_empleado_modificador ?? "No definido"));


    $mensaje = modificar_fichaje_rrhh($id_fichaje, $fecha, $hora, $tipo, $id_empleado_modificador);

    header("Location: modificar_fichaje.php");
    exit();
}

?>


<p>Selecciona un fichaje para modificar:</p>
<table border="1">
    <tr>
        <th>ID Fichaje</th><th>ID Empleado</th><th>Fecha</th><th>Hora</th><th>Tipo</th><th>Modificado</th><th>ID Modificador</th>
    </tr>
    <?php foreach ($fichajes as $fichaje): ?>
    <tr>
        <td><?= $fichaje['id_fichaje']; ?></td>
        <td><?= $fichaje['id_empleado']; ?></td>
        <td><?= $fichaje['fecha']; ?></td>
        <td><?= $fichaje['hora']; ?></td>
        <td><?= ucfirst($fichaje['tipo']); ?></td>
        <td><?= $fichaje['fichaje_modificado'] ? 'Sí' : 'No'; ?></td>
        <td><?= !empty($fichaje['id_empleado_modificador']) ? $fichaje['id_empleado_modificador'] : 'Sin modificaciones'; ?></td>
    </tr>
    <?php endforeach; ?>
</table>


<form method="POST" action="modificar_fichaje.php">
    <label>ID Fichaje a Modificar: <input type="number" name="id_fichaje" required></label><br>
    <label>Fecha: <input type="date" name="fecha"></label><br>
    <label>Hora: <input type="time" name="hora"></label><br>
    <label>Tipo:</label>
    <select name="tipo">
        <option value="entrada">Entrada</option>
        <option value="salida">Salida</option>
    </select><br>


    <input type="hidden" name="id_empleado_modificador" value="<?= $id_empleado_modificador; ?>">
    <button type="submit">Modificar Fichaje</button>
</form>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
