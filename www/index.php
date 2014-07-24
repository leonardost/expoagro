<?php

    /**
     * Ponto de entrada único da aplicação expoagro. Todas as requisições
     * são processadas por este script. Os scripts corretos são chamados
     * de acordo com a URI utilizada.
     *
     * Exemplos:
     *
     * localhost:8080/categoria
     *     Esta URI chama a classe de controle 'CategoriaControle' em
     *     aplicacao/controle/categoria.php. Essa classe processa quaisquer
     *     dados de POST e gera a visão adequada.
     *
     * localhost:8080/categoria/remover/1
     *     Remove a categoria que tem id = 1.
     */

    session_start();

    // Carrega configurações globais da aplicação
    require_once('aplicacao/configuracao/configuracao.php');
    require_once('aplicacao/configuracao/conexao.php');

    // Classes principais
    require_once('aplicacao/controle/controle.php');
    require_once('aplicacao/visao/visao.php');

    // Se não estiver logado, o usuário deve ver a página de login
    if (empty($_SESSION['logado'])) {
        // Redireciona para a raiz se não estiver lá
        if ($_SERVER['REQUEST_URI'] !== '/') {
            header('Location: /');
            exit;
        }
        $pagina_atual = 'index';
        $metodo = 'login';
    }
    else {
        // Processa a URI

        // Remove agumentos GET
        $uri = explode('?', $_SERVER['REQUEST_URI'], 2);
        $caminho = $uri[0];
        if (!empty($uri[1])) {
            $argumentos_get = $uri[1];
        }
	
        // Remove '/' inicial
        if (strpos($caminho, '/') === 0) {
            $caminho = substr($caminho, 1);
        }
        $partes = explode('/', $caminho);

        // A página atual é a primeira seção da URI
        $pagina_atual = array_shift($partes);

        // Se a URI estiver vazia
        if (empty($pagina_atual)) {
            $pagina_atual = 'index';
        }

        // Processa restante da URI
        $metodo = array_shift($partes);
        $argumento = array_shift($partes);
    }

    // Cria o controle da página atual e o executa
    require_once(ROOT . '/aplicacao/controle/' . $pagina_atual . '.php');
    // Os controles se chamam IndexControle, CategoriaControle, etc.
    $classe = ucfirst($pagina_atual) . 'Controle';
    $classe = str_replace("_", "", $classe);  // TODO: Refatorar, pensar qual melhor forma de noemar classes com nomes compostos
    $controle = new $classe();

//    echo("Paǵina atual = " . $pagina_atual);  // TODO: Mover para log
//    echo("Método = $metodo");                 // TODO: Mover para log

    // Checa se o método existe no controle
    if (method_exists($controle, $metodo)) {
        $controle->$metodo($argumento);
    }
    // Se não existe, executa o método 'index'
    else {
        $controle->index($argumento);
    }

