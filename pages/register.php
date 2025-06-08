<?php
include("../connect.php");

if (isset($_POST['signUp'])) {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];

    $checkTelefone = "SELECT * FROM cliente WHERE telefone = '$telefone'";
    $result = $conn->query($checkTelefone);

    if ($result->num_rows > 0) {
        echo "Telefone já cadastrado";
    } else {
        $insertQuery = "INSERT INTO cliente (NOME, CPF, TELEFONE, SENHA) VALUES ('$nome', '$cpf', '$telefone', '$senha')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: ../index.php");
            exit();
        } else {
            echo "Erro: " . $conn->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM cliente WHERE nome = '$nome' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['nome'] = $row['NOME'];
        header("Location: servicos.php");
        exit();
    } else {
        echo "Usuário e/ou senha incorretos";
    }
}
?>