<?php

	// Apenas testando, MD5 não deve ser usado para processar senhas
	$md5hash = '5166bc207a30cd8d95ab9cfd532b0a7e';
	$salt1 = 'TAKpH5GM';
	$salt2 = 'k6gwCmFh';

	if (!empty($_POST['senha'])) {
		if (md5(md5(md5($salt1 . $_POST['senha'] . $salt2))) === $md5hash) {
			die("Logado!");
		}
		else {
			die("Senha incorreta! Tente novamente.");
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Expoagro</title>
    <meta charset="UTF-8">
</head>
<body>

    <a href="index.php">Página de testes</a>
    
    <form action="index.php" method="post">
		<input name="senha" type="password">
		<input name="submit" type="submit" value="Fazer login">
    </form>

</body>
</html>
