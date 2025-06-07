#!/bin/bash

# Configuración LDAP
ADMIN_LDAP="cn=admin,dc=planetas,dc=sa"
ADMIN_LDAP_PASSWD="ldap-planetas-sa"
DOMINIO_LDAP="dc=planetas,dc=sa"
LOG_LDAP_MANAGER="/var/log/ldap-manager.log"

# Función para registrar actividades
registrar_actividad() {
    echo "$(date): $1" >> "$LOG_LDAP_MANAGER"
}

# Función para manejar errores
manejar_error() {
    echo "Error: $1"
    registrar_actividad "Error: $1"
    exit 1
}

# === Funciones para Crear Objetos ===
crear_ou() {
    echo -e "Unidades Organizativas existentes: \n"
    listar_ou
    read -p "Nombre de la OU (ej: usuarios): " NOMBRE_OU
    archivo_ldif="/tmp/crear_ou.ldif"
    cat << EOF > $archivo_ldif
dn: ou=$NOMBRE_OU,$DOMINIO_LDAP
objectClass: organizationalUnit
ou: $NOMBRE_OU
EOF
    if ldapadd -x -D "$ADMIN_LDAP" -w "$ADMIN_LDAP_PASSWD"  -f $archivo_ldif; then
        echo "OU '$NOMBRE_OU' creada."
        registrar_actividad "OU '$NOMBRE_OU' creada exitosamente."
    else
        manejar_error "No se pudo crear la OU '$NOMBRE_OU'."
    fi
    rm $archivo_ldif
}

crear_grupo() {
    echo -e "Grupos existentes: \n"
    listar_grupos
    read -p "Nombre del grupo: " NOMBRE_GRUPO
    read -p "GID del grupo: " GID
    archivo_ldif="/tmp/crear_grupo.ldif"
    cat << EOF > $archivo_ldif
dn: cn=$NOMBRE_GRUPO,$DOMINIO_LDAP
objectClass: posixGroup
cn: $NOMBRE_GRUPO
gidNumber: $GID
EOF
    if ldapadd -x -D "$ADMIN_LDAP" -w "$ADMIN_LDAP_PASSWD" -f $archivo_ldif; then
        echo "Grupo '$NOMBRE_GRUPO' creado."
        registrar_actividad "Grupo '$NOMBRE_GRUPO' creado exitosamente."
    else
        manejar_error "No se pudo crear el grupo '$NOMBRE_GRUPO'."
    fi
    rm $archivo_ldif
}

crear_usuario() {
    echo -e "Usuarios existentes: \n"
    listar_usuarios
    read -p "Código de usuario: " UID_NUMBER
    read -p "Nombre de inicio de sesión: " UID_USUARIO
    read -p "Apellido del usuario " APELLIDO_USUARIO
    read -p "Nombre completo: " NOMBRE_COMPLETO
    read -p "GID principal (grupo): " GID_USUARIO

    while true; do
        read -s -p "Contraseña: " CONTRASENA
	echo
        read -s -p "Confirma la contraseña: " CONFIRMACION_CONTRASENA
	echo
        if [ "$CONTRASENA" = "$CONFIRMACION_CONTRASENA" ]; then
            break
        else
            echo "Las contraseñas no coinciden. Por favor, intenta nuevamente."
        fi
    done

    HASH_CONTRASENA=$(slappasswd -s "$CONTRASENA")
    archivo_ldif="/tmp/crear_usuario.ldif"
    cat << EOF > $archivo_ldif
dn: uid=$UID_USUARIO,ou=usuarios,$DOMINIO_LDAP
objectClass: inetOrgPerson
objectClass: posixAccount
objectClass: shadowAccount
uid: $UID_USUARIO
sn: $APELLIDO_USUARIO
cn: $NOMBRE_COMPLETO
uidNumber: $UID_NUMBER
gidNumber: $GID_USUARIO
homeDirectory: /mnt/profiles/$UID_USUARIO
loginShell: /bin/bash
userPassword: $HASH_CONTRASENA

EOF
    if ldapadd -x -D "$ADMIN_LDAP" -w "$ADMIN_LDAP_PASSWD" -f $archivo_ldif; then
        echo "Usuario '$UID_USUARIO' creado."
        registrar_actividad "Usuario '$UID_USUARIO' creado exitosamente."
	 # Crear cuenta para mensajería instantánea
    	ejabberdctl register "$UID_USUARIO" planetas.sa "$CONTRASENA"
	echo "Cuenta de mensajería '$UID_USUARIO' creada exitosamente."
	registrar_actividad "Cuenta de mensajería '$UID_USUARIO' creada exitosamente."
    else
        manejar_error "No se pudo crear el usuario '$UID_USUARIO'."
    fi
    rm $archivo_ldif
}

