<?php

/**
 * Visao
 * 
 * Classe que cuida da apresentação das páginas. Os templates usam
 * placeholders definidos entre chaves ('{' e '}') para apresentação
 * do conteúdo. Ver /aplicacao/visao/template.htm.
 */
class Visao {
    
    private $conteudo;
    
    /**
     * Construtor($pagina)
     *
     * Argumentos:
     *     $pagina é a página que será mostrada
     */
    public function __construct($pagina) {

        // Lê o arquivo de template que serve de base para todas as páginas
        $template = file_get_contents(ROOT . '/aplicacao/visao/template.htm');
        if ($template === false) {
            die('Problema ao abrir o arquivo de template');  // TODO: Mover para módulo de log
        }
        $this->conteudo = $template;

        // Lê a página que deverá ser apresentada
        $arquivo = ROOT . '/aplicacao/visao/' . $pagina . '.htm';
        $conteudo = file_get_contents($arquivo);
        if ($conteudo === false) {
            die('Problema ao abrir o arquivo de visão ' . $arquivo);  // TODO: Mover para módulo de log
        }

        // Integra a página ao template
        $this->substituir_secao('{CONTEUDO}', $conteudo);

    }
    
    /**
     * substituir_secao($secao, $conteudo)
     *
     * Substitui uma seção do conteúdo pelo conteúdo passado como parâmetro
     *
     * Argumentos:
     *     $secao é o placeholder a ser substituído no template
     *     $conteudo é o que irá substituir o placeholder
     * 
     * Exemplos:
     *     substituir_secao('{CONTEUDO}', '<p>Isto é o que vai aparecer na página</p>');
     */ 
    public function substituir_secao($secao, $conteudo) {
        $this->conteudo = str_replace($secao, $conteudo, $this->conteudo);
    }
    
    public function substituir_secao_arquivo($secao, $arquivo) {
        $arquivo = ROOT . '/aplicacao/visao/' . $arquivo;
        $conteudo = file_get_contents($arquivo);
        if ($conteudo === false) {
            die('Problema ao abrir o arquivo de visão ' . $arquivo);  // TODO: Mover para módulo de log
        }
        
        $this->substituir_secao($secao, $conteudo);
    }

    /**
     * gerar()
     *
     * Gera a visualização da página, retirando placeholders não utilizados.
     */
    public function gerar() {
        $this->conteudo = preg_replace('/\{[^}]*\}/', '', $this->conteudo);
        echo $this->conteudo;
    }
    
}
