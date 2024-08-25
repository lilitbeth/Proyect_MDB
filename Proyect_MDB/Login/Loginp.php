<?php
// Conexion a la base de datos SQL Server
$serverName = "HP-ELI\SQLEXPRESS";
$conexion = array ("Database" => "BasedeRegistros_LDRJLL", "UID" => "admin", "PWD" => "20061013", "CharacterSet" => "UTF-8");
$con = sqlsrv_connect ($serverName, $conexion);

if ($con) {
    echo "Conexión exitosa";
} else {
    echo "Fallo en la conexión";
    die(print_r(sqlsrv_errors(), true));  // Muestra los errores si la conexión falla
}

// Procesar formulario de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y limpiar los datos del formulario
    $username = htmlspecialchars(trim($_POST["username"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    // Verificar credenciales para Administradores
    $sqlAdministracion = "SELECT * FROM Administradores WHERE username = ? AND Contraseña = ?";
    $params = array($username, $password);
    $stmt = sqlsrv_query($con, $sqlAdministracion, $params);

    if ($stmt !== false && sqlsrv_has_rows($stmt)) {
        // Redirigir a la ventana de administración
        header("Location: Portal_Administracion.html");
        exit;
    } else {
        // Verificar credenciales para Alumnos
        $sqlAlumnos = "SELECT * FROM Alumnos WHERE Codigo_Alumno = ? AND Contraseña = ?";
        $stmt = sqlsrv_query($con, $sqlAlumnos, $params);

        if ($stmt !== false && sqlsrv_has_rows($stmt)) {
            // Redirigir a la ventana de estudiantes
            header("Location: Portal_Estudiantes.html");
            exit;
        } else {
            // Verificar credenciales para Maestros
            $sqlMaestros = "SELECT * FROM Maestros WHERE Codigo_Maestro = ? AND Contraseña = ?";
            $stmt = sqlsrv_query($con, $sqlMaestros, $params);

            if ($stmt !== false && sqlsrv_has_rows($stmt)) {
                // Redirigir a la ventana de maestros
                header("Location: Portal_Profesores.html");
                exit;
            } else {
                echo "Usuario o contraseña inválidos";
            }
        }
    }
}

sqlsrv_close($con);
?>

