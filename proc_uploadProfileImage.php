<?php 
Include "conf/conn.php";
spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
});
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
    header("location:login.php");
}
$uid = $_SESSION['uId'];
$queryUtilizadores = new utilizadores();
//se existe um post de um ficheiro e se não existe erro no upload...
if (isset($_FILES["userImage"]) and $_FILES["userImage"]["error"]==0) {

    $name= $_FILES["userImage"]["name"];
    $extensao=strtolower(substr($name,strrpos($name,".")+1 ));
    $imgData =addslashes(file_get_contents($_FILES['userImage']['tmp_name']));

    if ($extensao=="jpg" or $extensao=="gif" or $extensao=="png") {

        $resultPedidos=$queryUtilizadores->update(array('imagem'=>$imgData),array('id'=>$uid));
        // $queryPedidos = "UPDATE utilizadores SET imagem='$imgData' WHERE id = '$uid'";
        // $resultPedidos = $conn->query($queryPedidos);
        echo "Inserido com sucesso";

    } else {

        echo "Ficheiros $extensao não aceites";

    }

}