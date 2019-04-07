<?php 
	Include "conf/conn.php";
	spl_autoload_register(function($nome) {
    	include "classes/$nome.class.php"; //inclui a class
	});
	session_start();
	if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
		header("location:login.php");
	}
	$queryPedidos = new pedidos();
	$queryEquipamentosEmUso = new equipamentos_em_uso();
	// $queryPedidosFila = "SELECT pedidos.id as pedidoId, equipamentos.id, qnt ,dataLevantamento FROM pedidos
	// 							INNER JOIN pedidos_linha ON pedidos_linha.idPedido = pedidos.id
	// 								INNER JOIN equipamentos ON pedidos_linha.idEquipamentos = equipamentos.id
	// 									WHERE userId = '".$_SESSION['uId']."' and terminado = 0";
	// $resultPedidosFila = $conn->query($queryPedidosFila);
	$resultPedidosFila = $queryPedidos->select(array('pedidos.id as pedidoId','equipamentos.id','qnt','dataLevantamento'),array('pedidos_linha','equipamentos'),array('pedidos_linha.idPedido'=>'pedidos.id','pedidos_linha.idEquipamentos'=>'equipamentos.id'),array('userId'=>$_SESSION['uId'],'terminado'=>'0'));

	if( mysqli_num_rows ($resultPedidosFila) > 0 ){
		while($pedidosFila = mysqli_fetch_array($resultPedidosFila))
		{
			$resultInsertEquipUse = $queryEquipamentosEmUso->insert('idPedido'=>$pedidosFila['pedidoId'],'idEquip'=>$pedidosFila['id'],'qnt'=>$pedidosFila['qnt'],'data'=>$pedidosFila['dataLevantamento']);
			// $queryInsertEquipUse = "INSERT INTO equipamentos_em_uso(idPedido, idEquip, qnt, data) VALUES ('".$pedidosFila['pedidoId']."','".$pedidosFila['id']."','".$pedidosFila['qnt']."','".$pedidosFila['dataLevantamento']."')";
   //  		$resultInsertEquipUse = $conn->query($queryInsertEquipUse);
		}
		$resultFinalizarPedido = $queryPedidos->update(array('data'=>date('Y-m-d H:i:s'),'terminado'=>'1'),array('userId'=>$_SESSION['uId']));
		// $queryFinalizarPedido = "UPDATE pedidos SET data=NOW(), terminado=1 WHERE userId = '".$_SESSION['uId']."'";
  //   	$resultFinalizarPedido = $conn->query($queryFinalizarPedido);
	} else {
		echo "Não tem pedidos em fila para finalizar!";
	}
	  

?>