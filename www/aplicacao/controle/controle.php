<?php

abstract class Controle {
	
	protected $visao;
	protected $conexao;
	
	public function __construct() {
		$this->conexao = conectar();
	}
	
	public function __destruct() {
		desconectar($this->conexao);
	}

	public function associar_visao($pagina) {
        $this->visao = new Visao($pagina);
	}

    public abstract function index();

}
