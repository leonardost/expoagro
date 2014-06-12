<?php

require_once(realpath($_SERVER['DOCUMENT_ROOT']) . '/modelo/conexao.php');
require_once(realpath($_SERVER['DOCUMENT_ROOT']) . '/visao/visao.php');

abstract class Controle {
	
	protected $visao;
	protected $conexao;
	
	public function __construct() {
		$this->conexao = conectar();
	}
	
	public function __destruct() {
		desconectar($this->conexao);
	}
	
	public function associar_visao($pagina) {
		if (!empty($pagina)) {
			$this->visao = new Visao($pagina);
		}
	}
	
	public abstract function executar();

}
