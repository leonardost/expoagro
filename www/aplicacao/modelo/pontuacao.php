<?php

class PontuacaoModelo {

	private $conexao = null;

	function __construct($conexao) {
		$this->conexao = $conexao;
	}

	public function get_categoria() {
		return $this->categoria;
	}

	public function get_colocacao() {
		return $this->colocacao;
	}

	public function get_pontos() {
		return $this->pontos;
	}

	public function inserir($id, $categoria, $colocacao, $pontos) {
		return executar_sql($this->conexao, "INSERT INTO pontuacao (id, categoria, colocacao, pontos) VALUES ($id, $categoria, '$colocacao', $pontos)");
	}

	public function editar($categoria, $colocacao, $pontos) {
		return executar_sql($this->conexao, "UPDATE pontuacao SET categoria = $categoria, colocacao = '$colocacao', pontos = '$pontos' WHERE id = $this->id");
	}

	public function remover() {
		return executar_sql($this->conexao, "DELETE from pontuacao WHERE id = $this->id");
	}

	public function todos() {
		return executar_sql($this->conexao,
			"SELECT p.id, c.nome, p.colocacao, p.pontos FROM pontuacao p LEFT OUTER JOIN categoria c ON p.categoria = c.id ORDER BY categoria, pontos DESC");
	}

	public function buscar($id) {
		$resultado = executar_sql($this->conexao, "SELECT id, categoria, colocacao, pontos FROM pontuacao WHERE id = $id");
		if (pg_num_rows($resultado) == 1) {
			$tupla = recuperar_tuplas($resultado);

			$this->id = $tupla[0];
			$this->categoria = $tupla[1];
			$this->colocacao = $tupla[2];
			$this->pontos = $tupla[3];

			return true;
		}
		return false;
	}

};
