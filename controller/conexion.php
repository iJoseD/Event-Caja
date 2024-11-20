<?php
/**
 * Bind Tickets: Producción
 */
$servername = "localhost";
$dbname     = "backoffice_bd";
$username   = "backoffice_user";
$password   = "U46u90yc@";

/**
 * Caribe Creativo: Pruebas
*/
// $servername = "localhost";
// $dbname     = "eventflow";
// $username   = "eventflow_user";
// $password   = "X#3jxw587";

/**
 * Localhost: Desarrollo
*/
// $servername = "127.0.0.1";
// $dbname     = "event";
// $username   = "root";
// $password   = "";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}