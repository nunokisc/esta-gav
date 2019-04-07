<?php 
Include "conf/conn.php";
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
	header("location:login.php");
}

$tipoPedido = $_GET['tipoPedido'];
$dataLevantamento = date("Y-m-d H:i:s", strtotime(str_replace('/', '-',$_GET['data'])));
$uid = $_SESSION['uId'];
$timestamp = date('w', strtotime($dataLevantamento));
if ($timestamp >= 2 AND $timestamp < 5)
{
	$dataEntrega = date('Y-m-d',strtotime('next Friday',strtotime($dataLevantamento)));
}
else
{
	$dataEntrega = date('Y-m-d',strtotime('next Tuesday',strtotime($dataLevantamento))); 
}

$queryInserirPedidos = "INSERT INTO pedidos (userId, data, tipo, dataLevantamento, dataEntrega) VALUES ('$uid', NOW(), '$tipoPedido', '$dataLevantamento', '$dataEntrega')";
$resultInserirPedidos = $conn->query($queryInserirPedidos);
?>