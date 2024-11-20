<?php
    session_start();

    if ( !empty($_SESSION['UsuarioID']) ) {
        header( 'location: /' );
    }
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head -->
    <?php require_once('../../template/header.php'); ?>

    <body>
        <section class="container-fluid">
            <div class="row vh-100">
                <div class="col-12 d-flex align-items-center justify-content-center imgLogin">
                    <div class="row w-75 text-center bg-white pt-4 pb-4 rounded-3">
                        <div class="col-12">
                            <img class="w-75" src="/resources/img/logoBind.png" alt="Logo">
                        </div>
                        <div class="col-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control form-control-sm" id="CorreoElectronico">
                                <label>Usuario</label>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="form-floating">
                                <input type="password" class="form-control form-control-sm" id="Contrasena">
                                <label>Contraseña</label>
                            </div>
                        </div>
                        <div class="col-12 mt-3 w-100 mb-5">
                            <button type="button" class="btn btn-primary w-100" id="button-iniciarSesion">Ingresar <i class="bi bi-arrow-right"></i></button>
                        </div>
                        <small class="mt-5">Una marca de <strong>Bind Tech Solutions</strong></small>
                    </div>
                </div>
            </div>
        </section>

        <!-- Scripts footer -->
        <?php require_once('../../template/footer.php'); ?>
        <script src="/resources/js/login.js"></script>
    </body>
</html>