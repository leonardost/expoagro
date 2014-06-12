<?php

require_once(realpath($_SERVER['DOCUMENT_ROOT']) . '/modelo/conexao.php');

class Categoria {
	
	private $id = 0;
	private $nome = '';

	function __construct($id, $nome) {
		$this->id = $id;
		$this->nome = $nome;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getNome() {
		return $this->nome;
	}
	
	public function setNome($nome) {
		$this->nome = $nome;
	}

	public static function todos() {
		$conexao = conectar();
		$resultado = executar_sql($conexao, "SELECT id, nome FROM categoria ORDER BY nome");
		desconectar($conexao);
		return $resultado;
	}

};
