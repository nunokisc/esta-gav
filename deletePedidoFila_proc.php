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
	$queryPedidosLinha = new pedidos_linha();
	$cargosesultCargos = $queryPedidosLinha->delete(array('id'=>$id));
	// $queryDeletePedido = "DELETE FROM pedidos_linha WHERE id = ".$id."";
 //    $cargosesultCargos = $conn->query($queryDeletePedido);
?>