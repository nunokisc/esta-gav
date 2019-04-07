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
	$queryPedidos= new pedidos();
	$resultDeletePedidos = $queryPedidos->delete(array('id'=>$id));
	// $queryDeletePedidos = "DELETE FROM pedidos WHERE id=".$id;
	// $resultDeletePedidos = $conn->query($queryDeletePedidos);
?>