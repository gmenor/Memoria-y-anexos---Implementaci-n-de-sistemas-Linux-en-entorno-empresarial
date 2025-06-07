<?php
session_start();
//user-db
function conectar_db_empleado() {
    $host = "localhost";
    $usuario = "user-db";
    $password = "planetas-sa-2025-user-db";
    $bd = "planetas_sa";
    
    $conexion = new mysqli($host, $usuario, $password, $bd);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    return $conexion;
}

function obtener_id_empleado($usuario) {
    $db_host = "localhost"; 
    $db_user = "user-db";      
    $db_pass = "planetas-sa-2025-user-db"; 
    $db_name = "planetas_sa"; 

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        return ["error" => "No se pudo conectar a la base de datos: " . $conn->connect_error];
    }

    $sql = "SELECT id_empleado FROM empleados WHERE uid_ldap = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $datos = $result->fetch_assoc();
	$_SESSION["id_usuario"] = $datos["id_empleado"];
        $stmt->close();
        $conn->close();
	return $_SESSION["id_usuario"];
    } else {
        $stmt->close();
        $conn->close();
        header("Location: login.php?error=usuario_no_encontrado");
        exit();
    }
}
function fichar_entrada($id_usuario) {
    $db_host = "localhost";
    $db_user = "user-db";
    $db_pass = "planetas-sa-2025-user-db";
    $db_name = "planetas_sa";

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);


    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("CALL insertar_fichaje(?, CURDATE(), CURTIME(), 'entrada')");
    $stmt->bind_param("i", $id_usuario);

    $resultado = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $resultado;
}

function fichar_salida($id_empleado) {
    $db_host = "localhost";
    $db_user = "user-db";
    $db_pass = "planetas-sa-2025-user-db";
    $db_name = "planetas_sa";

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);


    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("CALL insertar_fichaje(?, CURDATE(), CURTIME(), 'salida')");
    $stmt->bind_param("i", $id_empleado);

    $resultado = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $resultado;
}
function notificar_ausencia($id_empleado, $fecha_inicio, $fecha_fin, $motivo, $justificada) {
    $db_host = "localhost";
    $db_user = "user-db";
    $db_pass = "planetas-sa-2025-user-db";
    $db_name = "planetas_sa";


    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }


    $stmt = $conn->prepare("CALL insertar_ausencia(?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $id_empleado, $fecha_inicio, $fecha_fin, $motivo, $justificada);


    $resultado = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $resultado;
}

function solicitar_vacaciones($id_empleado, $fecha_inicio, $fecha_fin) {
    $db_host = "localhost";
    $db_user = "user-db";
    $db_pass = "planetas-sa-2025-user-db";
    $db_name = "planetas_sa";

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("CALL insertar_solicitud_vacaciones(?, ?, ?)");
    $stmt->bind_param("iss", $id_empleado, $fecha_inicio, $fecha_fin);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $mensaje = $result->fetch_assoc()["mensaje"];
    } else {
        $mensaje = "Error al insertar solicitud de vacaciones.";
    }

    $stmt->close();
    $conn->close();

    return $mensaje;
}

function consultar_fichajes_empleado($id_usuario) {
    $conexion = conectar_db_empleado();
    $stmt = $conexion->prepare("CALL consultar_fichajes(?)");
    $stmt->bind_param("s", $id_usuario);
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $fichajes = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $fichajes;
}

function consultar_ausencias_empleado($id_usuario) {
    $conexion = conectar_db_empleado();
    $stmt = $conexion->prepare("CALL consultar_ausencias(?)");
    $stmt->bind_param("s", $id_usuario);
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $ausencias = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $ausencias;
}

function consultar_solicitudes_vacaciones_empleado($id_usuario) {
    $conexion = conectar_db_empleado();
    $stmt = $conexion->prepare("CALL consultar_solicitudes_vacaciones(?)");
    $stmt->bind_param("s", $id_usuario);
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $solicitudes = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $solicitudes;
}

