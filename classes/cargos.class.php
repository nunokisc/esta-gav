<?php

//declaração da classe clientes
class cargos extends table {
    protected $tabela="cargos";
    protected $user=false;

    public function __construct() {
        parent::__construct();
        if (@$_SESSION["user"]) {
            $this->user=$_SESSION["user"];
        }
    }

}
