<?php
session_start();
include '../connect.php'; 

if (!isset($_SESSION['cod_cliente'])) {
    $_SESSION['mensagem_erro'] = "Sessão expirada. Faça o login novamente.";
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: servicos.php");
    exit();
}

$cod_cliente = $_SESSION['cod_cliente'];
$servicos_json = $_POST['servicos_json'] ?? '';
$data_agendamento = $_POST['data_agendamento'] ?? '';
$hora_agendamento = $_POST['hora_agendamento'] ?? '';
$valor_total = $_POST['valor_total'] ?? 0;
$modo_de_pagamento = $_POST['modo_de_pagamento'] ?? '';

if (empty($servicos_json) || empty($data_agendamento) || empty($hora_agendamento) || empty($valor_total) || empty($modo_de_pagamento)) {
    die("Erro: Dados incompletos. Por favor, volte e tente novamente.");
}

$sql = "INSERT INTO agendamento (COD_CLIENTE, SERVICOS_SELECIONADOS, DATA_AGENDAMENTO, HORA_AGENDAMENTO, VALOR_TOTAL, MODO_DE_PAGAMENTO, STATUS) 
        VALUES (?, ?, ?, ?, ?, ?, 'Confirmado')";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Ocorreu um erro interno. Por favor, tente novamente mais tarde. [debug: prepare failed]");
}

$stmt->bind_param("isssds", 
    $cod_cliente,
    $servicos_json,
    $data_agendamento,
    $hora_agendamento,
    $valor_total,
    $modo_de_pagamento
);

if ($stmt->execute()) {
    header("Location: agendamento_confirmado.php");
    exit();
} else {
    die("Não foi possível salvar seu agendamento. Por favor, tente novamente. [debug: execute failed]");
}

$stmt->close();
$conn->close();

?>