<?php

// Permitir el acceso desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Especificar los métodos permitidos (POST, GET, etc.)
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE");

// Especificar los encabezados permitidos
header("Access-Control-Allow-Headers: Content-Type");

// Permitir que las cookies se incluyan en las solicitudes (si es necesario)
header("Access-Control-Allow-Credentials: true");

require "config/Conexion.php";
parse_str(file_get_contents("php://input"), $datos);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Obtener todos los registros de pokes
        $result = $conexion->query("SELECT * FROM pokes");

        if ($result->num_rows > 0) {
            // Convertir el resultado en un arreglo asociativo
            $pokes = array();
            while ($row = $result->fetch_assoc()) {
                $pokes[] = $row;
            }
            // Devolver los registros de pokes como JSON
            echo json_encode($pokes);
        } else {
            // No se encontraron registros de pokes
            echo "No se encontraron registros de pokes.";
        }
        break;

    case 'POST':
        // Recibir los datos en formato JSON
        $data = json_decode(file_get_contents('php://input'), true);

        // Verificar si se han recibido los datos esperados
        if (isset($data['nombre_pkmn']) && isset($data['tipo1']) && isset($data['tipo2']) && isset($data['mov1']) && isset($data['mov2']) && isset($data['mov3']) && isset($data['mov4']) && isset($data['ruta'])) {
            // Obtener los datos del arreglo JSON
            $nombre_pkmn = $data['nombre_pkmn'];
            $tipo1 = $data['tipo1'];
            $tipo2 = $data['tipo2'];
            $mov1 = $data['mov1'];
            $mov2 = $data['mov2'];
            $mov3 = $data['mov3'];
            $mov4 = $data['mov4'];
            $ruta = $data['ruta'];

            // Insertar los datos en la base de datos
            $sql = $conexion->prepare("INSERT INTO pokes (nombre_pkmn, tipo1, tipo2, mov1, mov2, mov3, mov4, ruta) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->bind_param("ssssssss", $nombre_pkmn, $tipo1, $tipo2, $mov1, $mov2, $mov3, $mov4, $ruta);
            if ($sql->execute()) {
                echo "Datos insertados con éxito";
            } else {
                echo "Error al insertar datos: " . $sql->error;
            }
            $sql->close();
        } else {
            // Si faltan datos
            echo "Error: Faltan datos en la solicitud.";
        }
        break;

    case 'PATCH':
        $id_pkmn = $datos['id_pkmn'];
        $nombre_pkmn = $datos['nombre_pkmn'];
        $tipo1 = $datos['tipo1'];
        $tipo2 = $datos['tipo2'];

        $actualizaciones = array();
        if (!empty($nombre_pkmn)) {
            $actualizaciones[] = "nombre_pkmn = '$nombre_pkmn'";
        }
        if (!empty($tipo1)) {
            $actualizaciones[] = "tipo1 = '$tipo1'";
        }
        if (!empty($tipo2)) {
            $actualizaciones[] = "tipo2 = '$tipo2'";
        }

        $actualizaciones_str = implode(', ', $actualizaciones);
        $sql = "UPDATE pokes SET $actualizaciones_str WHERE id_pkmn = $id_pkmn";

        if ($conexion->query($sql) === TRUE) {
            echo "Registro actualizado con éxito.";
        } else {
            echo "Error al actualizar registro: " . $conexion->error;
        }
        break;

    case 'PUT':
        // Recibir los datos del formulario HTML
        $id_pkmn = $datos['id_pkmn'];
        $nombre_pkmn = $datos['nombre_pkmn'];
        $tipo1 = $datos['tipo1'];
        $tipo2 = $datos['tipo2'];

        // Preparar la consulta SQL usando consultas preparadas
        $sql = $conexion->prepare("UPDATE pokes SET nombre_pkmn = ?, tipo1 = ?, tipo2 = ? WHERE id_pkmn = ?");
        $sql->bind_param("sssi", $nombre_pkmn, $tipo1, $tipo2, $id_pkmn);

        // Ejecutar la consulta preparada
        if ($sql->execute()) {
            echo "Registro actualizado con éxito.";
        } else {
            echo "Error al actualizar registro: " . $conexion->error;
        }
        break;

        case 'DELETE':
            // Verificar si se proporcionó el ID de pokemon
            $id_pkmn = isset($_GET['id_pkmn']) ? $_GET['id_pkmn'] : null;
            if ($id_pkmn === null) {
                // Si no se proporcionó el ID de pokemon, devolver un error y establecer el código de respuesta HTTP en 400
                echo "ID de pokemon no proporcionado.";
                http_response_code(400); // Bad Request
                break;
            }
        
            // Preparar la consulta de eliminación
            $stmt = $conexion->prepare("DELETE FROM pokes WHERE id_pkmn = ?");
            if ($stmt === false) {
                // Si hay un error en la preparación de la consulta, devolver un error y establecer el código de respuesta HTTP en 500
                echo "Error en la preparación de la consulta: " . $conexion->error;
                http_response_code(500); // Internal Server Error
                break;
            }
        
            // Vincular el parámetro ID de pokemon y ejecutar la consulta de eliminación
            $stmt->bind_param("i", $id_pkmn);
            if ($stmt->execute()) {
                // Si la consulta se ejecuta con éxito, devolver un mensaje de éxito
                echo "Registro eliminado con éxito.";
            } else {
                // Si hay un error al ejecutar la consulta, devolver un mensaje de error y establecer el código de respuesta HTTP en 500
                echo "Error al eliminar registro: " . $stmt->error;
                http_response_code(500); // Internal Server Error
            }
            break;
        
}

// Configuraciones adicionales de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE, HEAD, TRACE");
header("Access-Control-Allow-Credentials: true");
?>
