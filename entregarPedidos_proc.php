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
	$queryEquipamentoEmUso = new equipamentos_em_uso();

	$resultEntregarPedidos = $queryPedidos->update(array('entregue'=>'1','levantado'=>'0','finalizado'=>'1','dataEntrega'=>date('Y-m-d H:i:s')),arra('id'=>$id));
	// $queryEntregarPedidos = "UPDATE pedidos SET entregue=1, levantado = 0, finalizado = 1, dataEntrega = NOW() WHERE id=".$id;
	// $resultEntregarPedidos = $conn->query($queryEntregarPedidos);
	$resultDeleteEquipPedido = $queryEquipamentoEmUso->delete(array('idPedido'=>$id));
	// $queryDeleteEquipPedido = "DELETE FROM equipamentos_em_uso WHERE idPedido=".$id;
	// $resultDeleteEquipPedido = $conn->query($queryDeleteEquipPedido);
?>