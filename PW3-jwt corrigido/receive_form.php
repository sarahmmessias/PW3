<html><body>
<?php
require 'jwtclass.php';
$myjwt = new myJWT();
$user = "admin";
$pass = "ProgWeb3";
$db = "progweb3";
$conn = mysqli_connect("127.0.0.1", $user, $pass, $db);
if ($conn->connect_errno){
    die("Erro de conexão" . $conn->connect_error);
} 

$idUsuario = $_POST["usuario"];
$senhaUsuario = $_POST["senha"];
$sql = "select * from usuarios where idusuario = '". $idUsuario ."' and senhausuario = '". $senhaUsuario ."'";
$resultadoQuery = mysqli_query($conn, $sql);
if ($resultadoQuery->num_rows == 0 ){
    die("usuário ou senha inválidos");
}
$arrayQuery = $resultadoQuery->fetch_assoc();
echo "<BR>";
echo "usuário digitado: " . $arrayQuery["idusuario"];
echo "<BR>";
echo "<BR>";
echo "senha digitada: " . $arrayQuery["senhausuario"];

$time_exp = time()+300;
$payload = [
    'iss' => 'localhost',
    'exp' => $time_exp,
    'nome' => $arrayQuery["nomeusuario"],
    'email' => $arrayQuery["email"]
 ];

 echo "<BR>";
 echo "<BR>";
 echo "Token criado: <br>";
 echo "<BR>";
  $token = $myjwt->criaToken($payload);
 echo $token;

 $time_exp = time()+86400;
 $payload_refresh = [
    'iss' => 'localhost',
    'exp' => $time_exp,
    'nome' => $arrayQuery["nomeusuario"],
    'email' => $arrayQuery["email"]
 ];
 echo "<BR>";
 echo "<BR>";
 echo "Refresh token: <br>";
 echo "<BR>";
 $refresh_token = $myjwt->criaToken($payload_refresh);
 echo $refresh_token."<BR>";
 $signature_refresh_token = $myjwt->extractDataJWT($refresh_token, 2);
 $sql = "update usuarios set refreshtoken = '".$signature_refresh_token."' where email = '". $arrayQuery["email"] ."'";
 $resultadoQuery = mysqli_query($conn, $sql);
?>
</body>
</html>
