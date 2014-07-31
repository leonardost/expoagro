<?php

class ProdutoControle extends Controle {

    private $modelo;
    private $modelo_categoria;

    public function __construct() {
        parent::__construct();

        require_once(ROOT . '/aplicacao/modelo/produto.php');
        $this->modelo = new ProdutoModelo($this->conexao);
        require_once(ROOT . '/aplicacao/modelo/categoria.php');
        $this->modelo_categoria = new CategoriaModelo($this->conexao);
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
        $categoria = null;

        if (!empty($_POST)) {
            $erros['nome'] = !$this->valida_campo_obrigatorio('nome', $nome);
            $erros['categoria'] = !$this->valida_campo_obrigatorio('categoria', $categoria);
            if (!$erros['nome'] && !$erros['categoria']) {
                $this->modelo->inserir('default', $nome, $categoria);
                header('Location: /produto/');
                exit;
                // TODO: Mostrar erro se acontecer
            }
        }

        $this->associar_visao('produto/inserir');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
        
        // Preenche combobox de categorias
        $categorias_html = '';
        $categorias = $this->modelo_categoria->todos();
        while ($tupla = recuperar_tuplas($categorias)) {
            $categorias_html .= "<option value=\"$tupla[0]\" {SELECTED$tupla[0]}>$tupla[1]</option>\n";
        }
        $this->visao->substituir_secao('{CATEGORIAS}', $categorias_html);

        if (!$erros['nome']) {
            $this->visao->substituir_secao('{NOME}', $nome);
        }
        if (!$erros['categoria']) {
            $this->visao->substituir_secao('{SELECTED' . $categoria . '}', 'selected');
        }

        $mensagem_erro = '';
        // TODO: Alterar o foco para campo com problema.
        if ($erros['nome']) {
            $mensagem_erro .= "<p class=\"erro\">Nome vazio!</p><br>\n";
        }
        elseif ($erros['categoria']) {
            $mensagem_erro .= "<p class=\"erro\">Categoria vazia!</p><br>\n";
        }
        $this->visao->substituir_secao('{ERRO}', $mensagem_erro);
        
        $this->visao->gerar();

    }

    public function editar($id) {
        if (!$this->modelo->buscar($id)) {
            // TODO: Exibir msg "Não existe produto com id = $id."
            header('Location: /produto/');
            exit;
        }
        else {
            $erros = array();
            $nome = null;
            $categoria = null;

            if (empty($_POST)) {
                $nome = $this->modelo->get_nome();
                $categoria = $this->modelo->get_categoria();
            }
            // Mudança submetida
            else {
                $id = $_POST['id'];
                $erros['nome'] = !$this->valida_campo_obrigatorio('nome', $nome);
                $erros['categoria'] = !$this->valida_campo_obrigatorio('categoria', $categoria);

                if (!$erros['nome'] && !$erros['categoria']) {
                    $this->modelo->editar($id, $nome, $categoria);
                    // TODO: Tratar erro se acontecer
                    header('Location: /produto/');
                }
            }
            
            $this->associar_visao('produto/editar');
            $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');

            // Preenche combobox de categorias
            $categorias_html = '';
            $categorias = $this->modelo_categoria->todos();
            while ($tupla = recuperar_tuplas($categorias)) {
                $categorias_html .= "<option value=\"$tupla[0]\" {SELECTED$tupla[0]}>$tupla[1]</option>\n";
            }
            $this->visao->substituir_secao('{CATEGORIAS}', $categorias_html);

            $mensagem_erro = '';
            // TODO: Alterar o foco para campo com problema.
            if ($erros['nome']) {
                $mensagem_erro .= "<p class=\"erro\">Nome vazio!</p><br>\n";
            }
            elseif ($erros['categoria']) {
                $mensagem_erro .= "<p class=\"erro\">Categoria vazia!</p><br>\n";
            }
            $this->visao->substituir_secao('{ERRO}', $mensagem_erro);
            
            $this->visao->substituir_secao('{ID}', $id);
            $this->visao->substituir_secao('{NOME}', $nome);
            $this->visao->substituir_secao('{SELECTED' . $categoria . '}', 'selected');
            $this->visao->gerar();
        }
    }
    
    public function remover($id) {
        //~ if ($this->modelo->buscar($id)) {
            //~ $this->modelo->remover($id);
        //~ }
        //~ else {
            //~ // TODO: Exibir msg "Não existe produto com id = $id." ?
        //~ }
        $this->modelo->remover($id);
        // TODO: Checar se houve erro ou não, mostrar mensagem de sucesso ou falha
        header('Location: /produto/');
        exit;
    }

    public function index() {

        $conteudo = "<table>
            <tr>
                <th>Seleção</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Editar</th>
                <th>Remover</th>
            </tr>";

        $resultado = $this->modelo->todos();

        if (pg_affected_rows($resultado) == 0) {
            $conteudo .= "<tr><td colspan=\"5\">Nenhum produto cadastrado.</td></tr>\n";
        }
        else {
            while ($row = recuperar_tuplas($resultado)) {
                $conteudo .= "
                    <tr>
                        <td></td>
                        <td>$row[1]</td>
                        <td>$row[2]</td>
                        <td><a href=\"/produto/editar/$row[0]/\">Editar</a></td>
                        <td><a href=\"/produto/remover/$row[0]/\">Remover</a></td>
                    </tr>\n";
            }
        }
        $conteudo .= "</table>\n";

        $this->associar_visao('produto/index');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
        $this->visao->substituir_secao('{TABELA}', $conteudo);
        $this->visao->gerar();

    }

}
