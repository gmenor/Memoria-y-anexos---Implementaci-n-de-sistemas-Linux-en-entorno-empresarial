<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login RRHH</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>
    <h1>Acceso Recursos Humanos</h1>
    <form action="validar_rrhh.php" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required>
        <br><br>
        
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        <br><br>

        <input type="submit" value="Iniciar sesión"></br>
        <a href="../index.php">Volver al Inicio</a>
    </form>
</body>
</html>
<?php
if (isset($_GET["error"])) {
    if ($_GET["error"] == "credenciales") {
        echo "<p style='color:red;'>Usuario o contraseña incorrectos.</p>";
    } elseif ($_GET["error"] == "permisos") {
        echo "<p style='color:red;'>No tienes acceso a Recursos Humanos.</p>";
    } elseif ($_GET["error"] == "conexion") {
        echo "<p style='color:red;'>Error de conexión con el servidor LDAP.</p>";
    } elseif ($_GET["error"] == "usuario_no_encontrado") {
    echo "<p style='color:red;'>Error: Usuario no encontrado en la base de datos.</p>";
}
}
?>
