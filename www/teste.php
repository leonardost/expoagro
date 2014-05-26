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

    echo("<p>Testando conexão com o banco 'expoagro'.</p>\n");

    $conn = pg_connect("host=localhost port=5432 dbname=expoagro user=postgres password=postgres")
            or die("<p><b>Problema com a conexao!</b></p>\n");

    $result = pg_query($conn, "SELECT id, nome FROM categoria ORDER BY id");
    echo("<h3>Tabela categoria</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhuma categoria cadastrada.</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>ID</th><th>Nome</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td></tr>\n");
        }
        echo("</table>\n");
    }
    
    $result = pg_query($conn, "SELECT p.id, c.nome, p.colocacao, p.pontos 
            FROM pontuacao p, categoria c 
            WHERE p.categoria = c.id 
            ORDER BY p.id");
    echo("<h3>Tabela pontuacao</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhuma pontuação cadastrada.</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>ID</th><th>Categoria</th><th>Colocação</th><th>Pontos</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr>\n");
        }
        echo("</table>\n");
    }
    
    $result = pg_query($conn, "SELECT id, premio, valor FROM premiacao ORDER BY id");
    echo("<h3>Tabela premiacao</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhuma premiação cadastrada.</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>ID</th><th>Prêmio</th><th>Valor</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n");
        }
        echo("</table>\n");
    }

    $result = pg_query($conn, "SELECT cat.nome, pont.colocacao, prem.premio, pp.quantidade 
            FROM pontuacao_premiacao pp, categoria cat, pontuacao pont, premiacao prem
            WHERE pp.pontuacao = pont.id AND pont.categoria = cat.id AND pp.premiacao = prem.id
            ORDER BY cat.nome, pont.colocacao, prem.premio");
    echo("<h3>Tabela pontuacao_premiacao</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhuma pontuação x premiação cadastrada.</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>Categoria</th><th>Colocação</th><th>Prêmio</th><th>Quantidade</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr>\n");
        }
        echo("</table>\n");
    }

    $result = pg_query($conn, "SELECT id, nome, endereco FROM produtor ORDER BY id");
    echo("<h3>Tabela produtor</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhum produtor cadastrado.</p>\n");
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


    $result = pg_query($conn, "SELECT p.id, p.nome, c.nome 
            FROM produto p, categoria c
            WHERE p.categoria = c.id
            ORDER BY p.id");
    echo("<h3>Tabela produto</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhum produto cadastrado.</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>ID</th><th>Nome</th><th>Categoria</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n");
        }
        echo("</table>\n");
    }

    $result = pg_query($conn, "SELECT p.nome, pr.nome, pp.classificacao 
            FROM produto_produtor pp, produto p, produtor pr
            WHERE pp.produto = p.id AND pp.produtor = pr.id
            ORDER BY p.nome, pp.classificacao, pr.nome");
    echo("<h3>Tabela produto_produtor</h3>\n");
    if (pg_affected_rows($result) == 0) {
        echo("<p>Nenhum produto x produtor cadastrado.</p>\n");
    }
    else {
        echo("<table border=\"1\">\n");
        echo("<tr><th>Produto</th><th>Produtor</th><th>Classificação</th></tr>\n");
        while ($row = pg_fetch_row($result)) {
            echo("<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n");
        }
        echo("</table>\n");
    }

    pg_close($conn);

    echo("<h1>OK!</h1>");

?>

</body>
</html>
