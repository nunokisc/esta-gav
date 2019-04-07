<?php

//declaração da classe clientes
class tipo_e extends table {
    protected $tabela="tipo_e";
    protected $user=false;

    public function __construct() {
        parent::__construct();
        if (@$_SESSION["user"]) {
            $this->user=$_SESSION["user"];
        }
    }

}