<?php

	// Ponto de entrada único da aplicação expoagro. Todas as requisições
	// são processadas por este script. Os scripts corretos são chamados
	// de acordo com a URI utilizada.
	//
	// Exemplos:
	//
	// localhost:8080/categoria
	//     Esta URI chama a classe de controle 'CategoriaControle' em
	//     controle/categoria.php. Essa classe processa quaisquer dados
	//     de POST e gera a visão adequada.

    session_start();

	// Se não estiver logado, o usuário deve ver a página de login
    if (empty($_SESSION['logado'])) {
		// Redireciona para a raiz se não estiver lá
		if ($_SERVER['REQUEST_URI'] !== '/') {
			header('Location: /');
			exit;
		}
		$_SESSION['pagina_atual'] = 'login';
    }
    else {
		// Processa a URI

		// Remove agumentos GET
		$uri = explode('?', $_SERVER['REQUEST_URI'], 2);
		$caminho = $uri[0];
		
		// Remove '/' se a URI começa com ele
		if (strpos($caminho, '/') === 0) {
			$caminho = substr($caminho, 1);
		}
		$partes = explode('/', $caminho);

		// A página atual é a primeira seção da URI
		$_SESSION['pagina_atual'] = $partes[0];
		array_shift($partes);
		
		// Se a URI estiver vazia
		if (empty($_SESSION['pagina_atual'])) {
			$_SESSION['pagina_atual'] = 'index';
		}

		// Processa restante da URI
		$metodo = array_shift($partes);
		$argumento = array_shift($partes);

	}

	// A função 'require' trabalha com caminhos absolutos, então '/' não é
	// a raiz de documentos do Apache. Temos que informar o caminho completo.
	$root = realpath($_SERVER['DOCUMENT_ROOT']);

	// Cria o controle da página atual e o executa
	require_once($root . '/controle/' . $_SESSION['pagina_atual'] . '.php');
	$classe = ucfirst($_SESSION['pagina_atual']) . 'Controle';
	$controle = new $classe;

	if (!empty($metodo)) {
		$controle->associar_visao($_SESSION['pagina_atual'] . '_' . $metodo);
		$controle->$metodo($argumento);
	}
	else {
		$controle->associar_visao($_SESSION['pagina_atual']);
		$controle->executar();
	}

?>
