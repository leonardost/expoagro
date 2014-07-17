<?php

class CategoriaModelo {

    private $conexao = null;

	function __construct($conexao) {
		$this->conexao = $conexao;
	}

    public function inserir($id, $nome) {
        $resultado = executar_sql($this->conexao, 'INSERT INTO categoria (id, nome) VALUES (' . $id . ', \'' . $_POST['nome'] . '\')');
        return $resultado;
    }

    public function editar($id, $nome) {
        $resultado = executar_sql($this->conexao, 'UPDATE categoria SET nome = \'' . $nome . '\' WHERE id = ' . $id);
        return $resultado;
    }

	public function todos() {
		$resultado = executar_sql($this->conexao, "SELECT id, nome FROM categoria ORDER BY nome");
		return $resultado;
	}

};
