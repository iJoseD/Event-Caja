<?php

session_start();

if (empty($_SESSION['UsuarioID'])) {
    header( 'location: /login/' );
} else {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/conexion.php');

    $UsuarioID = $_SESSION['UsuarioID'];

    $sql = "SELECT * FROM Usuarios WHERE ID = '$UsuarioID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $TipoDNI            = $row["TipoDNI"];
            $DNI                = $row["DNI"];
            $NombreCompleto     = $row["NombreCompleto"];
            $CorreoElectronico  = $row["CorreoElectronico"];
            $Telefono           = $row["Telefono"];
            $Contrasena         = $row["Contrasena"];
            $Genero             = $row["Genero"];
            $Rol                = $row["Rol"];
            $EventoID           = $row["EventoID"];
            $PdvID              = $row["PdvID"];
        }
    }
}