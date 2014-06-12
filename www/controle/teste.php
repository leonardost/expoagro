<?php

require_once(realpath($_SERVER['DOCUMENT_ROOT']) . '/controle/controle.php');

class TesteControle extends Controle {
	
	public function executar() {
		
		// Array que guarda os erros que aconteceram
		$erros = array();

		if ($this->conexao === false) {
			$erros['conexao'] = true;
		}

		// Array que guarda as saídas de cada tabela
		$saidas = array();

		// Tabela categoria
		$result = pg_query($this->conexao, "SELECT id, nome FROM categoria ORDER BY id");
		if (pg_affected_rows($result) == 0) {
			$saidas['categoria'] = "<p>Nenhuma categoria cadastrada.</p>\n";
		}
		else {
			$saidas['categoria'] = "<table border=\"1\">\n";
			$saidas['categoria'] .= "<tr><th>ID</th><th>Nome</th></tr>\n";
			while ($row = pg_fetch_row($result)) {
				$saidas['categoria'] .= "<tr><td>$row[0]</td><td>$row[1]</td></tr>\n";
			}
			$saidas['categoria'] .= "</table>\n";
		}
		
		// Tabela pontuacao
		$result = pg_query($this->conexao, "SELECT p.id, c.nome, p.colocacao, p.pontos 
				FROM pontuacao p, categoria c 
				WHERE p.categoria = c.id 
				ORDER BY p.id");
		if (pg_affected_rows($result) == 0) {
			$saidas['pontuacao'] = "<p>Nenhuma pontuação cadastrada.</p>\n";
		}
		else {
			$saidas['pontuacao'] = "<table border=\"1\">\n";
			$saidas['pontuacao'] .= "<tr><th>ID</th><th>Categoria</th><th>Colocação</th><th>Pontos</th></tr>\n";
			while ($row = pg_fetch_row($result)) {
				$saidas['pontuacao'] .= "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr>\n";
			}
			$saidas['pontuacao'] .= "</table>\n";
		}
		
		// Tabela premiacao
		$result = pg_query($this->conexao, "SELECT id, premio, valor FROM premiacao ORDER BY id");
		if (pg_affected_rows($result) == 0) {
			$saidas['premiacao'] = "<p>Nenhuma premiação cadastrada.</p>\n";
		}
		else {
			$saidas['premiacao'] = "<table border=\"1\">\n";
			$saidas['premiacao'] .= "<tr><th>ID</th><th>Prêmio</th><th>Valor</th></tr>\n";
			while ($row = pg_fetch_row($result)) {
				$saidas['premiacao'] .= "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n";
			}
			$saidas['premiacao'] .= "</table>\n";
		}

		// Tabela pontuacao_premiacao
		$result = pg_query($this->conexao, "SELECT cat.nome, pont.colocacao, prem.premio, pp.quantidade 
				FROM pontuacao_premiacao pp, categoria cat, pontuacao pont, premiacao prem
				WHERE pp.pontuacao = pont.id AND pont.categoria = cat.id AND pp.premiacao = prem.id
				ORDER BY cat.nome, pont.colocacao, prem.premio");
		if (pg_affected_rows($result) == 0) {
			$saidas['pontuacao_premiacao'] = "<p>Nenhuma pontuação x premiação cadastrada.</p>\n";
		}
		else {
			$saidas['pontuacao_premiacao'] = "<table border=\"1\">\n";
			$saidas['pontuacao_premiacao'] .= "<tr><th>Categoria</th><th>Colocação</th><th>Prêmio</th><th>Quantidade</th></tr>\n";
			while ($row = pg_fetch_row($result)) {
				$saidas['pontuacao_premiacao'] .= "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr>\n";
			}
			$saidas['pontuacao_premiacao'] .= "</table>\n";
		}

		// Tabela produtor
		$result = pg_query($this->conexao, "SELECT id, nome, endereco FROM produtor ORDER BY id");
		if (pg_affected_rows($result) == 0) {
			$saidas['produtor'] = "<p>Nenhum produtor cadastrado.</p>\n";
		}
		else {
			$saidas['produtor'] = "<table border=\"1\">\n";
			$saidas['produtor'] .= "<tr><th>ID</th><th>Nome</th><th>Endereço</th></tr>\n";
			while ($row = pg_fetch_row($result)) {
				$saidas['produtor'] .= "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n";
			}
			$saidas['produtor'] .= "</table>\n";
		}

		// Tabela produto
		$result = pg_query($this->conexao, "SELECT p.id, p.nome, c.nome 
				FROM produto p, categoria c
				WHERE p.categoria = c.id
				ORDER BY p.id");
		if (pg_affected_rows($result) == 0) {
			$saidas['produto'] = "<p>Nenhum produto cadastrado.</p>\n";
		}
		else {
			$saidas['produto'] = "<table border=\"1\">\n";
			$saidas['produto'] .= "<tr><th>ID</th><th>Nome</th><th>Categoria</th></tr>\n";
			while ($row = pg_fetch_row($result)) {
				$saidas['produto'] .= "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n";
			}
			$saidas['produto'] .= "</table>\n";
		}

		// Tabela produto_produtor
		$result = pg_query($this->conexao, "SELECT p.nome, pr.nome, pp.classificacao 
				FROM produto_produtor pp, produto p, produtor pr
				WHERE pp.produto = p.id AND pp.produtor = pr.id
				ORDER BY p.nome, pp.classificacao, pr.nome");
		if (pg_affected_rows($result) == 0) {
			$saidas['produto_produtor'] = "<p>Nenhum produto x produtor cadastrado.</p>\n";
		}
		else {
			$saidas['produto_produtor'] = "<table border=\"1\">\n";
			$saidas['produto_produtor'] .= "<tr><th>Produto</th><th>Produtor</th><th>Classificação</th></tr>\n";
			while ($row = pg_fetch_row($result)) {
				$saidas['produto_produtor'] .= "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n";
			}
			$saidas['produto_produtor'] .= "</table>\n";
		}

		pg_close($this->conexao);

		if (!empty($erros['conexao'])) {
			$this->visao->substituir_secao('{ERRO}', '<h2>Problema com a conexao!</h2>');
		}
		
		foreach($saidas as $secao => $saida) {
			$this->visao->substituir_secao('{' . strtoupper($secao) . '}', $saida);
		}

		if (empty($erros)) {
			$this->visao->substituir_secao('{CONCLUSAO}', 'OK!');
		}
		else {
			$this->visao->substituir_secao('{CONCLUSAO}', 'Aconteceram erros');
		}
		
		$this->visao->gerar();

	}
	
}
