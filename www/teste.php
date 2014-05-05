<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Página de teste</title>
    <meta charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
    <style>
        *, body {
            font-family: 'open sans', sans-serif, arial;
        }
        table {
            border-collapse: collapse;
            border: 1px solid #000;
        }
        table th, table td {
            padding:5px;
            background-color: #BBB;
            border: 1px solid #000;
        }
        table td {
            background-color: #EEE;
        }
    </style>
</head>
<body>

<?php

    echo("<p>Testando conexão com o banco 'expoagro'</p>\n");

    $conn = pg_connect("host=localhost port=5432 dbname=expoagro user=postgres password=postgres")
        or die("<p><b>Problema com a conexao!</b></p>\n");

    $result = pg_query($conn, "SELECT id, nome, endereco FROM produtor");
    echo("<h3>Tabela produtor</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhum produtor cadastrado</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>ID</th><th>Nome</th><th>Endereço</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr>\n");
            echo("<td>$row[0]</td><td>$row[1]</td><td>$row[2]</td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
    }

    $result = pg_query($conn, "SELECT id, nome FROM categoria");
    echo("<h3>Tabela categoria</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhuma categoria cadastrada</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>ID</th><th>Nome</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td></tr>\n");
        }
        echo("</table>\n");
    }

    $result = pg_query($conn, "SELECT id, premio, valor FROM premiacao");
    echo("<h3>Tabela premiacao</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhuma premiação cadastrada</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>ID</th><th>Prêmio</th><th>Valor</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n");
        }
        echo("</table>\n");
    }

    $result = pg_query($conn, "SELECT id, nome, categoria FROM produto");
    echo("<h3>Tabela produto</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhum produto cadastrado</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>ID</th><th>Nome</th><th>Categoria</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n");
        }
        echo("</table>\n");
    }

    $result = pg_query($conn, "SELECT produto, produtor, classificacao FROM produto_produtor");
    echo("<h3>Tabela produto_produtor</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhum produto x produtor cadastrado</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>Produto</th><th>Produtor</th><th>Classificação</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n");
        }
        echo("</table>\n");
    }

    $result = pg_query($conn, "SELECT pontuacao, premiacao, categoria FROM pontuacao_premiacao");
    echo("<h3>Tabela pontuacao_premiacao</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhuma pontuação x premiação cadastrada</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>Pontuação</th><th>Premiação</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td></tr>\n");
        }
        echo("</table>\n");
    }

    pg_close($conn);

    echo("<h1>OK!</h1>");

?>

</body>
</html>
