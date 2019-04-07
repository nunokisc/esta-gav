<?php 
	Include "conf/conn.php";
	spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
	});
	session_start();
	if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
		header("location:login.php");
	}
	
	$qtd = $_POST['qtd'];
	$idEqui = $_POST['idEqui'];
	$uid = $_SESSION['uId'];
	
	$queryPedidos=new pedidos();
	$resultPedidos = $queryPedidos->select('','','',array('userId'=>$uid,'terminado'=>'0'));
	$queryPedidosLinha = new pedidos_linha();

	// $queryPedidos = "SELECT * FROM pedidos WHERE userId = '$uid' and terminado = 0";
	// $resultPedidos = $conn->query($queryPedidos);

		$pedidos = mysqli_fetch_array($resultPedidos);
		$idPedido =  $pedidos['id'];
		$resultInserirPedidosFila = $queryPedidosLinha->insert(array('idPedido'=>$idPedido,'idEquipamentos'=>$idEqui,'qnt'=>$qtd,'data'=>	date('Y-m-d H:i:s')));
		// $queryInserirPedidosFila = "INSERT INTO pedidos_linha (idPedido, idEquipamentos, qnt, data) VALUES ('$idPedido', '$idEqui', '$qtd', NOW())";
		// $resultInserirPedidosFila = $conn->query($queryInserirPedidosFila);
		if($resultInserirPedidosFila)
		{
			echo 1;
		}
		else
		{
			echo  mysqli_error($conn);
		}


	
	
	
	
?>