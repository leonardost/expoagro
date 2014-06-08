<?php

    session_start();

    if (empty($_SESSION['logado'])) {
        header('Location: login.php');
        exit;
    }
    
    require_once('conexao.php');
    require_once('modelo/categoria.php');

    //~ $categoria = new Categoria(1, 'ASDF');    
    //~ echo '<pre>';
    //~ print_r($categoria);
    //~ echo '</pre>';

    $conexao = conectar();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Categorias</title>
        <link href="css/estilo.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        
        <section>
            
            <h1>Categorias</h1>

            <a href="categoria_inserir.php">Inserir nova categoria</a>

            <table>
                <tr>
                    <th>Seleção</th>
                    <th>Nome</th>
                    <th>Editar</th>
                    <th>Remover</th>
                </tr>
<?php

    $result = executar_sql($conexao, "SELECT id, nome FROM categoria ORDER BY nome");

    if (pg_affected_rows($result) == 0) {
        echo("<tr><td colspan=\"4\">Nenhuma categoria cadastrada.</td></tr>\n");
    }
    else {
        while ($row = pg_fetch_row($result)) {
            echo("
                <tr>
                    <td></td>
                    <td>$row[1]</td>
                    <td><a href=\"categoria_editar.php?id=$row[0]\">Editar</a></td>
                    <td><a href=\"categoria_remover.php?id=$row[0]\">Remover</a></td>
                </tr>\n"
            );
        }
    }

?>
            </table>

        </section>
        
    </body>
</html>
<?php
    desconectar($conexao);
?>
