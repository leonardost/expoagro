<?php

require_once(realpath($_SERVER['DOCUMENT_ROOT']) . '/controle/controle.php');

class LoginControle extends Controle {

	public function executar() {
		// Apenas testando, MD5 não deve ser usado para processar senhas
		$md5hash = '5166bc207a30cd8d95ab9cfd532b0a7e';
		$salt1 = 'TAKpH5GM';
		$salt2 = 'k6gwCmFh';

		$erros = array();
		if (!empty($_POST['senha'])) {
			if (md5(md5(md5($salt1 . $_POST['senha'] . $salt2))) === $md5hash) {
				// Seta a flag 'logado' e move o navegador para a página index
				$_SESSION['logado'] = true;
				header('Location: /index/');
				exit;
			}
			else {
				$erros['login'] = true;
			}
		}

		if (!empty($erros['login']) && $erros['login'] === true) {
			$this->visao->substituir_secao('{MENSAGEM_ERRO}', '<p class="erro">Senha inválida. Tente novamente</p>');
		}
		else {
			$this->visao->substituir_secao('{MENSAGEM_ERRO}', '');
		}

		$this->visao->gerar();
	}
	
}
