<?php

//declaração da classe clientes
class equipamentos extends table {
    protected $tabela="equipamentos";
    protected $user=false;

    public function __construct() {
        parent::__construct();
        if (@$_SESSION["user"]) {
            $this->user=$_SESSION["user"];
        }
    }

}