function consultar_datos_personales_empleado($id_usuario) {
    $conexion = conectar_db_empleado();
    $stmt = $conexion->prepare("CALL consultar_datos_personales(?)");
    $stmt->bind_param("s", $id_usuario);
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $datos = $resultado->fetch_assoc();

    $stmt->close();
    $conexion->close();

    return $datos;
}

//rrhh-db
function conectar_bd_rrhh() {
    $host = "localhost";
    $usuario = "rrhh-db";
    $password = "planetas-sa-2025-rrhh-db";
    $bd = "planetas_sa";
    
    $conexion = new mysqli($host, $usuario, $password, $bd);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    return $conexion;
}

function obtener_id_empleado_rrhh($usuario) {
    $db_host = "localhost";
    $db_user = "rrhh-db";
    $db_pass = "planetas-sa-2025-rrhh-db";
    $db_name = "planetas_sa";

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        return ["error" => "No se pudo conectar a la base de datos: " . $conn->connect_error];
    }

    $sql = "SELECT id_empleado FROM empleados WHERE uid_ldap = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $datos = $result->fetch_assoc();
        $_SESSION["id_usuario"] = $datos["id_empleado"];
        $stmt->close();
        $conn->close();
        return $_SESSION["id_usuario"];
    } else {
        $stmt->close();
        $conn->close();
        header("Location: login.php?error=usuario_no_encontrado"); 
        exit();
    }
}

function consultar_fichajes_rrhh() {
    $conexion = conectar_bd_rrhh();
    $stmt = $conexion->prepare("CALL consultar_fichajes('*')");
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $fichajes = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $fichajes;
}

function consultar_ausencias_rrhh() {
    $conexion = conectar_bd_rrhh();
    $stmt = $conexion->prepare("CALL consultar_ausencias('*')");
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $ausencias = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $ausencias;
}

function consultar_solicitudes_vacaciones_rrhh() {
    $conexion = conectar_bd_rrhh();
    $stmt = $conexion->prepare("CALL consultar_solicitudes_vacaciones('*')");
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $solicitudes = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $solicitudes;
}

function consultar_datos_personales_rrhh() {
    $conexion = conectar_bd_rrhh(); 
    $stmt = $conexion->prepare("CALL consultar_datos_personales('*')");
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $empleados = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $empleados;
}

