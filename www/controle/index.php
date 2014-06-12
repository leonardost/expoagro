<?php

require_once(realpath($_SERVER['DOCUMENT_ROOT']) . '/controle/controle.php');

class IndexControle extends Controle {
	
	public function executar() {
		$this->visao->gerar();
	}

}
