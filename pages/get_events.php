<?php
// pages/get_events.php
session_start(); // Inicia a sessão se necessário, embora não seja usada diretamente aqui
include("../connect.php"); // Inclui o arquivo de conexão com o banco de dados

header('Content-Type: application/json'); // Informa ao navegador que a resposta é JSON

$events = []; // Array para armazenar os eventos formatados

// Consulta para buscar todos os agendamentos
$sql = "SELECT COD_AGENDAMENTO, DATA_HORA, VALOR_TOTAL FROM Agendamento ORDER BY DATA_HORA";
$result = $conn->query($sql);

if ($result) {
    while($row = $result->fetch_assoc()) {
        $events[] = [
            'id' => $row['COD_AGENDAMENTO'],
            'title' => 'Agendamento - R$ ' . number_format($row['VALOR_TOTAL'], 2, ',', '.'),
            'start' => $row['DATA_HORA'] // DATA_HORA já deve estar no formato DATETIME
        ];
    }
} else {
    // Em caso de erro na consulta, você pode logar o erro ou retornar um JSON de erro para depuração
    error_log("Erro ao buscar eventos para o calendário: " . $conn->error);
    // Opcional: echo json_encode(['error' => 'Erro ao carregar agendamentos.']);
}

echo json_encode($events); // Retorna o array de eventos como JSON
$conn->close(); // Fecha a conexão com o banco de dados
?>