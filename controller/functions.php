<?php

function diaSemana($dia) {
    $dias_semana = array(
        "Monday" => "Lunes",
        "Tuesday" => "Martes",
        "Wednesday" => "Miércoles",
        "Thursday" => "Jueves",
        "Friday" => "Viernes",
        "Saturday" => "Sábado",
        "Sunday" => "Domingo"
    );
    $dia_actual = $dias_semana[$dia];

    return $dia_actual;
}

function generarCodigo($longitud = 6) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    $max = strlen($caracteres) - 1;

    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[random_int(0, $max)];
    }

    return $codigo;
}

function codigoUnico($longitud = 6) {
    $codigo = generarCodigo($longitud);
    $codigos_previos = [];

    // Leer los códigos almacenados en el archivo
    $archivo = 'CodigosTemp.txt';
    if (file_exists($archivo)) {
        $codigos_previos = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    // Verificar si el código generado ya existe
    while (in_array($codigo, $codigos_previos)) {
        $codigo = generarCodigo($longitud); // Generar otro código si ya existe
    }

    // Guardar el nuevo código en el archivo
    file_put_contents($archivo, $codigo . PHP_EOL, FILE_APPEND);

    return $codigo;
}