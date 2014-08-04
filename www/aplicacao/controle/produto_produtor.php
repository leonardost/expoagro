<?php

class ProdutoProdutorControle extends Controle {

    private $modelo;
    private $modelo_produto;
    private $modelo_produtor;

    public function __construct() {
        parent::__construct();

        require_once(ROOT . '/aplicacao/modelo/produto_produtor.php');
        $this->modelo = new ProdutoProdutorModelo($this->conexao);

        require_once(ROOT . '/aplicacao/modelo/produto.php');
        $this->modelo_produto = new ProdutoModelo($this->conexao);

        require_once(ROOT . '/aplicacao/modelo/produtor.php');
        $this->modelo_produtor = new ProdutorModelo($this->conexao);
    }

    public function inserir() {

        // Se o envio de informações foi feito
        if (!empty($_POST)) {
            $produtor = $_POST['produtor'];

            $produtos_a_inserir = array();
            foreach ($_POST as $chave => $valor) {
                if (strpos($chave, "produto") !== false) {
                    $id_produto = substr($chave, 7);
                    array_push($produtos_a_inserir, $id_produto);
                }
            }

            foreach ($produtos_a_inserir as $produto) {
                $this->modelo->inserir($produto, $produtor);
            }

            header('Location: /produto_produtor/');
            exit;
        }

        // Gera o combobox de produtores

        $lista_produtores = $this->modelo_produtor->todos();

        $html_lista_produtores = '';
        if (pg_affected_rows($lista_produtores) == 0) {
            $html_lista_produtores = "<p>Não há produtores cadastrados</p>\n";
        }
        else {
            $html_lista_produtores = "<select id=\"produtor\" name=\"produtor\">\n";
            while ($tupla = recuperar_tuplas($lista_produtores)) {
                $html_lista_produtores .= "<option value=\"$tupla[0]\">$tupla[1]</option>\n";
            }
            $html_lista_produtores .= "</select>\n";
        }

        // Gera a tabela de produtos

        $lista_produtos = $this->modelo_produto->todos();

        $html_lista_produtos = '';
        if (pg_affected_rows($lista_produtos) == 0) {
            $html_lista_produtos = "<p>Não há produtos cadastrados</p>\n";
        }
        else {
            $html_lista_produtos = "<table><tr><th></th><th>Produtos</th></tr>\n";
            while ($tupla = recuperar_tuplas($lista_produtos)) {
                $id = $tupla[0];
                $produto = $tupla[1];
                $html_lista_produtos .= "
                <tr>
                    <td><input id=\"produto$id\" name=\"produto$id\" type=\"checkbox\"></td>
                    <td><label for=\"produto$id\">$produto</label></td>
                </tr>\n";
            }
            $html_lista_produtos .= "</table>\n";
        }

        $this->associar_visao('produto_produtor/inserir');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');

        $this->visao->substituir_secao('{COMBOBOX_PRODUTORES}', $html_lista_produtores);
        $this->visao->substituir_secao('{LISTA_PRODUTOS}', $html_lista_produtos);
        $this->visao->gerar();

    }

    public function editar($produtor) {

        // Se o envio de informações foi feito
        if (!empty($_POST)) {
            $produtor = $_POST['produtor'];

            $produtos_a_inserir = array();
            foreach ($_POST as $chave => $valor) {
                if (strpos($chave, "produto") !== false) {
                    $id_produto = substr($chave, 7);
                    array_push($produtos_a_inserir, $id_produto);
                }
            }

            // Remove os produtos antigos relativos ao produtor selecionado
            $this->modelo->remover($produtor);

            foreach ($produtos_a_inserir as $produto) {
                $this->modelo->inserir($produto, $produtor);
            }

            header('Location: /produto_produtor/');
            exit;
        }

        // Recupera qual é produtor que estamos editando
        if ($this->modelo_produtor->buscar($produtor)) {
            $html_produtor = $this->modelo_produtor->get_nome();
        }

        // Gera a tabela de produtos, selecionando os itens já associados
        $lista_produtos = $this->modelo_produto->todos();

        if (pg_affected_rows($lista_produtos) == 0) {
            $conteudo = "<p>Não há produtos cadastrados</p>\n";
        }
        else {
            // Recupera os itens já associados com este produtor
            $lista_produtos_associados = $this->modelo->todos_produtor($produtor);
            $produtos_associados = array();
            while ($tupla = recuperar_tuplas($lista_produtos_associados)) {
                array_push($produtos_associados, $tupla[0]);
            }

            // Gera a tabela de produtos, selecionando os que já estão associados
            $html_lista_produtos = "<table><tr><th></th><th>Produto</th><th>Categoria</th></tr>\n";
            while ($tupla = recuperar_tuplas($lista_produtos)) {
                $id = $tupla[0];
                $produto = $tupla[1];
                $categoria = $tupla[2];
                $checked = '';
                if (in_array($id, $produtos_associados)) {
                    $checked = 'checked';
                }
                $html_lista_produtos .= "
                <tr>
                    <td><input id=\"produto$id\" name=\"produto$id\" type=\"checkbox\" $checked></td>
                    <td><label for=\"produto$id\">$produto</label></td>
                    <td><label for=\"produto$id\">$categoria</label></td>
                </tr>\n";
            }
            $html_lista_produtos .= "</table>\n";
        }

        $this->associar_visao('produto_produtor/editar');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
        $this->visao->substituir_secao('{ID_PRODUTOR}', $produtor);
        $this->visao->substituir_secao('{PRODUTOR}', $html_produtor);
        $this->visao->substituir_secao('{LISTA_PRODUTOS}', $html_lista_produtos);
        $this->visao->gerar();

    }

    public function remover($produtor) {
        $this->modelo->remover($produtor);
        header('Location: /produto_produtor/');
        exit;
    }

    public function index() {
        $conteudo = '';
        $lista_produtos_produtores = $this->modelo->todos();
        if (pg_affected_rows($lista_produtos_produtores) == 0) {
            $conteudo .= "<p>Não há produtos x produtores cadastrados</p>\n";
        }
        else {
            $conteudo .= "<table>";
            $lista_produtos_por_produtor = array();
            while ($tupla = recuperar_tuplas($lista_produtos_produtores)) {
                $id = $tupla[0];
                $produtor = $tupla[1];
                $produto = $tupla[2];
                $classificacao = $tupla[3];
                if (!array_key_exists($id, $lista_produtos_por_produtor)) {
                    $lista_produtos_por_produtor[$id] = array();
                    $lista_produtos_por_produtor[$id]['produtor'] = $produtor;
                    $lista_produtos_por_produtor[$id]['produtos'] = array();
                }
                array_push($lista_produtos_por_produtor[$id]['produtos'], array($produto, $classificacao));
            }
        }

        foreach ($lista_produtos_por_produtor as $id => $produtos) {
            $conteudo .=
                "<tr><th colspan=\"2\">" . $produtos['produtor'] . "</th>" .
                " <th><a href=\"/produto_produtor/editar/$id/\">editar</a></th>" .
                " <th><a href=\"/produto_produtor/remover/$id/\">remover</a></th></tr>\n" .
                "<tr><th>Produto</th><th>Classificação</th><td colspan=\"2\"></td></tr>";
            foreach ($produtos['produtos'] as $produto) {
                $conteudo .= "<tr><td>$produto[0]</td><td>$produto[1]</td><td colspan=\"2\"></td></tr>\n";
            }
        }
        $conteudo .= "</table>";

        $this->associar_visao('produto_produtor/index');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
        $this->visao->substituir_secao('{LISTA}', $conteudo);
        $this->visao->gerar();
    }

}
