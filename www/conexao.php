<?php

    function conectar() {

        $host = 'localhost';
        $port = '5432';
        $dbname = 'expoagro';
        $user = 'postgres';
        $pass = 'postgres';

        $string_conexao = "host=$host
            port=$port
            dbname=$dbname
            user=$user
            password=$pass";

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
