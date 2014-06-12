<?php

//--Visao---------------------------------------------------------------
//
// Classe que cuida da apresentação das páginas
//
class Visao {
	
	private $conteudo;
	
	//--Construtor------------------------------------------------------
	//
	// Argumentos:
	//   $pagina é a página que será mostrada
	//
	public function __construct($pagina) {
		
		$root = realpath($_SERVER['DOCUMENT_ROOT']);
		
		// Lê o arquivo de template geral, igual para todas as páginas
		$template = file_get_contents($root . '/visao/template.htm');
		if ($template === false) {
			die('Problema ao abrir o arquivo de template');  // TODO: Mover para módulo de log
		}
		
		// Lê a página que deverá ser apresentada
		$p = file_get_contents($root . '/visao/' . $pagina . '.htm');
		if ($p === false) {
			die('Problema ao abrir o arquivo de visão de ' . $pagina);  // TODO: Mover para módulo de log
		}
		
		// Integra a página no template
		$this->conteudo = str_replace('{CONTEUDO}', $p, $template);
		
	}
	
	//--substituir_secao------------------------------------------------
	//
	// Substitui uma seção do conteúdo pelo conteúdo passado como parâmetro
	//
	public function substituir_secao($secao, $conteudo) {
		$this->conteudo = str_replace($secao, $conteudo, $this->conteudo);
	}

	//--gerar-----------------------------------------------------------
	//
	// Gera a visualização da página, retirando placeholders (palavras
	// entre '{' e '}') não utilizados.
	//
	public function gerar() {
		$this->conteudo = preg_replace('/\{[^}]*\}/', '', $this->conteudo);
		echo $this->conteudo;
	}
	
}
