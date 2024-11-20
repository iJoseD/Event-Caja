<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/conexion.php');
$Accion = isset($_POST['Accion']) ? $_POST['Accion'] : '';

// Campos
$EventoID   = isset($_POST['EventoID']) ? $_POST['EventoID'] : '';
$PdvID      = isset($_POST['PdvID']) ? $_POST['PdvID'] : '';
$Codigo     = isset($_POST['Codigo']) ? $_POST['Codigo'] : '';

/**
 * Función para conocer el último ID registrado en la tabla Ventas
*/
function ObtenerUltimoID($EventoID, $PdvID, $conn) {
    $Sql = "SELECT Ventas.ID
            FROM Ventas
            JOIN Tickets ON Ventas.TicketID = Tickets.ID
            WHERE
                Tickets.EventoID = '$EventoID'
                AND Tickets.PdvID = '$PdvID'
                AND Ventas.Despachado = 'No'
            ORDER BY Ventas.ID DESC
            LIMIT 1";
    $Result = $conn->query($Sql);

    if ($Result->num_rows > 0) {
        while($Row = $Result->fetch_assoc()) {
            $ID = $Row['ID'];
        }
    }

    return $ID;
}

if ($Accion === 'ObtenerUltimoID') {
    $Sql = "SELECT Ventas.ID
            FROM Ventas
            JOIN Tickets ON Ventas.TicketID = Tickets.ID
            WHERE
                Tickets.EventoID = '$EventoID'
                AND Tickets.PdvID = '$PdvID'
                AND Ventas.Despachado = 'No'
            ORDER BY Ventas.ID DESC
            LIMIT 1";
    $Result = $conn->query($Sql);

    if ($Result->num_rows > 0) {
        while($Row = $Result->fetch_assoc()) {
            echo $Row["ID"];
        }
    }
    
    $conn->close();

} elseif ($Accion === 'ActualizarPedidos') {
    $Html = '<div class="row">
        <input type="hidden" id="UltimoID" value="'. ObtenerUltimoID($EventoID, $PdvID, $conn) .'">';
        $Sql = "SELECT Ventas.FechaRegistro, Tickets.Ubicacion, Ventas.Codigo
                FROM Ventas
                JOIN Tickets ON Ventas.TicketID = Tickets.ID
                WHERE
                    Tickets.EventoID = '$EventoID'
                    AND Tickets.PdvID = '$PdvID'
                    AND Ventas.Despachado = 'No'
                ORDER BY Ventas.FechaRegistro DESC";
        $Result = $conn->query($Sql);

        if ($Result->num_rows > 0) {
            while($Row = $Result->fetch_assoc()) {
                $Codigo = $Row["Codigo"];

                $FechaRegistro = new DateTime( $Row["FechaRegistro"] );
                $FechaActual = new DateTime();
                $DiferenciaMinutos = $FechaRegistro->diff($FechaActual)->format('%i');

                if ($DiferenciaMinutos < 2) {
                    $Borde = 'border border-success border-2';
                    $Fondo = 'bg-success-subtle';
                } elseif ($DiferenciaMinutos > 2 && $DiferenciaMinutos < 3) {
                    $Borde = 'border border-warning border-2';
                    $Fondo = 'bg-warning-subtle';
                } else {
                    $Borde = 'border border-danger border-2';
                    $Fondo = 'bg-danger-subtle';
                }
                
                $Html .= '<div class="col-md-6 col-lg-6 col-12 mb-4">
                    <div class="card h-100 '. $Borde .'">
                        <div class="card-header '. $Fondo .'">
                            <h1 class="fw-bold">'. $Row["Ubicacion"] .'</h1>
                            <span>Código de venta: '. $Row["Codigo"] .'</span>
                        </div>
                        <div class="card-body">';
                            $SqlItems = "SELECT InventarioPDV.Nombre, COUNT(*) AS Cantidad, SUM(DetalleVenta.Precio) AS Precio, Ventas.MetodoDePago
                                    FROM DetalleVenta
                                    JOIN InventarioPDV ON DetalleVenta.ProductoID = InventarioPDV.ID
                                    JOIN Ventas ON DetalleVenta.Codigo = Ventas.Codigo
                                    WHERE DetalleVenta.Codigo = '$Codigo' AND DetalleVenta.Estado = 'Cerrada'
                                    GROUP BY InventarioPDV.Nombre, Ventas.MetodoDePago";
                            $ResultItems = $conn->query($SqlItems);

                            $Total = 0;
                            if ($ResultItems->num_rows > 0) {
                                while($Row = $ResultItems->fetch_assoc()) {
                                    $Html .= '<p><span class="badge bg-secondary">'. $Row["Cantidad"] .'</span> '. $Row["Nombre"] .'</p>';
                                }
                            }
                        $Html .= '</div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-primary btn-sm FinalizarPedido" data-Codigo="'. $Codigo .'">Finalizar</button>
                        </div>
                    </div>
                </div>';
            }
        } else {
            $Html .= '<p>No tienes pedidos asignados.</p>';
        }
    $Html .= '</div>
    <script src="/resources/js/FinalizarPedido.js"></script>';

    echo $Html;

    $conn->close();

} elseif ($Accion === 'FinalizarPedido') {
    // Preparar la consulta SQL para actualizar datos
    $sql = "UPDATE Ventas SET Despachado = 'Si' WHERE Codigo = '$Codigo'";

    if ($conn->query($sql) === TRUE) {
        echo "Datos guardados exitosamente";
    } else { echo "Error: " . $sql . "<br>" . $conn->error; }

    $conn->close();
}