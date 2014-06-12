<?php

require_once(realpath($_SERVER['DOCUMENT_ROOT']) . '/controle/controle.php');

class DeslogarControle extends Controle {
	
	public function executar() {
		session_destroy();
		$_SESSION = array();
		header('Location: /');
	}

}
