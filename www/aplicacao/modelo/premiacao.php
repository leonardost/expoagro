<?php

class PremiacaoModelo {

	private $conexao = null;
	private $id = null;
	private $premio = null;
	private $valor = null;
	
	function __construct($conexao) {
		$this->conexao = $conexao;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_premio() {
		return $this->premio;
	}
	
	public function get_valor() {
		return $this->valor;
	}
	
	public function inserir($id, $premio, $valor) {
		return executar_sql($this->conexao, "INSERT INTO premiacao (id, premio, valor) VALUES ($id, '$premio', $valor)");
	}
	
	public function editar($id, $premio, $valor) {
		return executar_sql($this->conexao, "UPDATE premiacao SET premio = '$premio', valor = $valor WHERE id = $id");
	}
	
	public function remover($id) {
		return executar_sql($this->conexao, "DELETE FROM premiacao WHERE id = $id");
	}
	
	public function todos() {
		return executar_sql($this->conexao, "SELECT id, premio, valor FROM premiacao ORDER BY premio");
	}
	
	public function buscar_id($id) {
		$resultado = executar_sql($this->conexao, "SELECT id, premio, valor FROM premiacao WHERE id = $id");
		
		if (pg_affected_rows($resultado) == 1) {
			$tupla = recuperar_tuplas($resultado);
			
			$this->id = $tupla[0];
			$this->premio = $tupla[1];
			$this->valor = $tupla[2];
			
			return true;
		}
		return false;
	}
};