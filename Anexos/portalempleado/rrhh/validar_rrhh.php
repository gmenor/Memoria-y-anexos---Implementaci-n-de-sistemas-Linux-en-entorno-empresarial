<?php
session_start();
$ldap_server = "ldap://172.30.0.2";
$ldap_dn = "ou=usuarios,dc=planetas,dc=sa";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];


    $ldap_conn = ldap_connect($ldap_server);
    if (!$ldap_conn) {
        header("Location: login_rrhh.php?error=conexion");
        exit();
    }

    ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);


    $user_dn = "uid=$usuario,$ldap_dn";
    $ldap_bind = @ldap_bind($ldap_conn, $user_dn, $password);

    if ($ldap_bind) {

        $search = ldap_search($ldap_conn, "ou=usuarios,dc=planetas,dc=sa", "(uid=$usuario)");
        $entries = ldap_get_entries($ldap_conn, $search);

        if ($entries["count"] > 0) {
            $gid = isset($entries[0]["gidnumber"][0]) ? $entries[0]["gidnumber"][0] : null;

            if ($gid === "1002") {
                $_SESSION["usuario"] = $usuario;
                header("Location: dashboard_rrhh.php"); // Redirigir al panel RRHH
                exit();
            } else {
                header("Location: login_rrhh.php?error=permisos");
                exit();
            }
        } else {
            header("Location: login_rrhh.php?error=usuario_no_encontrado");
            exit();
        }
    } else {
        header("Location: login_rrhh.php?error=credenciales");
        exit();
    }

    ldap_close($ldap_conn);
}
?>
