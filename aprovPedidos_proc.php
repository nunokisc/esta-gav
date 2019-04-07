<?php 
	Include "conf/conn.php";
	spl_autoload_register(function($nome) {
	    include "classes/$nome.class.php"; //inclui a class
});
	session_start();
	if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
		header("location:login.php");
	}
	$id = $_POST['id'];
	$queryPedidos = new pedidos();
	$resultAprovPedidos = $queryPedidos->update(array('aprovado'=>'1'),array('id'=>$id));
	// $queryAprovPedidos = "UPDATE pedidos SET aprovado=1 WHERE id=".$id;
	// $resultAprovPedidos = $conn->query($queryAprovPedidos);
?>