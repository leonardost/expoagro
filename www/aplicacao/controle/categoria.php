<?php

class CategoriaControle extends Controle {

    private $modelo;

    public function __construct() {
        parent::__construct();

        require_once(ROOT . '/aplicacao/modelo/categoria.php');
        $this->modelo = new CategoriaModelo($this->conexao);
    }

	public function inserir() {

		$erros = array();

		if (!empty($_POST)) {
			if (empty($_POST['nome'])) {
				$erros['nome'] = true;
			}
			else {
                $this->modelo->inserir('default', $_POST['nome']);
                // TODO: Mostrar erro se acontecer
				header('Location: /categoria/');
                exit;
			}
		}

        $this->associar_visao('categoria/inserir');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
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
                $this->modelo->editar($id, $nome);
                // TODO: Tratar erro se acontecer
				header('Location: /categoria/');
			}
		}
		
        $this->associar_visao('categoria/editar');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
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
        exit;
	}

	public function index() {

		$conteudo = "<table>
			<tr>
				<th>Seleção</th>
				<th>Nome</th>
				<th>Editar</th>
				<th>Remover</th>
			</tr>";

        $resultado = $this->modelo->todos();

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

        $this->associar_visao('categoria/index');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
		$this->visao->substituir_secao('{TABELA}', $conteudo);
		$this->visao->gerar();

	}

}
