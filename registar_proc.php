<?php 
Include "conf/conn.php";
//recebe todas as variaveis do post
$utilizador = $_POST['Utilizador'];
$nome = $_POST['Nome'];
$numeroAluno = $_POST['NumeroAluno'];
$numero = $_POST['Numero'];
$email = $_POST['Email'];
$password = $_POST['Password'];
$passwordConfirmar = $_POST['PasswordConfirmar'];

	//transforma password dada em md5
	$npassword = md5($password);
	//cria a salt key com o sha1 do md5 do usernamme 
	$salt = sha1(md5($email));
	// junta a password md5 com a salt key
	$saltedpassowrd = md5($salt.$npassword);
	$query = "INSERT INTO utilizadores (username,nome,numeroAluno,contacto,email,salt,password) VALUES ('$utilizador','$nome','$numeroAluno','$numero','$email','$salt','$saltedpassowrd')";
	if($result = $conn->query($query)){
		echo "Registado com exito!!";
	} else {
		echo("Error description: " . mysqli_error($conn));
	}
	session_unset();
?>