<?php

function conectar() {

    $string_conexao = "host=" . HOST .
        " port=" . PORT .
        " dbname=" . DBNAME .
        " user=" . USER .
        " password=" . PASS;

    $conexao = pg_connect($string_conexao);

    return $conexao;

}

function executar_sql($conexao, $string) {
    $resultado = pg_query($conexao, $string);
    return $resultado;
}

function recuperar_tuplas($resultado) {
    $tupla = pg_fetch_row($resultado);
    return $tupla;
}

function desconectar($conexao) {
    pg_close($conexao);
}
