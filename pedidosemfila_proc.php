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
	$resultPedidosFila = $queryPedidos->select(array('modelo','qnt','pedidos_linha.id'),array('pedidos_linha','equipamentos'),array('pedidos_linha.idPedido'=>'pedidos.id','pedidos_linha.idEquipamentos'=>'equipamentos.id'),array('userId'=>$_SESSION['uId'],'terminado'=>'0'));
	// $queryPedidosFila = "SELECT modelo, qnt, pedidos_linha.id FROM pedidos
	// 						INNER JOIN pedidos_linha ON pedidos_linha.idPedido = pedidos.id
	// 							INNER JOIN equipamentos ON pedidos_linha.idEquipamentos = equipamentos.id
	// 								WHERE userId = '".$_SESSION['uId']."' and terminado = 0";
	// $resultPedidosFila = $conn->query($queryPedidosFila);
	$pedidos = "";
	if( mysqli_num_rows ($resultPedidosFila) > 0 ){
		while($pedidosFila = mysqli_fetch_array($resultPedidosFila))
		{
			
			$pedidos = $pedidos . '<li><i class="fa fa-times text-danger" onclick="deletePedidosFila(this);return false;" id="'.$pedidosFila['id'].'"></i> Equipamentos: '.$pedidosFila['modelo'].' Quantidade: '.$pedidosFila['qnt'].'</li>';


		}
	}
	else
	{

		$pedidos = '<li>Não existem materiais!</li>';

	}
	$items[] = $pedidos;
	$items[] = mysqli_num_rows ($resultPedidosFila);

echo json_encode($items);

?>
