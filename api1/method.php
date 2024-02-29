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
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Consulta SQL para seleccionar datos de la tabla de maestro
        $sql = "SELECT id_mae, nombre, apodo, tel, foto FROM maestro";

        $query = $conexion->query($sql);

        if ($query->num_rows > 0) {
            $data = array();
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
            // Devolver los resultados en formato JSON
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            echo "No se encontraron registros en la tabla de maestro.";
        }

        $conexion->close();
        break;

        case 'POST':
            // Recibir los datos en formato JSON
            $data = json_decode(file_get_contents('php://input'), true);
    
            // Verificar si se han recibido los datos esperados
            if (isset($data['nombre']) && isset($data['apodo']) && isset($data['tel']) && isset($data['foto'])) {
                // Obtener los datos del arreglo JSON
                $nombre = $data['nombre'];
                $apodo = $data['apodo'];
                $tel = $data['tel'];
                $foto = $data['foto'];
    
                // Insertar los datos en la base de datos
                $sql = $conexion->prepare("INSERT INTO maestro (nombre, apodo, tel, foto) VALUES (?, ?, ?, ?)");
                $sql->bind_param("ssss", $nombre, $apodo, $tel, $foto);
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
        $id_mae = $datos['id_mae'];
        $nombre = $datos['nombre'];
        $apodo = $datos['apodo'];
        $tel = $datos['tel'];
        $foto = $datos['foto'];
    
        $actualizaciones = array();
        if (!empty($nombre)) {
            $actualizaciones[] = "nombre = '$nombre'";
        }
        if (!empty($apodo)) {
            $actualizaciones[] = "apodo = '$apodo'";
        }
        if (!empty($tel)) {
            $actualizaciones[] = "tel = '$tel'";
        }
        if (!empty($foto)) {
            $actualizaciones[] = "foto = '$foto'";
        }
    
        $actualizaciones_str = implode(', ', $actualizaciones);
        $sql = "UPDATE maestro SET $actualizaciones_str WHERE id_mae = $id_mae";
    
        if ($conexion->query($sql) === TRUE) {
            echo "Registro actualizado con éxito.";
        } else {
            echo "Error al actualizar registro: " . $conexion->error;
        }
        break;
    

        case 'PUT':
            // Recibir los datos del formulario HTML
            $id_mae = $datos['id_mae'];
            $nombre = $datos['nombre'];
            $apodo = $datos['apodo'];
            $tel = $datos['tel'];
            $foto = $datos['foto'];
            
            // Preparar la consulta SQL usando consultas preparadas
            $sql = $conexion->prepare("UPDATE maestro SET nombre = ?, apodo = ?, tel = ?, foto = ? WHERE id_mae = ?");
            $sql->bind_param("ssssi", $nombre, $apodo, $tel, $foto, $id_mae);
            
            // Ejecutar la consulta preparada
            if ($sql->execute()) {
                echo "Registro actualizado con éxito.";
            } else {
                echo "Error al actualizar registro: " . $conexion->error;
            }
            break;
        
    

            case 'DELETE':
                // Obtener el ID de usuario del arreglo $datos
                $id_mae = isset($_GET['id_mae']) ? $_GET['id_mae'] : null;

                // Verificar si se proporcionó el ID de usuario
                if ($id_mae === null) {
                    echo "ID de usuario no proporcionado.";
                    break; // Sale del switch si el ID de usuario no está presente
                }

                // Preparar la consulta de eliminación
                $stmt = $conexion->prepare("DELETE FROM maestro WHERE id_mae = ?");

                // Verificar si la preparación de la consulta fue exitosa
                if ($stmt === false) {
                    echo "Error en la preparación de la consulta: " . $conexion->error;
                    break;
                }

                // Vincular el parámetro ID de usuario
                $stmt->bind_param("i", $id_mae);

                // Ejecutar la consulta de eliminación
                if ($stmt->execute()) {
                    echo "Registro eliminado con éxito.";
                } else {
                    echo "Error al eliminar registro: " . $stmt->error;
                }
                break;
        }

        header("Access-Control-Allow-Origin: *");

        // Permitir métodos HTTP específicos
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE, HEAD, TRACE, PATCH");
        
        // Permitir encabezados personalizados
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        
        // Permitir credenciales
        header("Access-Control-Allow-Credentials: true");
?>