function insertar_empleado($nombre, $apellido, $dni, $id_departamento, $id_turno, $puesto, $telefono, $uid_ldap, $cuenta_pidgin, $fecha_contratacion) {
    $conexion = conectar_bd_rrhh(); 
    
    
    $stmt = $conexion->prepare("CALL insertar_empleado(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiisssss", $nombre, $apellido, $dni, $id_departamento, $id_turno, $puesto, $telefono, $uid_ldap, $cuenta_pidgin, $fecha_contratacion);
    
    if ($stmt->execute()) {
        $mensaje = "Empleado insertado correctamente.";
    } else {
        $mensaje = "Error al insertar empleado: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();

    return $mensaje;
}

function insertar_turno($descripcion, $hora_inicio, $hora_fin, $dias_semana, $horario_flexible) {
    $conexion = conectar_bd_rrhh(); 
    
    $stmt = $conexion->prepare("CALL insertar_turno(?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $descripcion, $hora_inicio, $hora_fin, $dias_semana, $horario_flexible);
    
    if ($stmt->execute()) {
        $mensaje = "Turno insertado correctamente.";
    } else {
        $mensaje = "Error al insertar turno: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();

    return $mensaje;
}


function consultar_incidencias_fichajes() {
    $conexion = conectar_bd_rrhh(); 
    
    
    $stmt = $conexion->prepare("CALL consultar_incidencias_fichajes()");
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $incidencias = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $incidencias;
}

function consultar_correcciones_fichajes_rrhh() {
    $conexion = conectar_bd_rrhh(); 
    
    
    $stmt = $conexion->prepare("CALL consultar_correcciones_fichajes('*')");
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $correcciones = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $correcciones;
}

function consultar_modificaciones_estado_vacaciones_rrhh() {
    $conexion = conectar_bd_rrhh(); 
    
    
    $stmt = $conexion->prepare("CALL consultar_modificaciones_estado_vacaciones()");
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $modificaciones = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $modificaciones;
}

function borrar_empleado_rrhh($id_empleado) {
    $conexion = conectar_bd_rrhh(); 
    

    $stmt = $conexion->prepare("CALL borrar_empleado(?)");
    $stmt->bind_param("i", $id_empleado);
    
    if ($stmt->execute()) {
        $mensaje = "Empleado con ID $id_empleado eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar empleado con ID $id_empleado: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();

    return $mensaje;
}

function consultar_turnos_rrhh() {
    $conexion = conectar_bd_rrhh(); 
    
 
    $stmt = $conexion->prepare("CALL consultar_turnos()");
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $turnos = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $turnos;
}

function consultar_departamentos_rrhh() {
    $conexion = conectar_bd_rrhh();
    

    $stmt = $conexion->prepare("CALL consultar_departamentos()");
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $departamentos = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conexion->close();

    return $departamentos;
}

function borrar_departamento_rrhh($id_departamento) {
    $conexion = conectar_bd_rrhh(); 
    
 
    $stmt = $conexion->prepare("CALL borrar_departamento(?)");
    $stmt->bind_param("i", $id_departamento);
    
    if ($stmt->execute()) {
        $mensaje = "Departamento con ID $id_departamento eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar departamento con ID $id_departamento: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();

    return $mensaje;
}

function borrar_turno_rrhh($id_turno) {
    $conexion = conectar_bd_rrhh();
    

    $stmt = $conexion->prepare("CALL borrar_turno(?)");
    $stmt->bind_param("i", $id_turno);
    
    if ($stmt->execute()) {
        $mensaje = "Turno con ID $id_turno eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar turno con ID $id_turno: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();

    return $mensaje;
}

function insertar_departamento_rrhh($nombre, $id_responsable) {
    $conexion = conectar_bd_rrhh();
   
    
    $stmt = $conexion->prepare("CALL insertar_departamento(?, ?)");
    $stmt->bind_param("si", $nombre, $id_responsable);
    
    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        $mensaje = $resultado->fetch_assoc()['mensaje'];
    } else {
        $mensaje = "Error al insertar departamento: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();

    return $mensaje;
}

function modificar_empleado_rrhh($id_empleado, $nombre, $apellido, $dni, $id_departamento, $id_turno, $puesto, $telefono, $uid_ldap, $cuenta_pidgin, $fecha_contratacion) {
    $conexion = conectar_bd_rrhh(); 
    
    // Recuperar valores actuales del empleado
    $stmt = $conexion->prepare("SELECT nombre, apellido, dni, id_departamento, id_turno, puesto, telefono, uid_ldap, cuenta_pidgin, fecha_contratacion FROM empleados WHERE id_empleado = ?");
    $stmt->bind_param("i", $id_empleado);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    // Verificar si el empleado existe
    if ($resultado->num_rows === 0) {
        return "Error: El empleado no existe.";
    }

    // Obtener los datos actuales
    $empleado_actual = $resultado->fetch_assoc();
    $stmt->close();

    // Usar los valores actuales si no se han enviado modificaciones
    $nombre = !empty($nombre) ? $nombre : $empleado_actual["nombre"];
    $apellido = !empty($apellido) ? $apellido : $empleado_actual["apellido"];
    $dni = !empty($dni) ? $dni : $empleado_actual["dni"];
    $id_departamento = !empty($id_departamento) ? $id_departamento : $empleado_actual["id_departamento"];
    $id_turno = !empty($id_turno) ? $id_turno : $empleado_actual["id_turno"];
    $puesto = !empty($puesto) ? $puesto : $empleado_actual["puesto"];
    $telefono = !empty($telefono) ? $telefono : $empleado_actual["telefono"];
    $uid_ldap = !empty($uid_ldap) ? $uid_ldap : $empleado_actual["uid_ldap"];
    $cuenta_pidgin = !empty($cuenta_pidgin) ? $cuenta_pidgin : $empleado_actual["cuenta_pidgin"];
    $fecha_contratacion = !empty($fecha_contratacion) ? $fecha_contratacion : $empleado_actual["fecha_contratacion"];
    error_log("Valores enviados: id_empleado={$id_empleado}, puesto={$puesto}");
    // Llamar al procedimiento almacenado con todos los valores
    $stmt = $conexion->prepare("CALL modificar_empleado(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssiisssss", $id_empleado, $nombre, $apellido, $dni, $id_departamento, $id_turno, $puesto, $telefono, $uid_ldap, $cuenta_pidgin, $fecha_contratacion);

    if ($stmt->execute()) {
        $mensaje = "Empleado modificado correctamente.";
    } else {
        $mensaje = "Error al modificar empleado: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();

    return $mensaje;
}

function modificar_turno_rrhh($id_turno, $descripcion, $hora_inicio, $hora_fin, $dias_semana, $horario_flexible) {
    $conexion = conectar_bd_rrhh();

    $stmt = $conexion->prepare("CALL modificar_turno(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $id_turno, $descripcion, $hora_inicio, $hora_fin, $dias_semana, $horario_flexible);

    if ($stmt->execute()) {
        return "Turno modificado correctamente.";
    } else {
        return "Error al modificar el turno.";
    }
}

function modificar_departamento_rrhh($id_departamento, $nombre, $id_responsable) {
    $conexion = conectar_bd_rrhh();

    $stmt = $conexion->prepare("CALL modificar_departamento(?, ?, ?)");
    $stmt->bind_param("isi", $id_departamento, $nombre, $id_responsable);

    if ($stmt->execute()) {
        return "Departamento modificado correctamente.";
    } else {
        return "Error al modificar el departamento.";
    }
}

function modificar_fichaje_rrhh($id_fichaje, $fecha, $hora, $tipo, $id_empleado_modificador) {
    $conexion = conectar_bd_rrhh();

    $stmt = $conexion->prepare("CALL modificar_fichaje(?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $id_fichaje, $fecha, $hora, $tipo, $_SESSION["id_usuario"]);

    if ($stmt->execute()) {
        return "Fichaje modificado correctamente.";
    } else {
        return "Error al modificar el fichaje.";
    }
}

function aprobar_solicitud_vacaciones($id_solicitud, $id_empleado_modificador) {
    $conexion = conectar_bd_rrhh(); 

    $stmt = $conexion->prepare("CALL aprobar_solicitud_vacaciones(?, ?)");
    $stmt->bind_param("ii", $id_solicitud, $id_empleado_modificador);

    if ($stmt->execute()) {
        return "Solicitud de vacaciones aprobada correctamente.";
    } else {
        return "Error al aprobar la solicitud.";
    }
}

function denegar_solicitud_vacaciones($id_solicitud, $id_empleado_modificador, $motivo) {
    $conexion = conectar_bd_rrhh();

    $stmt = $conexion->prepare("CALL denegar_solicitud_vacaciones(?, ?, ?)");
    $stmt->bind_param("iis", $id_solicitud, $id_empleado_modificador, $motivo);

    if ($stmt->execute()) {
        return "Solicitud de vacaciones denegada correctamente.";
    } else {
        return "Error al denegar la solicitud.";
    }
}

function justificar_ausencia($id_ausencia, $id_empleado_modificador) {
    $conexion = conectar_bd_rrhh(); 
    $stmt = $conexion->prepare("CALL justificar_ausencia(?, ?)");
    $stmt->bind_param("ii", $id_ausencia, $id_empleado_modificador);

    if ($stmt->execute()) {
        return "Ausencia justificada correctamente.";
    } else {
        return "Error al justificar la ausencia.";
    }
}

function borrar_fichaje($id_fichaje, $id_empleado_modificador) {
    $conexion = conectar_bd_rrhh();

    $stmt = $conexion->prepare("CALL borrar_fichaje(?, ?)");
    $stmt->bind_param("ii", $id_fichaje, $id_empleado_modificador);

    if ($stmt->execute()) {
        return "Fichaje eliminado correctamente.";
    } else {
        return "Error al eliminar el fichaje.";
    }
}

?>
