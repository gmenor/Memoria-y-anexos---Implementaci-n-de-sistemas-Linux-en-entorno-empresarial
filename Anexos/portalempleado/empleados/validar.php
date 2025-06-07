<?php
session_start();
$ldap_server = "ldap://172.30.0.2";
$ldap_dn = "dc=planetas,dc=sa";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    $ldap_conn = ldap_connect($ldap_server);
    if (!$ldap_conn) {
        header("Location: login.php?error=conexion");
    }

    ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);

    $ldap_bind = @ldap_bind($ldap_conn, "uid=$usuario,ou=usuarios,$ldap_dn", $password);
    if ($ldap_bind) {
        $_SESSION["usuario"] = $usuario;
        header("Location: dashboard.php");
        exit();
    } else {
         header("Location: login.php?error=credenciales");
    }

    ldap_close($ldap_conn);
}
?>
