<?php 
Include "conf/conn.php";
session_start();

// as variáveis login e password recebem os dados digitados na página anterior
$email = $_POST['Email'];
$password = $_POST['Password'];

$salt = sha1(md5($email));
$npassword = md5($password);
$saltedpassowrd = md5($salt.$npassword);


// A variavel $result pega as varias $login e $password, faz uma pesquisa na tabela de usuarios
$query = "SELECT * FROM utilizadores WHERE email = '$email' AND password= '$saltedpassowrd' AND salt= '$salt'";
$result = $conn->query($query);
$r = mysqli_fetch_row($result);



//echo $result;
/* Logo abaixo temos um bloco com if e else, verificando se a variável $result foi bem sucedida, ou seja se ela estiver encontrado algum registro idêntico o seu valor será igual a 1, se não, se não tiver registros seu valor será 0. Dependendo do resultado ele redirecionará para a pagina site.php ou retornara  para a pagina do formulário inicial para que se possa tentar novamente realizar o login */
if(mysqli_num_rows ($result) > 0 )
{
	$_SESSION['uId'] = $r[0];
	$_SESSION['username'] = $r[1];
	$_SESSION['nome'] = $r[2];
	$_SESSION['email'] = $email;
	$_SESSION['password'] = $saltedpassowrd;
	$_SESSION['cargo'] = $r[9];
	$queryBlock = "SELECT * FROM pedidos WHERE userId = '".$r[0]."' and terminado = 1 and levantado = 1 and DATE(dataEntrega) < DATE(NOW()) and entregue = 0";
	$resultBlock = $conn->query($queryBlock);
	if(mysqli_num_rows ($resultBlock) > 0 )
	{
		$_SESSION['block'] = 1;
	}
	else
	{
		$_SESSION['block'] = 0;
	}
	header('location:index.php');
}
else{
	unset ($_SESSION['uId']);
	unset ($_SESSION['username']);
	unset ($_SESSION['nome']);
	unset ($_SESSION['email']);
	unset ($_SESSION['password']);
	unset ($_SESSION['cargo']);
	unset ($_SESSION['block']);
	session_destroy();
	header('location:login.php');
	
	}

?>