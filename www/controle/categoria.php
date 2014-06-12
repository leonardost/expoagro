<?php

require_once(realpath($_SERVER['DOCUMENT_ROOT']) . '/controle/controle.php');
require_once(realpath($_SERVER['DOCUMENT_ROOT']) . '/modelo/categoria.php');

class CategoriaControle extends Controle {

	public function inserir() {

		$erros = array();

		if (!empty($_POST)) {
			if (empty($_POST['nome'])) {
				$erros['nome'] = true;
			}
			else {
				
				$resultado = executar_sql($this->conexao, 'INSERT INTO categoria (id, nome) VALUES (default, \'' . $_POST['nome'] . '\')');
				header('Location: /categoria/');
			}
		}
		
		if (!empty($erros['nome'])) {
			$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Nome vazio!</p>\n");
		}
		$this->visao->gerar();

	}

	public function editar($id) {

		$erros = array();

		if (empty($_POST)) {
			$resultado = executar_sql($this->conexao, 'SELECT id, nome FROM categoria WHERE id = ' . $id);
			$tupla = recuperar_tuplas($resultado);

			$id = $tupla[0];
			$nome = $tupla[1];
		}
		// Mudança submetida
		else {
			$id = $_POST['id'];

			if (empty($_POST['nome'])) {
				$erros['nome'] = true;
			}
			else {
				$nome = $_POST['nome'];
				$resultado = executar_sql($this->conexao, 'UPDATE categoria SET nome = \'' . $nome . '\' WHERE id = ' . $id);
				header('Location: /categoria/');
			}
		}
		
		if (!empty($erros['nome'])) {
			$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Nome vazio!</p>\n");
		}
		$this->visao->substituir_secao('{ID}', $id);
		$this->visao->substituir_secao('{NOME}', $nome);
		$this->visao->gerar();

	}
	
	public function remover($id) {
		$resultado = executar_sql($this->conexao, 'DELETE FROM categoria WHERE id = ' . $id);
		header('Location: /categoria/');
	}

	public function executar() {

		$conteudo = "<table>
			<tr>
				<th>Seleção</th>
				<th>Nome</th>
				<th>Editar</th>
				<th>Remover</th>
			</tr>";

		$resultado = Categoria::todos();

		if (pg_affected_rows($resultado) == 0) {
			$conteudo .= "<tr><td colspan=\"4\">Nenhuma categoria cadastrada.</td></tr>\n";
		}
		else {
			while ($row = recuperar_tuplas($resultado)) {
				$conteudo .= "
					<tr>
						<td></td>
						<td>$row[1]</td>
						<td><a href=\"/categoria/editar/$row[0]/\">Editar</a></td>
						<td><a href=\"/categoria/remover/$row[0]/\">Remover</a></td>
					</tr>\n";
			}
		}
		$conteudo .= "</table>\n";

		$this->visao->substituir_secao('{TABELA}', $conteudo);
		$this->visao->gerar();

	}

}
