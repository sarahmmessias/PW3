<html>
    <body>

        <?php

            $user = "root";
            $pass = "";
            $db = "progweb3";
            $server = "localhost";
            $conn = mysqli_connect($server, $user, $pass, $db);

            if($conn->connect_errno)
            {
                die("Erro de conexão" . $conn->connect_error);
            } else {
                echo("Conexão ok");
            }

            require('jwtclass.php');

            $myjwt = new myJWT();
            
            $idUsuario = $_POST["usuario"];
            $senhaUsuario = $_POST["senha"];
            $sql = "select * from usuarios where idusuario = '". $idUsuario ."' and senhausuario = '". $senhaUsuario ."'";
            $resultadoQuery = mysqli_query($conn, $sql);
            if ($resultadoQuery->num_rows == 0){
                die("usuário ou senha inválidos");
            }
            $arrayQuery = $resultadoQuery->fetch_assoc();
            echo "<BR>";
            echo "usuário digitado: " . $arrayQuery["idusuario"];
            echo "<BR>";
            echo "<BR>";
            echo "senha digitada: " . $arrayQuery["senhausuario"];
            
            $payload = [
                'iss' => 'localhost',
                'nome' => $arrayQuery["nomeusuario"],
                'email' => $arrayQuery["email"],
                'time' => time()
            ];

            $time = time();
            
            echo "<BR>";
            echo "<BR>";
            $token = $myjwt->criaToken($payload);
            echo $token;
            
            echo "<BR>";
            echo "<BR>";
            echo "Token validado com sucesso?<br>";
            if ($myjwt->validaToken($token)){
                echo "sim";

                while (time() < $time + 2) {}

                $myjwt -> refreshToken($token);

                
            }else{
                echo "não";
            }
            
        ?>

    </body>
</html>
