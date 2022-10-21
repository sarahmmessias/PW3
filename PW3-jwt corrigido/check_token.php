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

$access_token = $_POST["access_token"];
$refresh_token = $_POST["refresh_token"];
if($myjwt->validaToken($access_token) == false){
    die("Access token inválido. Cancelando processamento");
}
if($myjwt->validaToken($refresh_token) == false){
    die("Refresh token inválido. Cancelando processamento");
}

echo "O access token está expirado?<BR>";
$access_token_expirado = $myjwt->isExpiredToken($access_token);
if ($access_token_expirado){
    echo "Sim. Necessário validar Refresh Token<BR>";
    $refresh_token_expirado = $myjwt->isExpiredToken($refresh_token);
    if ($refresh_token_expirado){
        echo "Refresh token expirado. Necessário novo login<BR>";
    }else{
        echo "Refresh token dentro da validade. Gerando novo access token e refresh token<BR>";
        $payload_refresh_token = $myjwt->extractDataJWT($refresh_token, 1);
        $signature_refresh_token = $myjwt->extractDataJWT($refresh_token, 2);
        $payload_access_token = $myjwt->extractDataJWT($access_token, 1);
        $sql = "select * from usuarios where email = '". $payload_access_token->email ."'";
        $resultadoQuery = mysqli_query($conn, $sql);
        if ($resultadoQuery->num_rows == 0 ){
            die("usuário ou senha inválidos");
        }
        $arrayQuery = $resultadoQuery->fetch_assoc();
        if ($arrayQuery["refreshtoken"] != $signature_refresh_token){
            $sql = "update usuarios set refreshtoken = '' where email = '". $payload_access_token->email ."'";
            $resultadoQuery = mysqli_query($conn, $sql);
            die("Erro na validação do refresh token! Por favor alterar sua senha");
        }else{
            $payload_refresh_token->exp = time()+86400;
            $new_refresh_token = $myjwt->criaToken($payload_refresh_token);
            $signature_refresh_token = $myjwt->extractDataJWT($new_refresh_token, 2);
            $sql = "update usuarios set refreshtoken = '".$signature_refresh_token."' where email = '". $payload_access_token->email ."'";
            $resultadoQuery = mysqli_query($conn, $sql);
            $payload_access_token->exp = time()+300;
            $new_access_token = $myjwt->criaToken($payload_access_token);
            echo "<BR>";
            echo "Novo access token: <br> " . $new_access_token;
            echo "<BR>";
            echo "<BR>";
            echo "Novo refresh token: <br> " . $new_refresh_token;
        }
    }
}else {
    echo "Não. Exibindo informações da página<BR>";
}
?>
</body>
</html>
