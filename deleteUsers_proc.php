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
	$queryUtilizadores = new utilizadores();
	$resultDeleteUsers = $queryUtilizadores->delete(array('id'=>$id));
	// $queryDeleteUsers = "DELETE FROM utilizadores WHERE id=".$id;
	// $resultDeleteUsers = $conn->query($queryDeleteUsers);
?>