# === Funciones para Modificar Objetos ===
modificar_ou() {
    echo -e "Unidades Organizativas existentes: \n"
    listar_ou
    read -p "Nombre de la OU a modificar: " NOMBRE_OU
    read -p "Nuevo nombre para la OU: " NUEVO_NOMBRE_OU
    archivo_ldif="/tmp/modificar_ou.ldif"
    cat << EOF > $archivo_ldif
dn: ou=$NOMBRE_OU,$DOMINIO_LDAP
changetype: modify
replace: ou
ou: $NUEVO_NOMBRE_OU
EOF
    if ldapmodrdn -x -D "$ADMIN_LDAP" -w "$ADMIN_LDAP_PASSWD" -r \
        "ou=$NOMBRE_OU,$DOMINIO_LDAP" "ou=$NUEVO_NOMBRE_OU"; then
        echo "OU '$NOMBRE_OU' modificada a '$NUEVO_NOMBRE_OU'."
        registrar_actividad "OU '$NOMBRE_OU' modificada a '$NUEVO_NOMBRE_OU'."
    else
        manejar_error "No se pudo modificar la OU '$NOMBRE_OU'."
    fi
    rm $archivo_ldif
}

modificar_grupo() {
    echo -e "Grupos existentes: \n"
    listar_grupos
    read -p "Nombre del grupo a modificar: " NOMBRE_GRUPO
    read -p "Nuevo nombre para el grupo: " NUEVO_NOMBRE_GRUPO
    archivo_ldif="/tmp/modificar_grupo.ldif"
    cat << EOF > $archivo_ldif
dn: cn=$NOMBRE_GRUPO,$DOMINIO_LDAP
changetype: modify
replace: cn
cn: $NUEVO_NOMBRE_GRUPO
EOF
    if ldapmodrdn -x -D "$ADMIN_LDAP" -w "$ADMIN_LDAP_PASSWD" -r \
        "cn=$NOMBRE_GRUPO,$DOMINIO_LDAP" "cn=$NUEVO_NOMBRE_GRUPO"; then
        echo "Grupo '$NOMBRE_GRUPO' modificado a '$NUEVO_NOMBRE_GRUPO'."
        registrar_actividad "Grupo '$NOMBRE_GRUPO' modificado a '$NUEVO_NOMBRE_GRUPO'."
    else
        manejar_error "No se pudo modificar el grupo '$NOMBRE_GRUPO'."
    fi
    rm $archivo_ldif
}

modificar_usuario() {
    echo -e "Usuarios existentes: \n"
    listar_usuarios
    read -p "UID del usuario a modificar: " UID_USUARIO
    read -p "Nuevo nombre completo: " NUEVO_NOMBRE_COMPLETO
    archivo_ldif="/tmp/modificar_usuario.ldif"
    cat << EOF > $archivo_ldif
dn: uid=$UID_USUARIO,ou=usuarios,$DOMINIO_LDAP
changetype: modify
replace: cn
cn: $NUEVO_NOMBRE_COMPLETO
EOF
    if ldapmodify -x -D "$ADMIN_LDAP" -w "$ADMIN_LDAP_PASSWD" -f $archivo_ldif; then
        echo "Usuario '$UID_USUARIO' modificado a '$NUEVO_NOMBRE_COMPLETO'."
        registrar_actividad "Usuario '$UID_USUARIO' modificado a '$NUEVO_NOMBRE_COMPLETO'."
    else
        manejar_error "No se pudo modificar el usuario '$UID_USUARIO'."
    fi
    rm $archivo_ldif
}

