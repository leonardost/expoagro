<?php

class PontuacaoPremiacaoControle extends Controle {

    private $modelo;
    private $modelo_premiacao;
    private $modelo_pontuacao;

    public function __construct() {
        parent::__construct();

        require_once(ROOT . '/aplicacao/modelo/pontuacao_premiacao.php');
        $this->modelo = new PontuacaoPremiacaoModelo($this->conexao);

        require_once(ROOT . '/aplicacao/modelo/premiacao.php');
        $this->modelo_premiacao = new PremiacaoModelo($this->conexao);

        require_once(ROOT . '/aplicacao/modelo/pontuacao.php');
        $this->modelo_pontuacao = new PontuacaoModelo($this->conexao);
    }

    public function inserir() {

        // Se o envio de informações foi feito
        if (!empty($_POST)) {

            $pontuacao = $_POST['pontuacao'];

            $premios_a_inserir = array();
            foreach ($_POST as $chave => $valor) {
                if (strpos($chave, "premiacao") !== false) {
                    $id_premio = substr($chave, 9);
                    $quantidade_premio = $_POST['quantidade' . $id_premio];
                    array_push($premios_a_inserir, array('id_premio' => $id_premio, 'quantidade' => $quantidade_premio));
                }
            }

            // TODO: Verificar se já existe premiação para a categoria e colocação selecionadas
            // TODO: Verificar se a quantidade de algum item é menor ou igual a 0

            foreach ($premios_a_inserir as $premio) {
                $this->modelo->inserir($pontuacao, $premio['id_premio'], $premio['quantidade']);
            }

            header('Location: /pontuacao_premiacao/');
            exit;
        }

        // Gera o combobox de categorias e colocações

        $lista_pontuacoes = $this->modelo_pontuacao->todos();

        $html_lista_categorias_colocacoes = '';
        if (pg_affected_rows($lista_pontuacoes) == 0) {
            $html_lista_categorias_colocacoes = "<p>Não há categorias e colocações cadastradas</p>\n";
        }
        else {
            $html_lista_categorias_colocacoes = "<select id=\"pontuacao\" name=\"pontuacao\">\n";
            while ($row = recuperar_tuplas($lista_pontuacoes)) {
                $id = $row[0];
                $categoria = $row[1];
                $colocacao = $row[2];
                $pontos = $row[3];
                $html_lista_categorias_colocacoes .= "<option value=\"$id\">$categoria - $colocacao</option>\n";
            }
            $html_lista_categorias_colocacoes .= "</select>\n";
        }

        // Gera a tabela de premiações

        $lista_premiacoes = $this->modelo_premiacao->todos();

        $html_lista_premiacoes = '';
        if (pg_affected_rows($lista_premiacoes) == 0) {
            $html_lista_premiacoes = "<p>Não há premiações cadastradas</p>\n";
        }
        else {
            $html_lista_premiacoes = "<table><tr><th></th><th>Prêmio</th><th>Valor</th><th>Quantidade</th></tr>\n";
            while ($row = recuperar_tuplas($lista_premiacoes)) {
                $id = $row[0];
                $premio = $row[1];
                $valor = $row[2];
                $html_lista_premiacoes .= "
                <tr>
                    <td><input id=\"premiacao$id\" name=\"premiacao$id\" type=\"checkbox\"></td>
                    <td><label for=\"premiacao$id\">$premio</label></td>
                    <td>$valor</td>
                    <td><input name=\"quantidade$id\" type=\"text\" value=\"0\"></td>
                </tr>\n";
            }
            $html_lista_premiacoes .= "</table>\n";
        }

        $this->associar_visao('pontuacao_premiacao/inserir');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
        $this->visao->substituir_secao('{COMBOBOX_CATEGORIAS_COLOCACOES}', $html_lista_categorias_colocacoes);
        $this->visao->substituir_secao('{LISTA_PREMIOS}', $html_lista_premiacoes);
        $this->visao->gerar();

    }

