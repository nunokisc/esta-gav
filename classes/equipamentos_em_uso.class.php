<?php

//declaração da classe clientes
class equipamentos_em_uso extends table {
    protected $tabela="equipamentos_em_uso";
    protected $user=false;

    public function __construct() {
        parent::__construct();
        if (@$_SESSION["user"]) {
            $this->user=$_SESSION["user"];
        }
    }

}