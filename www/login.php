<?php

    session_start();

	// Apenas testando, MD5 não deve ser usado para processar senhas
    $md5hash = '5166bc207a30cd8d95ab9cfd532b0a7e';
    $salt1 = 'TAKpH5GM';
    $salt2 = 'k6gwCmFh';

    $erros = array();
    if (!empty($_POST['senha'])) {
        if (md5(md5(md5($salt1 . $_POST['senha'] . $salt2))) === $md5hash) {
            $_SESSION['logado'] = true;
        }
		else {
            $erros['login'] = true;
        }
    }

    if (!empty($_SESSION['logado'])) {
        header('Location: index.php');
        exit;
    }

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Expoagro - Login</title>
    <meta charset="utf-8">
    <link type="text/css" href="css/estilo.css" rel="stylesheet">
</head>
<body>

    <form id="login" action="login.php" method="post">
        
        <h2>Expoagro</h2>
        
        <label for="senha">Senha</label>
        <input id="senha" type="password" name="senha" autofocus="autofocus"></input>
        <input type="submit" value="Logar"></input>
<?php if ($erros['login'] === true) { ?>
        <p class="erro">Senha inválida. Tente novamente</p>
<?php } ?>
        
        <p><a href="teste.php">Página de testes</a></p>

    </form>

</body>
</html>
