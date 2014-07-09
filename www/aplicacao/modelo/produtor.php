<?php

class ProdutorModelo {

	private $conexao = null;
	private $nome = null;
	private $endereco = null;

	function __construct($conexao) {
		$this->conexao = $conexao;
	}

	public function get_nome() {
		return $this->nome;
	}

	public function get_endereco() {
		return $this->endereco;
	}

	public function inserir($id, $nome, $endereco) {
		return executar_sql($this->conexao, "INSERT INTO produtor (id, nome, endereco) VALUES ($id, '$nome', '$endereco')");
	}

	public function editar($id, $nome, $endereco) {
		return executar_sql($this->conexao, "UPDATE produtor SET nome = '$nome', endereco = '$endereco' WHERE id = $id");
	}

	public function remover($id) {
		return executar_sql($this->conexao, "DELETE from produtor WHERE id = $id");
	}

	public function todos() {
		return executar_sql($this->conexao, "SELECT id, nome, endereco FROM produtor ORDER BY nome");
	}

	public function buscar($id) {
		$resultado = executar_sql($this->conexao, "SELECT id, nome, endereco FROM produtor WHERE id = $id");
		if (pg_num_rows($resultado) == 1) {
			$tupla = recuperar_tuplas($resultado);

			$this->id = $tupla[0];
			$this->nome = $tupla[1];
			$this->endereco = $tupla[2];

			return true;
		}
		return false;
	}

};
