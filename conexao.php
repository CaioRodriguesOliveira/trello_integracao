<?php
// Define o banco de dados

$conexao = 'LAB';

if ($conexao == 'LAB') {
    // Configurações de conexão com o LABORATÓRIO
    $servername = "";
    $username = "";
    $password = "";
    $database = "";

}

if ($conexao == 'PROD') {
    // Configurações de conexão com o PRODUÇÃO
    $servername = "";
    $username = "";
    $password = "";
    $database = "";
}

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $database);

// Verifica a conexão
if ($conn->connect_error) {
    die("<br>Erro na conexão: " . $conn->connect_error);
}
mysqli_set_charset($conn, 'utf8');

echo "<br>Conexão ao ".$conexao." bem-sucedida!";
//mysql_set_charset('utf8');

// Fechar a conexão (opcional, geralmente não é necessário)
// $conn->close();
?>
