<?php

class ProdutoModelo {

    private $conexao = null;
    private $id = null;
    private $nome = null;
    private $categoria = null;

    function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function get_nome() {
        return $this->nome;
    }

    public function get_categoria() {
        return $this->categoria;
    }

    public function inserir($id, $nome, $categoria) {
        return executar_sql($this->conexao, "INSERT INTO produto (id, nome, categoria) VALUES ($id, '$nome', $categoria)");
    }

    public function editar($id, $nome, $categoria) {
        return executar_sql($this->conexao, "UPDATE produto SET nome = '$nome', categoria = $categoria WHERE id = $id");
    }

    public function remover($id) {
        return executar_sql($this->conexao, "DELETE from produto WHERE id = $id");
    }

    public function todos() {
        return executar_sql($this->conexao, "SELECT p.id, p.nome, c.nome FROM produto p LEFT OUTER JOIN categoria c ON p.categoria = c.id ORDER BY p.nome");
    }

    public function buscar($id) {

        $resultado = executar_sql($this->conexao, "SELECT id, nome, categoria FROM produto WHERE id = $id");

        if (pg_num_rows($resultado) == 1) {

            $tupla = recuperar_tuplas($resultado);

            $this->id = $tupla[0];
            $this->nome = $tupla[1];
            $this->categoria = $tupla[2];

            return true;

        }

        return false;

    }

};
