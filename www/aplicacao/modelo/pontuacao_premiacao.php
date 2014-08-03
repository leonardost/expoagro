<?php

class PontuacaoPremiacaoModelo {

    // - pontuacao    FK PK (este é um par categoria + colocação)
    // - premiacao    FK PK
    // - quantidade

    private $conexao = null;

    function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function inserir($pontuacao, $premiacao, $quantidade) {
        return executar_sql($this->conexao, "INSERT INTO pontuacao_premiacao (pontuacao, premiacao, quantidade) VALUES ($pontuacao, $premiacao, $quantidade)");
    }

    public function editar($pontuacao, $premiacao, $quantidade) {
        return executar_sql($this->conexao, "UPDATE pontuacao_premiacao SET quantidade = $quantidade WHERE pontuacao = $pontuacao AND premiacao = $premiacao");
    }

    // Remove todos os prêmios de uma determinada pontuação
    public function remover($pontuacao) {
        return executar_sql($this->conexao, "DELETE FROM pontuacao_premiacao WHERE pontuacao = $pontuacao");
    }

    public function todos() {
        return executar_sql($this->conexao,
            "SELECT pp.pontuacao, ca.nome, po.colocacao, pp.premiacao, pr.premio, pp.quantidade
            FROM pontuacao_premiacao pp, pontuacao po, premiacao pr, categoria ca
            WHERE pp.pontuacao = po.id AND po.categoria = ca.id AND pp.premiacao = pr.id
            ORDER BY ca.nome, po.colocacao, pr.premio ASC");
    }

    // Retorna todos os prêmios de uma determinada pontuação
    public function todos_pontuacao($pontuacao) {
        return $resultado = executar_sql($this->conexao, "SELECT premiacao, quantidade FROM pontuacao_premiacao WHERE pontuacao = $pontuacao");
    }

    public function buscar_pontuacao($id) {
        return $resultado = executar_sql($this->conexao,
            "SELECT ca.nome, po.colocacao
            FROM pontuacao po, categoria ca
            WHERE po.id = $id AND ca.id = po.categoria");
    }

};