# === Funciones para Borrar Objetos ===
borrar_ou() {
    echo -e "Unidades Organizativas existentes: \n"
    listar_ou
    read -p "Nombre de la OU a borrar: " NOMBRE_OU
    archivo_ldif="/tmp/borrar_ou.ldif"
    cat << EOF > $archivo_ldif
dn: ou=$NOMBRE_OU,$DOMINIO_LDAP
changetype: delete
EOF
    if ldapdelete -x -D "$ADMIN_LDAP" -w "$ADMIN_LDAP_PASSWD" -f $archivo_ldif; then
        echo "OU '$NOMBRE_OU' borrada."
        registrar_actividad "OU '$NOMBRE_OU' borrada."
    else
        manejar_error "No se pudo borrar la OU '$NOMBRE_OU'."
    fi
    rm $archivo_ldif
}

borrar_grupo() {
    echo -e "Grupos existentes: \n"
    listar_grupos
    read -p "Nombre del grupo a borrar: " NOMBRE_GRUPO
    archivo_ldif="/tmp/borrar_grupo.ldif"
    cat << EOF > $archivo_ldif
dn: cn=$NOMBRE_GRUPO,ou=departamentos,$DOMINIO_LDAP
changetype: delete
EOF
    if ldapdelete -x -D "$ADMIN_LDAP" -w "$ADMIN_LDAP_PASSWD" -f $archivo_ldif; then
        echo "Grupo '$NOMBRE_GRUPO' borrado."
        registrar_actividad "Grupo '$NOMBRE_GRUPO' borrado."
    else
        manejar_error "No se pudo borrar el grupo '$NOMBRE_GRUPO'."
    fi
    rm $archivo_ldif
}

borrar_usuario() {
    echo -e "Usuarios existentes:\n"
    listar_usuarios
    read -p "UID del usuario a borrar: " UID_USUARIO

    # Verificar si el usuario existe en LDAP
    DN_USUARIO=$(ldapsearch -x -LLL -b "$DOMINIO_LDAP" "(uid=$UID_USUARIO)" dn | grep "^dn:" | awk '{print $2}')
    if [ -z "$DN_USUARIO" ]; then
        manejar_error "El usuario '$UID_USUARIO' no existe en el servidor LDAP."
        return
    fi

    # Borrar el usuario directamente con ldapdelete
    if ldapdelete -x -D "$ADMIN_LDAP" -w "$ADMIN_LDAP_PASSWD" "$DN_USUARIO"; then
        echo "Usuario '$UID_USUARIO' borrado exitosamente."
        registrar_actividad "Usuario '$UID_USUARIO' borrado exitosamente."
	# Eliminar cuenta de mensajería instantánea
        ejabberdctl unregister "$UID_USUARIO" planetas.sa
        echo "Cuenta de mensajería '$UID_USUARIO' eliminada exitosamente."
	registrar_actividad "Cuenta de mensajería '$UID_USUARIO' eliminada exitosamente."
	# Preguntar si se debe eliminar la carpeta del usuario
        while true; do
            read -p "¿Deseas eliminar la carpeta del usuario? (1 = Sí / 2 = No): " RESPUESTA
            case "$RESPUESTA" in
                1)
                    if [ -d "/nfs/profiles/$UID_USUARIO" ]; then
                        rm -rf "/home/$UID_USUARIO"
                        echo "Carpeta de usuario '/nfs/profiles/$UID_USUARIO' eliminada exitosamente."
                        registrar_actividad "Carpeta de usuario '$UID_USUARIO' eliminada exitosamente."
                    else
                        echo "No se encontró la carpeta '/home/$UID_USUARIO', nada que eliminar."
                    fi
                    break
                    ;;
                2)
                    echo "Se ha decidido conservar la carpeta de usuario '/home/$UID_USUARIO'."
                    registrar_actividad "Carpeta de usuario '$UID_USUARIO' conservada."
                    break
                    ;;
                *)
                    echo "Entrada no válida. Por favor, introduce '1' para eliminar o '2' para conservar."
                    ;;
            esac
        done
    else
        manejar_error "No se pudo borrar el usuario '$UID_USUARIO'."
    fi
}

