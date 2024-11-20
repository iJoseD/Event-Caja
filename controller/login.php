<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/conexion.php');

// Campos
$Accion            = isset($_POST['Accion']) ? $_POST['Accion'] : '';
$CorreoElectronico = isset($_POST['CorreoElectronico']) ? $_POST['CorreoElectronico'] : '';
$Contrasena        = isset($_POST['Contrasena']) ? $_POST['Contrasena'] : '';

if ( $Accion === 'Ingresar' ) {
    // Preparar la consulta SQL para buscar datos
    $sql = "SELECT * FROM Usuarios WHERE CorreoElectronico = '$CorreoElectronico'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $passwordHash = $row["Contrasena"];
            $Salt = $row["Salt"];

            // Verificar la contraseña proporcionada por el usuario
            $ContrasenaValida = password_verify($Salt . $Contrasena, $passwordHash);

            if ($ContrasenaValida) {
                session_start();
                $_SESSION['UsuarioID'] = $row['ID'];

                echo "Ingreso exitoso";
            } else {
                echo "Contraseña incorrecta";
            }
        }
    } else {
        echo "Usuario incorrecto";
    }
}