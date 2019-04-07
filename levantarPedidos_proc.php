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
	$resultLevantarPedidos = $queryPedidos->update(array('levantado'=>'1','dataLevantamento'=>date('Y-m-d H:i:s')),array('id'=>$id));
	// $queryLevantarPedidos = "UPDATE pedidos SET levantado=1, dataLevantamento=NOW() WHERE id=".$id;
	// $resultLevantarPedidos = $conn->query($queryLevantarPedidos);
?>