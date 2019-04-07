<?php
spl_autoload_register(function($nome) {
    include "$nome.class.php"; //inclui a class
});
$table=new utilizadores();
$join=array();
$on=array();
$where=array();

$join[0] = "jakim";
$on['ini.lol']="ini.teste";
$where['lol'] = "teste";

$table->select($join,$on,array('id'=>'lol'));
echo "lol"; 

?>