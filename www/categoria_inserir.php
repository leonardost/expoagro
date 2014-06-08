<?php

    session_start();

    if (empty($_SESSION['logado'])) {
        header('Location: login.php');
        exit;
    }

    require_once('conexao.php');
    
    $conexao = conectar();

    $erros = array();
    
    if (!empty($_POST)) {
        if (empty($_POST['nome'])) {
            $erros['nome'] = true;
        }
        else {
            $resultado = executar_sql($conexao, 'INSERT INTO categoria (id, nome) VALUES (default, \'' . $_POST['nome'] . '\')');
            header('Location: categoria.php');
            exit;
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Inserir Categoria</title>
        <link href="css/estilo.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        
        <section>
            
			<h1>Inserir Categoria</h1>
			
			<form action="categoria_inserir.php" method="post">
				<label for="nome">Nome</label><input type="text" id="nome" name="nome" autofocus="autofocus"><br>
<?php
    if ($erros['nome'] === true) {
        echo("<p class=\"erro\">Nome vazio!</p>\n");
    }
?>
				<input type="submit" value="Salvar">
			</form>

        </section>
        
    </body>
</html>
<?php
    desconectar();
?>
