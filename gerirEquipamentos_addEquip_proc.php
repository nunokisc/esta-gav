 <?php 
Include "conf/conn.php";
spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
});
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
	header("location:login.php");
}
$tipo = $_POST['tipo'];
$qtd = $_POST['qtd'];
$model = $_POST['model'];
$obs = $_POST['obs'];

$queryEquipamentos = new equipamentos();
$resultInsertEquip = $queryEquipamentos->insert(array('tipo'=>$tipo,'qtd'=>$qtd,'modelo'=>$model,'obs'=>$obs));
// $insertEquip = "INSERT INTO equipamentos(tipo, qtd, modelo, obs) VALUES ('$tipo', '$qtd', '$model', '$obs')";
// $resultInsertEquip = $conn->query($insertEquip);
?>