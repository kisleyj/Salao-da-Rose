<?php
session_start(); // Mantenha o session_start() no topo para lidar com sessões

include("../connect.php"); // Inclui o arquivo de conexão

// --- Lógica de Cadastro do Cliente (signUp) ---
if (isset($_POST['signUp'])) {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];

    // 1. Verificação de Telefone Existente (Consulta direta)
    $checkTelefone = "SELECT * FROM cliente WHERE TELEFONE = '$telefone'";
    $resultCheck = $conn->query($checkTelefone);

    if ($resultCheck->num_rows > 0) {
        echo "Telefone já cadastrado.";
    } else {
        // 2. Inserção do Cliente (Consulta direta)
        $insertQuery = "INSERT INTO cliente (NOME, CPF, TELEFONE, SENHA) VALUES ('$nome', '$cpf', '$telefone', '$senha')";

        if ($conn->query($insertQuery) === TRUE) {
            // Opcional: echo "Cadastro realizado com sucesso!"; // Para testar
            header("Location: ../index.php"); // Redireciona para a tela inicial
            exit();
        } else {
            echo "Erro ao cadastrar: " . $conn->error;
        }
    }
}

// --- Lógica de Login (signIn) ---
if (isset($_POST['signIn'])) {
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];

    // Login do Admin (Consulta direta)
    $sqlAdmin = "SELECT * FROM admin WHERE USUARIO = '$nome' AND SENHA = '$senha'";
    $resultAdmin = $conn->query($sqlAdmin);

    if ($resultAdmin->num_rows === 1) {
        $admin = $resultAdmin->fetch_assoc();
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_usuario'] = $admin['USUARIO'];
        header("Location: admin.php"); // Redireciona para o painel do admin (na mesma pasta pages/)
        exit();
    }

    // Login do Cliente (Consulta direta)
    $sqlCliente = "SELECT * FROM cliente WHERE NOME = '$nome' AND SENHA = '$senha'";
    $resultCliente = $conn->query($sqlCliente);

    if ($resultCliente->num_rows === 1) {
        $cliente = $resultCliente->fetch_assoc();
        $_SESSION['cliente_logado'] = true;
        $_SESSION['cliente_nome'] = $cliente['NOME'];
        $_SESSION['cliente_id'] = $cliente['COD_CLIENTE'];
        header("Location: servicos.php"); // Redireciona para a página de serviços (na mesma pasta pages/)
        exit();
    } else {
        echo "Usuário e/ou senha incorretos.";
    }
}

// Fechar a conexão com o banco de dados. É bom fazer isso no final do script.
$conn->close(); 
?>