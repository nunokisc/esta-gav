<?php
$servername = "localhost";
$username = "esta_gav";
$password = "Estagav1234.";
$dbname = "esta_av_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
mysqli_set_charset($conn,'utf8');

$query = "SELECT * FROM configuracoes";
$siteInfoQuery = $conn->query($query);
$siteInfo = mysqli_fetch_assoc($siteInfoQuery);
?>