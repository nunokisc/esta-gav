<?php

//declaração da classe clientes
class utilizadores extends table {
    protected $tabela="utilizadores";
    protected $user=false;

    public function __construct() {
        parent::__construct();
        if (@$_SESSION["user"]) {
            $this->user=$_SESSION["user"];
        }
    }

}