# === Funciones para Listar Objetos ===
listar_ou() {
    echo "Listando Unidades Organizativas..."
    ldapsearch -x -LLL -b "$DOMINIO_LDAP" "(objectClass=organizationalUnit)" ou
}

listar_grupos() {
    echo "Listando Grupos..."
    ldapsearch -x -LLL -b "$DOMINIO_LDAP" "(objectClass=posixGroup)" cn gidNumber
}

listar_usuarios() {
    echo "Listando Usuarios..."
    ldapsearch -x -LLL -b "ou=usuarios,$DOMINIO_LDAP" "(objectClass=inetOrgPerson)" uid sn cn uidNumber gidNumber
}

# === Menú para seleccionar el tipo de objeto ===
menu_objeto() {
    echo "1. Unidad Organizativa"
    echo "2. Grupo"
    echo "3. Usuario"
    read -p "Elige una opción (1-3): " OBJETO

    case $OBJETO in
        1) OBJETO_SELECCIONADO="unidad organizativa" ;;
        2) OBJETO_SELECCIONADO="grupo" ;;
        3) OBJETO_SELECCIONADO="usuario" ;;
        *) echo "Opción inválida."; OBJETO_SELECCIONADO="";;
    esac
}

# === Menú principal ===
menu_principal() {
    while true; do
        clear
        echo "=== Gestión OpenLDAP ==="
        echo "1. Crear objetos"
        echo "2. Modificar objetos"
        echo "3. Borrar objetos"
        echo "4. Listar objetos"
        echo "5. Salir"
        read -p "Elige una opción (1-5): " OPCION

        case $OPCION in
            1) menu_objeto; 
               case $OBJETO_SELECCIONADO in
                   "unidad organizativa") crear_ou ;;
                   "grupo") crear_grupo ;;
                   "usuario") crear_usuario ;;
               esac ;;
            2) menu_objeto; 
               case $OBJETO_SELECCIONADO in
                   "unidad organizativa") modificar_ou ;;
                   "grupo") modificar_grupo ;;
                   "usuario") modificar_usuario ;;
               esac ;;
            3) menu_objeto; 
               case $OBJETO_SELECCIONADO in
                   "unidad organizativa") borrar_ou ;;
                   "grupo") borrar_grupo ;;
                   "usuario") borrar_usuario ;;
               esac ;;
            4) 
               echo "1. Listar Unidades Organizativas"
               echo "2. Listar Grupos"
               echo "3. Listar Usuarios"
               read -p "Elige una opción (1-3): " LISTAR_OPCION
               case $LISTAR_OPCION in
                   1) listar_ou ;;
                   2) listar_grupos ;;
                   3) listar_usuarios ;;
                   *) echo "Opción inválida." ;;
               esac
               ;;
            5) echo "Saliendo de OpenLDAP Manager..."; registrar_actividad "=== Salida de OpenLDAP Manager ==="; exit 0 ;;
            *) echo "Opción inválida." ;;
        esac
        read -p "Presiona Enter para continuar..."
    done
}

registrar_actividad "=== Inicio de OpenLDAP Manager ==="
# Iniciar el menú principal
menu_principal