    public function editar($pontuacao) {

        // Se o envio de informações foi feito
        if (!empty($_POST)) {

            $pontuacao = $_POST['pontuacao'];

            $premios_a_inserir = array();
            foreach ($_POST as $chave => $valor) {
                if (strpos($chave, "premiacao") !== false) {
                    $id_premio = substr($chave, 9);
                    $quantidade_premio = $_POST['quantidade' . $id_premio];
                    array_push($premios_a_inserir, array('id_premio' => $id_premio, 'quantidade' => $quantidade_premio));
                }
            }

            // TODO: Verificar se já existe premiação para a categoria e colocação selecionadas
            // TODO: Verificar se a quantidade de algum item é menor ou igual a 0

            // Remove os prêmios antigos relativos a categoria e colocação selecionadas
            $this->modelo->remover($pontuacao);

            // Insere os novos prêmios
            foreach ($premios_a_inserir as $premio) {
                $this->modelo->inserir($pontuacao, $premio['id_premio'], $premio['quantidade']);
            }
            header('Location: /pontuacao_premiacao/');
            exit;

        }

        // Recupera qual é a categoria e a colocação que estamos editando

        $categoria = $this->modelo->buscar_pontuacao($pontuacao);
        $row = recuperar_tuplas($categoria);
        $html_categoria_colocacao = $row[0] . ' ' . $row[1];

        // Gera a tabela de premiações, selecionando os itens já associados

        $lista_premiacoes = $this->modelo_premiacao->todos();

        if (pg_affected_rows($lista_premiacoes) == 0) {
            $conteudo = "<p>Não há premiações relativas a colocações cadastradas</p>\n";
        }
        else {

            // Recupera os itens já associados com esta categoria e colocação
            $lista_premiacoes_associadas = $this->modelo->todos_pontuacao($pontuacao);
            $premios_associados = array();
            while ($row = recuperar_tuplas($lista_premiacoes_associadas)) {
                $premios_associados[$row[0]] = $row[1];
            }

            // Gera a tabela de premiações, selecionando as que já estão associadas
            $html_lista_premiacoes = "<table><tr><th></th><th>Prêmio</th><th>Valor</th><th>Quantidade</th></tr>\n";
            while ($row = recuperar_tuplas($lista_premiacoes)) {
                $id = $row[0];
                $premio = $row[1];
                $valor = $row[2];
                $checked = '';
                $quantidade = 0;
                if (array_key_exists($id, $premios_associados)) {
                    $checked = 'checked';
                    $quantidade = $premios_associados[$id];
                }
                $html_lista_premiacoes .= "
                <tr>
                    <td><input id=\"premiacao$id\" name=\"premiacao$id\" type=\"checkbox\" $checked></td>
                    <td><label for=\"premiacao$id\">$premio</label></td>
                    <td>$valor</td>
                    <td><input name=\"quantidade$id\" type=\"text\" value=\"$quantidade\"></td>
                </tr>\n";
            }
            $html_lista_premiacoes .= "</table>\n";
        }

        $this->associar_visao('pontuacao_premiacao/editar');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
        $this->visao->substituir_secao('{ID_PONTUACAO}', $pontuacao);
        $this->visao->substituir_secao('{CATEGORIA_COLOCACAO}', $html_categoria_colocacao);
        $this->visao->substituir_secao('{LISTA_PREMIOS}', $html_lista_premiacoes);
        $this->visao->gerar();

    }

    public function remover($pontuacao) {
        $this->modelo->remover($pontuacao);
        header('Location: /pontuacao_premiacao/');
        exit;
    }

    public function index() {

        $conteudo = '';
        $lista_pontuacoes_premiacoes = $this->modelo->todos();
        if (pg_affected_rows($lista_pontuacoes_premiacoes) == 0) {
            $conteudo .= "<p>Não há premiações x pontuações cadastradas</p>\n";
        }
        else {
            $conteudo .= "<table>";
            $lista_premios_por_categoria_colocacao = array();
            while ($row = recuperar_tuplas($lista_pontuacoes_premiacoes)) {
                $id = $row[0];
                $categoria = $row[1];
                $colocacao = $row[2];
                $premio = $row[4];
                $quantidade = $row[5];
                if (!array_key_exists($id, $lista_premios_por_categoria_colocacao)) {
                    $lista_premios_por_categoria_colocacao[$id] = array();
                    $lista_premios_por_categoria_colocacao[$id]['premios'] = array();
                    $lista_premios_por_categoria_colocacao[$id]['categoria'] = $categoria;
                    $lista_premios_por_categoria_colocacao[$id]['colocacao'] = $colocacao;
                }
                array_push($lista_premios_por_categoria_colocacao[$id]['premios'], array($premio, $quantidade));
            }
        }

        foreach ($lista_premios_por_categoria_colocacao as $id => $premios) {
            $conteudo .=
                "<tr><th colspan=\"2\">Categoria " . $premios['categoria'] . ", colocação " . $premios['colocacao'] . "</th>" .
                " <th><a href=\"/pontuacao_premiacao/editar/$id/\">editar</a></th>" .
                " <th><a href=\"/pontuacao_premiacao/remover/$id/\">remover</a></th></tr>\n" .
                "<tr><th>Prêmio</th><th>Quantidade</th><td colspan=\"2\"></td></tr>";
            foreach ($premios['premios'] as $premio) {
                $conteudo .= "<tr><td>$premio[0]</td><td>$premio[1]</td><td colspan=\"2\"></td></tr>\n";
            }
        }
        $conteudo .= "</table>";

        //~ foreach ($lista_premios_por_categoria_colocacao as $id => $premios) {
            //~ $conteudo .= "<ul class=\"lista\"><li>Categoria " . $premios['categoria'] . ", colocação " . $premios['colocacao'] .
                //~ " [<a href=\"/pontuacao_premiacao/editar/$id/\">editar</a>]" .
                //~ " [<a href=\"/pontuacao_premiacao/remover/$id/\">remover</a>]\n" .
                //~ "<ul>\n";
            //~ foreach ($premios['premios'] as $premio) {
                //~ $conteudo .= "<li>$premio[0] - $premio[1]</li>\n";
            //~ }
            //~ $conteudo .= "</ul></li></ul>\n";
        //~ }

        $this->associar_visao('pontuacao_premiacao/index');
        $this->visao->substituir_secao_arquivo('{MENU}', 'menu.htm');
        $this->visao->substituir_secao('{LISTA}', $conteudo);
        $this->visao->gerar();

    }

}
