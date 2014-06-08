<?php

    session_start();

    if (empty($_SESSION['logado'])) {
        header('Location: login.php');
        exit;
    }

    require_once('conexao.php');
    
    $conexao = conectar();

	$id = -1;
	$nome = '';

    $erros = array();

	if (!empty($_GET)) {
		$id = $_GET['id'];
		
		$resultado = executar_sql($conexao, 'SELECT id, nome FROM categoria WHERE id = ' . $id);
		$tupla = recuperar_tuplas($resultado);

		$id = $tupla[0];
		$nome = $tupla[1];
	}

    if (!empty($_POST)) {
		$id = $_POST['id'];

        if (empty($_POST['nome'])) {
            $erros['nome'] = true;
        }
        else {
			$nome = $_POST['nome'];

            $resultado = executar_sql($conexao, 'UPDATE categoria SET nome = \'' . $nome . '\' WHERE id = ' . $id);
            header('Location: categoria.php');
            exit;
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Editar Categoria</title>
        <link href="css/estilo.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        
        <section>
            
			<h1>Editar Categoria</h1>
			
			<form action="categoria_editar.php" method="post">
				<input name="id" type="hidden" value="<?php echo $id ?>">
				<label for="nome">Nome</label><input type="text" id="nome" name="nome" autofocus="autofocus" value="<?php echo $nome ?>"><br>
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
