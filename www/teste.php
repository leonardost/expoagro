<!DOCTYPE html>
<html>
<head>
    <title>Página de teste</title>
    <meta charset="UTF-8">
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
            echo("<td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><br>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
    }

    pg_close($conn);

    echo("<h1>OK!</h1>");

?>

</body>
</html>
