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
$val = $_POST['val'];
$field = $_POST['field'];
echo $field,$val,$id;
$queryEquipamentos = new equipamentos();
if($field == "qtd")
{
	$resultUpdateQtd = $queryEquipamentos->update(array('qtd'=>$val),array('id'=>$id));
	// $queryUpdateQtd = "UPDATE equipamentos SET qtd=".$val." WHERE id=".$id;
	// $resultUpdateQtd = $conn->query($queryUpdateQtd);
}
else if($field == "model")
{
	$resultUpdateModel = $queryEquipamentos->update(array('modelo'=>$val),array('id'=>$id));
	// $queryUpdateModel = "UPDATE equipamentos SET modelo='".$val."' WHERE id=".$id;
	// $resultUpdateModel = $conn->query($queryUpdateModel);
}
else if($field == "obs")
{
	$resultUpdateObs = $queryEquipamentos->update(array('obs'=>$val),array('id'=>$id));
	// $queryUpdateObs = "UPDATE equipamentos SET obs='".$val."' WHERE id=".$id;
	// $resultUpdateObs = $conn->query($queryUpdateObs);
}
?>