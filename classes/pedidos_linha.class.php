<?php

//declaração da classe clientes
class pedidos_linha extends table {
    protected $tabela="pedidos_linha";
    protected $user=false;

    public function __construct() {
        parent::__construct();
        if (@$_SESSION["user"]) {
            $this->user=$_SESSION["user"];
        }
    }

}