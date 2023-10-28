<?php
// Conexión a la base de datos MySQL
$hostName = 'localhost';
$usuario = 'root';
$pass = 'root';
$nombrebd = 'Bd_Solicitudes';

$conexion = new mysqli($hostName, $usuario, $pass, $nombrebd);

if ($conexion->connect_error) {
    die("Error en la conexión a la base de datos: " . $conexion->connect_error);
}


// Verifica si se envió una acción válidait 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    // Acciones válidas
    if ($accion === 'agregar_usuario' || $accion === 'obtener_usuarios') {
        // Asegúrate de que se envíen otros datos necesarios
        if (isset($_POST['id'], $_POST['nombre'], $_POST['password'])) {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $password = $_POST['password'];

            // Corrige la consulta SQL: usa comillas simples alrededor de "password"
            $sql = "INSERT INTO usuario (id, nombre, password) VALUES ('$id', '$nombre', '$password')";

            if ($conexion->query($sql) === TRUE) {
                echo "Usuario agregado correctamente.";
            } else {
                echo "Error al agregar usuario: " . $conexion->error;
            }
        } else {
            echo "Faltan datos necesarios en la solicitud POST.";
        }
    } else {
        echo "Acción no válida.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion'])) {
    $accion = $_GET['accion'];

    // Acciones válidas
    if ($accion === 'obtener_usuarios') {
        $sql = "SELECT * FROM usuario";
        $result = $conexion->query($sql);

        if ($result && $result->num_rows > 0) {
            $usuarios = [];
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
            echo json_encode($usuarios);
        } else {
            echo json_encode([]);
        }
    } else {
        echo "Acción no válida.";
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Asegúrate de que se envíen otros datos necesarios
    if (isset($_POST['nombre'], $_POST['password'])) {
        $nombre = $_POST['nombre'];
        $password = $_POST['password'];

        // Actualiza el usuario
        $sql = "UPDATE usuario SET nombre='$nombre', password='$password' WHERE id='$id'";

        if ($conexion->query($sql) === TRUE) {
            echo "Usuario actualizado correctamente.";
        } else {
            echo "Error al actualizar usuario: " . $conexion->error;
        }
    } else {
        echo "Faltan datos necesarios en la solicitud PUT.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Elimina el usuario
    $sql = "DELETE FROM usuario WHERE id='$id'";

    if ($conexion->query($sql) === TRUE) {
        echo "Usuario eliminado correctamente.";
    } else {
        echo "Error al eliminar usuario: " . $conexion->error;
    }
}else {
    echo "Acción no válida verificar las condiciones";
}// Verifica si se envió una acción válida
