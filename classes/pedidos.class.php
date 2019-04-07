<?php

//declaração da classe clientes
class pedidos extends table {
    protected $tabela="pedidos";
    protected $user=false;

    public function __construct() {
        parent::__construct();
        if (@$_SESSION["user"]) {
            $this->user=$_SESSION["user"];
        }
    }

}