<?php

    session_start();

    if (empty($_SESSION['logado'])) {
        header('Location: login.php');
        exit;
    }

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Expoagro</title>
    <meta charset="UTF-8">
    <link type="text/css" href="css/estilo.css" rel="stylesheet">
</head>
<body>

    <section>
        <p class="deslogar"><a href="deslogar.php">Deslogar</a></p>
        <ul>
            <li><a href="categoria.php">Categorias</a></li>
            <li><a href="teste.php">Página de testes</a></li>
        </ul>
    </section>

</body>
</html>
