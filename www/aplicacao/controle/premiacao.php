<?php

class PremiacaoControle extends Controle {
	
	private $modelo;
	
	public function __construct() {
		parent::__construct();
		
		require_once(ROOT . '/aplicacao/modelo/premiacao.php');
		$this->modelo = new PremiacaoModelo($this->conexao);
	}	
		
	public function inserir() {
		$erros = array();
        $valor = 'NULL';

		if (!empty($_POST)) {
			$erros['premio'] = empty($_POST['premio']);
			
			if (empty($_POST['valor'])) {
				$erros['valor'] = false;
			}
			else if (is_numeric($_POST['valor'])) {
				$erros['valor'] = false;
                $valor = $_POST['valor'];
			}
            else {
                $erros['valor'] = true;
            }
			
			if (!$erros['premio'] && !$erros['valor']) {
				$this->modelo->inserir('default', $_POST['premio'], $valor);
				// TODO: Mostrar erro se acontecer
				header('Location: /premiacao/');
				exit;
			}
		}

		$this->associar_visao('premiacao/inserir');
		$this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
		
		if (!$erros['premio']) {
			$this->visao->substituir_secao('{PREMIO}', $_POST['premio']);
		}
		if (!$erros['valor']) {
			$this->visao->substituir_secao('{VALOR}', $_POST['valor']);
		}

		// TODO: Alterar o foco para campo com problema.
		if ($erros['premio']) {
			$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Prêmio não pode ser vazio!</p>\n");
		}
		elseif ($erros['valor']) {
			$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Valor inválido! Valor deve ser um número.</p>\n");
		}
		
		$this->visao->gerar();
	}
	
	public function editar($id) {
		if (!$this->modelo->buscar_id($id)) {
			// TODO: Exibir msg "Não existe premiação com id = $id." ?
			header('Location: /premiacao/');
            exit;
		}
		else {
			$erros = array();            
            $premio = $this->modelo->get_premio();
            $valor = $this->modelo->get_valor();
                
			// Mudança submetida
			if (!empty($_POST)) {
                $valor = "NULL";
                
				$erros['premio'] = empty($_POST['premio']);
				
                if (empty($_POST['valor'])) {
                	$erros['valor'] = false;
                }
                else if (is_numeric($_POST['valor'])) {
                    $erros['valor'] = false;
                    $valor = $_POST['valor'];
                }
                else {
                    $erros['valor'] = true;
                }

				if (!$erros['premio'] && !$erros['valor']) {
					$this->modelo->editar($_POST['id'], $_POST['premio'], $valor);
					// TODO: Mostrar erro se acontecer
					header('Location: /premiacao/');
				}
			}
			
			$this->associar_visao('premiacao/editar');
			$this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');

			// TODO: Alterar o foco para campo com problema.
			if ($erros['premio']) {
				$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Prêmio não pode ser vazio!</p>\n");
			}
			elseif ($erros['valor']) {
				$this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Valor inválido! Valor deve ser um número.</p>\n");
			}
			
			$this->visao->substituir_secao('{ID}', $id);
			$this->visao->substituir_secao('{PREMIO}', $premio);
			$this->visao->substituir_secao('{VALOR}', $_POST['valor']);
			$this->visao->gerar();
		}
	}
		
	public function remover($id) {
		// Verifica se id existe, porque o usuário pode colocar id na url
		if ($this->modelo->buscar_id($id)) {
			$this->modelo->remover($id);
		}
		else {
			// TODO: Exibir msg "Não existe premiação com id = $id." ?
		}
		header('Location: /premiacao/');
		exit;
	}
	
	public function index() {
		$conteudo = "<table>
			<tr>
				<th>Seleção</th>
				<th>Prêmio</th>
				<th>Valor</th>
				<th>Editar</th>
				<th>Remover</th>
			</tr>";
		
		$resultado = $this->modelo->todos();
		
		if (pg_affected_rows($resultado) == 0) {
			$conteudo .= "<tr><td colspan=\"4\">Nenhuma premiação cadastrada.</td></tr>\n";
		}
		else {
			while ($tupla = recuperar_tuplas($resultado)) {
				$conteudo .= "
					<tr>
						<td></td>
						<td>$tupla[1]</td>
						<td>$tupla[2]</td>
						<td><a href=\"/premiacao/editar/$tupla[0]/\">Editar</a></td>
						<td><a href=\"/premiacao/remover/$tupla[0]/\">Remover</a></td>
					</tr>\n";
			}
		}
		$conteudo .= "</table>\n";

		$this->associar_visao('premiacao/index');
		$this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
		$this->visao->substituir_secao('{TABELA}', $conteudo);
		$this->visao->gerar();
	}
	
}
