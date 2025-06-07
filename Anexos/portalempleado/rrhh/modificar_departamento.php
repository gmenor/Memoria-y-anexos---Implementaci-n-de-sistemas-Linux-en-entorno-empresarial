<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar departamento</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>
<?php
include "/var/www/html/portalempleado/anexo.php";

session_start();


$departamentos = consultar_departamentos_rrhh();
$conexion = conectar_bd_rrhh();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_departamento = $_POST["id_departamento"];


    if (isset($_POST["nombre"]) && trim($_POST["nombre"]) !== "") {
        $nombre = $_POST["nombre"];
    } else {
        $consulta = $conexion->prepare("SELECT nombre FROM departamentos WHERE id_departamento = ?");
        $consulta->bind_param("i", $id_departamento);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $fila = $resultado->fetch_assoc();
        $nombre = $fila["nombre"]; // Conservar el nombre actual
        $consulta->close();
    }


    if (isset($_POST["id_responsable"]) && $_POST["id_responsable"] !== "") {
        $id_responsable = $_POST["id_responsable"];
    } else {
        $consulta = $conexion->prepare("SELECT id_responsable FROM departamentos WHERE id_departamento = ?");
        $consulta->bind_param("i", $id_departamento);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $fila = $resultado->fetch_assoc();
        $id_responsable = $fila["id_responsable"]; // Conservar el ID actual
        $consulta->close();
    }

    $mensaje = modificar_departamento_rrhh($id_departamento, $nombre, $id_responsable);

    header("Location: modificar_departamento.php");
    exit();
}
?>


<p>Selecciona un departamento para modificar:</p>
<table border="1">
    <tr>
        <th>ID Departamento</th><th>Nombre</th><th>ID Responsable</th>
    </tr>
    <?php foreach ($departamentos as $departamento): ?>
    <tr>
        <td><?= $departamento['id_departamento']; ?></td>
        <td><?= $departamento['nombre']; ?></td>
        <td><?= !empty($departamento['id_responsable']) ? $departamento['id_responsable'] : 'Sin asignar'; ?></td>
    </tr>
    <?php endforeach; ?>
</table>


<form method="POST" action="modificar_departamento.php">
    <label>ID Departamento a Modificar: <input type="number" name="id_departamento" required></label><br>
    <label>Nuevo Nombre: <input type="text" name="nombre"></label><br>
    <label>ID Responsable: <input type="number" name="id_responsable"></label><br>
    <button type="submit">Modificar Departamento</button>
</form>

<a href="dashboard_rrhh.php">Volver al Dashboard</a>
</body>
</html>
