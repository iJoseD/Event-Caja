<?php

session_start();

if (empty($_SESSION['UsuarioID'])) {
    header( 'location: /login/' );
} else {
    header( 'location: /dashboard/' );
}