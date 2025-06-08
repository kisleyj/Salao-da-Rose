<?php
// pages/process_agendamento.php

session_start(); // Fundamental para acessar as variáveis de sessão

// 1. Incluir o arquivo de conexão com o banco
include '../connect.php';

// 2. Verificar se o cliente está logado e obter o COD_CLIENTE
if (!isset($_SESSION['cod_cliente'])) {
    // Cliente não está logado, redirecionar para a página de login ou mostrar erro
    // Você pode criar uma variável de sessão para mensagem de erro e redirecionar
    $_SESSION['mensagem_erro'] = "Você precisa estar logado para fazer um agendamento.";
    header("Location: ../index.php"); // Ou para a sua página de login
    exit();
}
$cod_cliente = $_SESSION['cod_cliente'];

// Tenta obter o nome do cliente da sessão, ou busca no banco
$nome_cliente_agendamento = "";
if (isset($_SESSION['nome_cliente'])) {
    $nome_cliente_agendamento = $_SESSION['nome_cliente'];
} else {
    // Se o nome não estiver na sessão, buscar no banco (opcional, mas bom para a tabela agendamento)
    $stmt_nome = $conn->prepare("SELECT NOME FROM cliente WHERE COD_CLIENTE = ?");
    if ($stmt_nome) {
        $stmt_nome->bind_param("i", $cod_cliente);
        $stmt_nome->execute();
        $result_nome = $stmt_nome->get_result();
        if ($result_nome->num_rows > 0) {
            $cliente_data = $result_nome->fetch_assoc();
            $nome_cliente_agendamento = $cliente_data['NOME'];
        }
        $stmt_nome->close();
    }
}


// 3. Verificar se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 4. Receber e validar os dados do formulário
    $data_agendamento_str = $_POST['booking_date'] ?? '';     // Formato YYYY-MM-DD vindo do Flatpickr
    $hora_agendamento_str = $_POST['selected_time'] ?? '';    // Formato HH:MM
    $servicos_selecionados_json = $_POST['servicos_selecionados_json'] ?? ''; // String JSON

    // Validações básicas
    if (empty($data_agendamento_str) || empty($hora_agendamento_str) || empty($servicos_selecionados_json)) {
        // Idealmente, redirecionar de volta para agendar.php com uma mensagem de erro
        die("Erro: Todos os campos são obrigatórios (data, hora, serviços)."); // Simples por enquanto
    }

    // Validar formato da data (YYYY-MM-DD) e hora (HH:MM) - Expressões Regulares
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $data_agendamento_str)) {
        die("Erro: Formato de data inválido.");
    }
    if (!preg_match("/^\d{2}:\d{2}$/", $hora_agendamento_str)) {
        die("Erro: Formato de hora inválido.");
    }

    // 5. Preparar dados para inserção
    // A data já vem como YYYY-MM-DD, o que é ótimo para o MySQL tipo DATE
    // A hora já vem como HH:MM, ótimo para o MySQL tipo TIME

    $status_agendamento = 'Confirmado'; // Ou 'Pendente', dependendo da sua lógica de negócio

    // 6. Inserir no banco de dados
    // Usar os nomes exatos da sua tabela `agendamento` e suas colunas
    $sql = "INSERT INTO agendamento (COD_CLIENTE, NOME_CLIENTE, DATA_AGENDAMENTO, HORA_AGENDAMENTO, SERVICO_SELECIONADO, STATUS) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // Erro na preparação da query
        // Em produção, logar o erro: error_log("Erro ao preparar INSERT: " . $conn->error);
        die("Ocorreu um erro ao processar seu agendamento. Por favor, tente novamente mais tarde. [debug: prepare failed]");
    }

    // 'isssss' significa: integer, string, string, string, string, string
    $stmt->bind_param("isssss", 
        $cod_cliente, 
        $nome_cliente_agendamento, // Usando o nome obtido
        $data_agendamento_str, 
        $hora_agendamento_str, 
        $servicos_selecionados_json, 
        $status_agendamento
    );

    if ($stmt->execute()) {
        // Sucesso!
        // Você pode redirecionar para uma página de sucesso ou exibir uma mensagem
        $_SESSION['mensagem_sucesso'] = "Agendamento realizado com sucesso para o dia " . date("d/m/Y", strtotime($data_agendamento_str)) . " às " . $hora_agendamento_str . "!";
        // header("Location: meus_agendamentos.php"); // Exemplo de redirecionamento
        // Por enquanto, vamos apenas mostrar uma mensagem de sucesso:
        
        echo "<!DOCTYPE html><html lang='pt-br'><head><meta charset='UTF-8'><title>Sucesso</title>";
        echo "<link rel='stylesheet' href='../css/style.css'>"; // Para manter o estilo
        echo "</head><body><main class='main-content'><div class='container wrapper-custom' style='text-align:center;'>";
        echo "<h2>Agendamento Confirmado!</h2>";
        echo "<p>Seu agendamento para <strong>" . htmlspecialchars($servicos_selecionados_json) . "</strong> ";
        echo "no dia <strong>" . date("d/m/Y", strtotime($data_agendamento_str)) . "</strong> às <strong>" . $hora_agendamento_str . "</strong> ";
        echo "foi realizado com sucesso.</p>";
        echo "<br><a href='../pages/agendar.php' class='continue-button' style='text-decoration:none; display:inline-block; width:auto; padding: 10px 20px;'>Fazer Novo Agendamento</a>";
        echo "&nbsp;&nbsp;<a href='../index.php' class='back-to-login-link' style='display:inline-block; width:auto; padding: 10px 20px;'>Página Inicial</a>";
        echo "</div></main></body></html>";

    } else {
        // Erro na execução
        // Em produção, logar o erro: error_log("Erro ao executar INSERT: " . $stmt->error);
        die("Ocorreu um erro ao salvar seu agendamento. Por favor, tente novamente mais tarde. [debug: execute failed]");
    }

    $stmt->close();
    $conn->close();

} else {
    // Se não for POST, redirecionar ou mostrar erro
    // header("Location: agendar.php");
    echo "Método de requisição inválido.";
    exit();
}
?>