<?php

class ProdutoProdutorModelo {

    // - produto    FK PK
    // - produtor    FK PK
    // - classificação

    private $conexao = null;

    function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function inserir($produto, $produtor) {
        return executar_sql($this->conexao, "INSERT INTO produto_produtor (produto, produtor) VALUES ($produto, $produtor)");
    }

    public function editar($produto, $produtor, $classificacao) {
        return executar_sql($this->conexao, "UPDATE produto_produtor SET classificacao = $classificacao WHERE produto = $produto AND produtor = $produtor");
    }

    public function remover($produtor) {
        return executar_sql($this->conexao, "DELETE FROM produto_produtor WHERE produtor = $produtor");
    }

    public function todos() {
        return executar_sql($this->conexao,
            "SELECT pr.id, pr.nome, p.nome, pp.classificacao
            FROM produto_produtor pp, produto p, produtor pr
            WHERE pp.produto = p.id AND pp.produtor = pr.id
            ORDER BY pr.nome, p.nome");
    }

    // Retorna todos os produtos de um determinado produtor
    public function todos_produtor($produtor) {
        return executar_sql($this->conexao, "SELECT produto, classificacao FROM produto_produtor WHERE produtor = $produtor");
    }

};
