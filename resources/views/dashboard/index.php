<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/validacion.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/functions.php');

    $Evento_Sql = "SELECT * FROM Eventos WHERE ID = '$EventoID'";
    $Evento_Result = $conn->query($Evento_Sql);

    if ($Evento_Result->num_rows > 0) {
        while($Row = $Evento_Result->fetch_assoc()) {
            $Evento_Codigo = $Row["Codigo"];
            $Evento_Nombre = $Row["Nombre"];
        }
    }

    $PDV_Sql = "SELECT * FROM PDV WHERE ID = '$PdvID'";
    $PDV_Result = $conn->query($PDV_Sql);

    if ($PDV_Result->num_rows > 0) {
        while($Row = $PDV_Result->fetch_assoc()) {
            $PDV_Nombre = $Row["Nombre"];
        }
    }

    function Consumo($TicketID, $conn) {
        $Sql = "SELECT SUM(Precio) AS 'Consumo' FROM DetalleVenta WHERE TicketID = '$TicketID' AND Estado = 'Cerrada'";
        $Result = $conn->query($Sql);

        if ($Result->num_rows > 0) {
            while($Row = $Result->fetch_assoc()) {
                $Consumo = isset($Row['Consumo']) ? $Row['Consumo'] : '0';
            }
        }

        return $Consumo;
    }

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
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head -->
    <?php require_once('../../template/header.php'); ?>

    <body class="bg-body-tertiary" data-EventoID="<?php echo $EventoID; ?>" data-PdvID="<?php echo $PdvID; ?>">
        <!-- Navbar     -->
        <?php require_once('../../template/navbar.php'); ?>

        <section class="container-fluid">
            <div class="row mt-5">
                <div class="col-12 mb-5">
                    <div class="card text-center">
                        <div class="card-header fw-bold text-uppercase">Información general</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-12">
                                    <p><span class="fw-bold">Usuario:</span><br><?php echo $NombreCompleto; ?></p>
                                </div>
                                <div class="col-md-4 col-lg-4 col-12">
                                    <p><span class="fw-bold">Evento:</span><br><?php echo $Evento_Nombre; ?></p>
                                </div>
                                <div class="col-md-4 col-lg-4 col-12">
                                    <p><span class="fw-bold">Zona:</span><br><?php echo $PDV_Nombre; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-5">
                    <div class="card">
                        <div class="card-header fw-bold">Pedidos</div>
                        <div class="card-body pt-5" id="Pedidos">
                            <div class="row">
                                <input type="hidden" id="UltimoID" value="<?php echo ObtenerUltimoID($EventoID, $PdvID, $conn); ?>">
                                <?php
                                    $Sql = "SELECT Ventas.FechaRegistro, Tickets.Ubicacion, Ventas.Codigo, Ventas.ValidacionCaja
                                            FROM Ventas
                                            JOIN Tickets ON Ventas.TicketID = Tickets.ID
                                            WHERE
                                                Tickets.EventoID = '$EventoID'
                                                AND Tickets.PdvID = '$PdvID'
                                            ORDER BY Ventas.ValidacionCaja DESC";
                                    $Result = $conn->query($Sql);

                                    if ($Result->num_rows > 0) {
                                        while($Row = $Result->fetch_assoc()) {
                                            $Codigo = $Row["Codigo"];
                                            $ValidacionCaja = $Row["ValidacionCaja"];

                                            $FechaRegistro = new DateTime( $Row["FechaRegistro"] );
                                            $FechaActual = new DateTime();
                                            $DiferenciaMinutos = $FechaRegistro->diff($FechaActual)->format('%i');

                                            if ($ValidacionCaja == 'Si') {
                                                $Borde = 'border border-success border-2';
                                                $Fondo = 'bg-success-subtle';
                                            } else {
                                                $Borde = 'border border-danger border-2';
                                                $Fondo = 'bg-danger-subtle';
                                            } ?>

                                            <div class="col-md-6 col-lg-6 col-12 mb-4">
                                                <div class="card h-100 <?php echo $Borde; ?>">
                                                    <div class="card-header <?php echo $Fondo; ?>">
                                                        <h1 class="fw-bold"><?php echo $Row["Ubicacion"]; ?></h1>
                                                        <span>Código de venta: <?php echo $Row["Codigo"]; ?></span>
                                                    </div>
                                                    <div class="card-body">
                                                        <?php
                                                            $SqlItems = "SELECT InventarioPDV.Nombre, COUNT(*) AS Cantidad, SUM(DetalleVenta.Precio) AS Precio, Ventas.MetodoDePago
                                                                    FROM DetalleVenta
                                                                    JOIN InventarioPDV ON DetalleVenta.ProductoID = InventarioPDV.ID
                                                                    JOIN Ventas ON DetalleVenta.Codigo = Ventas.Codigo
                                                                    WHERE DetalleVenta.Codigo = '$Codigo' AND DetalleVenta.Estado = 'Cerrada'
                                                                    GROUP BY InventarioPDV.Nombre, Ventas.MetodoDePago";
                                                            $ResultItems = $conn->query($SqlItems);

                                                            $Total = 0;
                                                            if ($ResultItems->num_rows > 0) {
                                                                while($Row = $ResultItems->fetch_assoc()) { ?>
                                                                    <p><span class="badge bg-secondary"><?php echo $Row["Cantidad"]; ?></span> <?php echo $Row["Nombre"]; ?></p>
                                                                <?php }
                                                            }
                                                        ?>
                                                    </div>
                                                    <div class="card-footer text-end">
                                                        <button type="button" class="btn btn-primary btn-sm FinalizarPedido" data-Codigo="<?php echo $Codigo; ?>">Validar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                    } else { ?>
                                        <p>No tienes pedidos asignados.</p>
                                    <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Scripts footer -->
        <?php require_once('../../template/footer.php'); ?>
        <script src="/resources/js/dashboard.js"></script>
    </body>
</html>