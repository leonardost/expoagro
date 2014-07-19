<?php

class PontuacaoControle extends Controle {

	private $modelo;

	public function __construct() {
		parent::__construct();

		require_once(ROOT . '/aplicacao/modelo/pontuacao.php');
		$this->modelo = new PontuacaoModelo($this->conexao);
	}

	private function valida_campo_obrigatorio($nome_campo, &$valor_campo) {
		if (empty($_POST[$nome_campo])) {
			return false;
		}
		else {
			$valor_campo = $_POST[$nome_campo];
			return true;
		}
	}

	private function monta_dropdown_categoria($categoria) {
		require_once(ROOT . '/aplicacao/modelo/categoria.php');
		$categoria_modelo = new CategoriaModelo($this->conexao);
		$lista_categorias = $categoria_modelo->todos();

		$categoria_secao = "<select id=\"categoria\" name=\"categoria\" autofocus>";
		$categoria_secao .= "<option value=0 selected=\"selected\">---Selecionar---</option>\n";
		if (pg_affected_rows($lista_categorias) <> 0) {
			while ($row = recuperar_tuplas($lista_categorias)) {
				$categoria_secao .= "<option value=$row[0]";
				if ($categoria == $row[0]) {
					$categoria_secao .= " selected=\"selected\" ";
				}
				$categoria_secao .= ">$row[1]</option>\n";
			}
		}
		$categoria_secao .= "</select>\n";

		return $categoria_secao;
	}

	public function inserir() {
		$erros = array();
		$categoria = null;
		$colocacao = null;
		$pontos = null;

		if (!empty($_POST)) {
			$erros['categoria'] = !$this->valida_campo_obrigatorio('categoria', $categoria);

			$expr = '/^[1-9;A-Z;a-z]$/';
			$erros['colocacao'] =
				!$this->valida_campo_obrigatorio('colocacao', $colocacao) ||
				!preg_match($expr, $colocacao);

			$expr = '/^[1-9][0-9]*$/';
			$erros['pontos'] =
				!$this->valida_campo_obrigatorio('pontos', $pontos) ||
				!preg_match($expr, $pontos) ||
				!filter_var($pontos, FILTER_VALIDATE_INT);

			if (!$erros['categoria'] && !$erros['colocacao'] && !$erros['pontos']) {
				$this->modelo->inserir('default', $categoria, $colocacao, $pontos);
				// TODO: Mostrar erro se acontecer
				header('Location: /pontuacao/');
				exit;
			}
		}

		$this->associar_visao('pontuacao/inserir');
		$this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');

		$categoria_secao = $this->monta_dropdown_categoria($categoria);
		$this->visao->substituir_secao('{CATEGORIA}', $categoria_secao);

		if (!$erros['colocacao']) {
			$this->visao->substituir_secao('{COLOCACAO}', $colocacao);
		}
		if (!$erros['pontos']) {
			$this->visao->substituir_secao('{PONTOS}', $pontos);
		}

		// TODO: Alterar o foco para campo com problema.
		if ($erros['categoria']) {
			$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Categoria não selecionada!</p>\n");
		}
		elseif ($erros['colocacao']) {
			$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Colocação é obrigatória e deve ser um caractere alfanumérico!</p>\n");
		}
		elseif ($erros['pontos']) {
			$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Pontos é obrigatório e deve ser um número entre 1 e 999!</p>\n");
		}
		$this->visao->gerar();

	}

	public function editar($id) {
		if (!$this->modelo->buscar($id)) {
			// TODO: Exibir msg "Não existe pontuacao com id = $id." ?
			header('Location: /pontuacao/');
		}
		else {
			$erros = array();
			$categoria = null;
			$colocacao = null;
			$pontos = null;

			if (empty($_POST)) {
				$categoria = $this->modelo->get_categoria();
				$colocacao = $this->modelo->get_colocacao();
				$pontos = $this->modelo->get_pontos();
			}
			// Mudança submetida
			else {
				$erros['categoria'] = !$this->valida_campo_obrigatorio('categoria', $categoria);

				$expr = '/^[1-9;A-Z;a-z]$/';
				$erros['colocacao'] =
					!$this->valida_campo_obrigatorio('colocacao', $colocacao) ||
					!preg_match($expr, $colocacao);

				$expr = '/^[1-9][0-9]*$/';
				$erros['pontos'] =
					!$this->valida_campo_obrigatorio('pontos', $pontos) ||
					!preg_match($expr, $pontos) ||
					!filter_var($pontos, FILTER_VALIDATE_INT);

				if (!$erros['categoria'] && !$erros['colocacao'] && !$erros['pontos']) {
					$this->modelo->editar($categoria, $colocacao, $pontos);
					// TODO: Tratar erro se acontecer
					header('Location: /pontuacao/');
				}
			}
			
			$this->associar_visao('pontuacao/editar');
			$this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');

			// TODO: Alterar o foco para campo com problema.
			if ($erros['categoria']) {
				$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Categoria não pode ser vazia!</p>\n");
			}
			elseif ($erros['colocacao']) {
				$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Colocação é obrigatória e deve ser um caractere alfanumérico!</p>\n");
			}
			elseif ($erros['pontos']) {
				$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Pontos é obrigatório e deve ser um número entre 1 e 999!</p>\n");
			}
			$this->visao->substituir_secao('{ID}', $id);
			$this->visao->substituir_secao('{COLOCACAO}', $colocacao);
			$this->visao->substituir_secao('{PONTOS}', $pontos);

			$categoria_secao = $this->monta_dropdown_categoria($categoria);
			$this->visao->substituir_secao('{CATEGORIA}', $categoria_secao);
			$this->visao->gerar();
		}
	}
	
	public function remover($id) {
		if ($this->modelo ->buscar($id)) {
			$this->modelo->remover();
		}
		else {
			// TODO: Exibir msg "Não existe pontuacao com id = $id." ?
		}
		header('Location: /pontuacao/');
		exit;
	}

	public function index() {

		$conteudo = "<table>
			<tr>
				<th>Seleção</th>
				<th>Categoria</th>
				<th>Colocação</th>
				<th>Pontos</th>
				<th>Editar</th>
				<th>Remover</th>
			</tr>";

		$resultado = $this->modelo->todos();

		if (pg_affected_rows($resultado) == 0) {
			$conteudo .= "<tr><td colspan=\"5\">Nenhuma pontuação cadastrada.</td></tr>\n";
		}
		else {
			while ($row = recuperar_tuplas($resultado)) {
				$conteudo .= "
					<tr>
						<td></td>
						<td>$row[1]</td>
						<td>$row[2]</td>
						<td>$row[3]</td>
						<td><a href=\"/pontuacao/editar/$row[0]/\">Editar</a></td>
						<td><a href=\"/pontuacao/remover/$row[0]/\">Remover</a></td>
					</tr>\n";
			}
		}
		$conteudo .= "</table>\n";

		$this->associar_visao('pontuacao/index');
		$this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
		$this->visao->substituir_secao('{TABELA}', $conteudo);
		$this->visao->gerar();

	}

}
