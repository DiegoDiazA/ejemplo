<?php

// Permitir el acceso desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Especificar los métodos permitidos (POST, GET, etc.)
header("Access-Control-Allow-Methods: POST, GET");

// Especificar los encabezados permitidos
header("Access-Control-Allow-Headers: Content-Type");

// Permitir que las cookies se incluyan en las solicitudes (si es necesario)
header("Access-Control-Allow-Credentials: true");

require "config/Conexion.php";
parse_str(file_get_contents("php://input"), $datos);
//print_r($_SERVER['REQUEST_METHOD']);
    // Método GET para obtener todos los usuarios
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $usuarios = array();

    $result = $conexion->query("SELECT * FROM usuarios");

    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($usuarios);
}

// Método POST para crear un nuevo usuario
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['usuario']) && isset($data['correo']) && isset($data['contrasena'])) {
        $usuario = $data['usuario'];
        $correo = $data['correo'];
        $contrasena = $data['contrasena'];

        $sql = $conexion->prepare("INSERT INTO usuarios (usuario, correo, contrasena) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $usuario, $correo, $contrasena);

        if ($sql->execute()) {
            echo "Datos insertados con éxito";
        } else {
            echo "Error al insertar datos: " . $sql->error;
        }

        $sql->close();
    } else {
        echo "Error: Faltan datos en la solicitud.";
    }
}

// Método PUT para actualizar un usuario
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);

    if (isset($put_vars['id_user']) && isset($put_vars['usuario']) && isset($put_vars['correo']) && isset($put_vars['contrasena'])) {
        $id_user = $put_vars['id_user'];
        $usuario = $put_vars['usuario'];
        $correo = $put_vars['correo'];
        $contrasena = $put_vars['contrasena'];

        $sql = $conexion->prepare("UPDATE usuarios SET usuario=?, correo=?, contrasena=? WHERE id_user=?");
        $sql->bind_param("sssi", $usuario, $correo, $contrasena, $id_user);

        if ($sql->execute()) {
            echo "Usuario actualizado con éxito";
        } else {
            echo "Error al actualizar usuario: " . $sql->error;
        }

        $sql->close();
    } else {
        echo "Error: Faltan datos en la solicitud.";
    }
}

// Método PATCH para actualizar parcialmente un usuario
elseif ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    parse_str(file_get_contents("php://input"), $patch_vars);

    if (isset($patch_vars['id_user'])) {
        $id_user = $patch_vars['id_user'];
        $actualizaciones = array();

        if (isset($patch_vars['usuario'])) {
            $actualizaciones[] = "usuario='" . $patch_vars['usuario'] . "'";
        }

        if (isset($patch_vars['correo'])) {
            $actualizaciones[] = "correo='" . $patch_vars['correo'] . "'";
        }

        if (isset($patch_vars['contrasena'])) {
            $actualizaciones[] = "contrasena='" . $patch_vars['contrasena'] . "'";
        }

        if (!empty($actualizaciones)) {
            $sql = "UPDATE usuarios SET " . implode(', ', $actualizaciones) . " WHERE id_user=$id_user";

            if ($conexion->query($sql) === TRUE) {
                echo "Usuario actualizado con éxito";
            } else {
                echo "Error al actualizar usuario: " . $conexion->error;
            }
        } else {
            echo "Error: No se especificaron datos para actualizar.";
        }
    } else {
        echo "Error: Faltan datos en la solicitud.";
    }
}

// Método DELETE para eliminar un usuario
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $delete_vars);

    if (isset($delete_vars['id_user'])) {
        $id_user = $delete_vars['id_user'];

        $sql = "DELETE FROM usuarios WHERE id_user=$id_user";

        if ($conexion->query($sql) === TRUE) {
            echo "Usuario eliminado con éxito";
        } else {
            echo "Error al eliminar usuario: " . $conexion->error;
        }
    } else {
        echo "Error: Faltan datos en la solicitud.";
    }
}

        header("Access-Control-Allow-Origin: *");

        // Permitir métodos HTTP específicos
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE, HEAD, TRACE, PATCH");
        
        // Permitir encabezados personalizados
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        
        // Permitir credenciales
        header("Access-Control-Allow-Credentials: true");
?>