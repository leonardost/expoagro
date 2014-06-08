<?php

    session_start();

    if (empty($_SESSION['logado'])) {
        header('Location: login.php');
        exit;
    }

	require_once('conexao.php');
	
	$conexao = conectar();
	$resultado = executar_sql($conexao, 'DELETE FROM categoria WHERE id = ' . $_GET['id']);
	desconectar($conexao);

	header('Location: categoria.php');
	exit;
