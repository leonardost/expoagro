<?php

	// Define a raiz do servidor (/var/www, http://localhost, etc.)
    define('ROOT', realpath($_SERVER['DOCUMENT_ROOT']));

    // Constantes para o banco de dados
    //
    // A constante BANCO diz qual é o banco de dados a ser utilizado.
    // Valores possíveis são:
    //     - postgres
    //
    define('BANCO', 'postgres');

    define('HOST', 'localhost');
    define('PORT', '5432');
    define('DBNAME', 'expoagro');
    define('USER', 'postgres');
    define('PASS', 'postgres');
