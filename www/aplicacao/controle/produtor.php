<?php

class ProdutorControle extends Controle {

    private $modelo;

    public function __construct() {
        parent::__construct();

        require_once(ROOT . '/aplicacao/modelo/produtor.php');
        $this->modelo = new ProdutorModelo($this->conexao);
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

    public function inserir() {

        $erros = array();
        $nome = null;
        $endereco = null;

        if (!empty($_POST)) {
            $erros['nome'] = !$this->valida_campo_obrigatorio('nome', $nome);
            $erros['endereco'] = !$this->valida_campo_obrigatorio('endereco', $endereco);
            if (!$erros['nome'] && !$erros['endereco']) {
                $this->modelo->inserir('default', $nome, $endereco);
                // TODO: Mostrar erro se acontecer
                header('Location: /produtor/');
                exit;
            }
        }

        $this->associar_visao('produtor/inserir');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
        if (!$erros['nome']) {
            $this->visao->substituir_secao('{NOME}', $nome);
        }
        if (!$erros['endereco']) {
            $this->visao->substituir_secao('{ENDERECO}', $endereco);
        }

        // TODO: Alterar o foco para campo com problema.
        if ($erros['nome']) {
            $this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Nome vazio!</p>\n");
        }
        elseif ($erros['endereco']) {
            $this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Endereço vazio!</p>\n");
        }
        $this->visao->gerar();

    }

    public function editar($id) {
        if (!$this->modelo->buscar($id)) {
            // TODO: Exibir msg "Não existe produtor com id = $id." ?
            header('Location: /produtor/');
        }
        else {
            $erros = array();
            $nome = null;
            $endereco = null;

            if (empty($_POST)) {
                $nome = $this->modelo->get_nome();
                $endereco = $this->modelo->get_endereco();
            }
            // Mudança submetida
            else {
                $erros['nome'] = !$this->valida_campo_obrigatorio('nome', $nome);
                $erros['endereco'] = !$this->valida_campo_obrigatorio('endereco', $endereco);

                if (!$erros['nome'] && !$erros['endereco']) {
                    $this->modelo->editar($nome, $endereco);
                    // TODO: Tratar erro se acontecer
                    header('Location: /produtor/');
                }
            }
            
            $this->associar_visao('produtor/editar');
            $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');

            // TODO: Alterar o foco para campo com problema.
            if ($erros['nome']) {
                $this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Nome não pode ser vazio!</p>\n");
            }
            elseif ($erros['endereco']) {
                $this->visao->substituir_secao('{ERRO}', "<p class=\"erro\">Endereço não pode ser vazio!</p>\n");
            }
            $this->visao->substituir_secao('{ID}', $id);
            $this->visao->substituir_secao('{NOME}', $nome);
            $this->visao->substituir_secao('{ENDERECO}', $endereco);
            $this->visao->gerar();
        }
    }
    
    public function remover($id) {
        if ($this->modelo->buscar($id)) {
            $this->modelo->remover();
        }
        else {
            // TODO: Exibir msg "Não existe produtor com id = $id." ?
        }
        header('Location: /produtor/');
        exit;
    }

    public function index() {

        $conteudo = "<table>
            <tr>
                <th>Seleção</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Editar</th>
                <th>Remover</th>
            </tr>";

        $resultado = $this->modelo->todos();

        if (pg_affected_rows($resultado) == 0) {
            $conteudo .= "<tr><td colspan=\"5\">Nenhum produtor cadastrado.</td></tr>\n";
        }
        else {
            while ($row = recuperar_tuplas($resultado)) {
                $conteudo .= "
                    <tr>
                        <td></td>
                        <td>$row[1]</td>
                        <td>$row[2]</td>
                        <td><a href=\"/produtor/editar/$row[0]/\">Editar</a></td>
                        <td><a href=\"/produtor/remover/$row[0]/\">Remover</a></td>
                    </tr>\n";
            }
        }
        $conteudo .= "</table>\n";

        $this->associar_visao('produtor/index');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
        $this->visao->substituir_secao('{TABELA}', $conteudo);
        $this->visao->gerar();

    }

}
