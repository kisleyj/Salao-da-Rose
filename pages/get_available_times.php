<?php
// pages/get_available_times.php

// Inclui o arquivo de conexão. Certifique-se que o caminho está correto.
include '../connect.php'; 

header('Content-Type: application/json'); // Define o tipo de conteúdo da resposta como JSON

// A data é enviada pelo script.js no formato YYYY-MM-DD
$selectedDate = $_GET['date'] ?? ''; 

// Validação básica do formato da data
if (empty($selectedDate) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $selectedDate)) {
    echo json_encode(['error' => 'Data não fornecida ou em formato inválido. Use YYYY-MM-DD.']);
    exit();
}

// A data já está no formato YYYY-MM-DD, pronta para o banco
$dbDate = $selectedDate; 

$occupiedTimes = [];

// Verifica se a conexão com o banco foi bem-sucedida
if (!$conn || $conn->connect_error) {
    echo json_encode(['error' => 'Erro de conexão com o banco de dados. Verifique connect.php.']);
    exit();
}

// ATENÇÃO: Nome da tabela e colunas ajustados para MAIÚSCULAS e singular 'agendamento'
$sql = "SELECT TIME_FORMAT(HORA_AGENDAMENTO, '%H:%i') as hora FROM agendamento WHERE DATA_AGENDAMENTO = ? AND (STATUS = 'confirmado' OR STATUS = 'pendente')";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Para depuração, você pode usar error_log($conn->error) para ver o erro real no log do PHP
    echo json_encode(['error' => 'Erro ao preparar a consulta para buscar horários.']);
    $conn->close();
    exit();
}

$stmt->bind_param("s", $dbDate);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $occupiedTimes[] = $row['hora'];
}
$stmt->close();
$conn->close(); // Fechar a conexão aqui é seguro, pois este script só faz uma consulta.

$availableTimesOutput = []; 
$start = strtotime('08:00'); // Horário de início do salão
$end = strtotime('17:30');   // Último horário possível para iniciar um agendamento de 30 min (terminaria 18:00)

for ($i = $start; $i <= $end; $i = $i + (30 * 60)) { // Intervalo entre horários (30 minutos)
    $timeSlot = date('H:i', $i);
    $availableTimesOutput[] = [
        'time' => $timeSlot,
        'available' => !in_array($timeSlot, $occupiedTimes) // true se NÃO estiver em $occupiedTimes
    ];
}

// A saída JSON deve ser um objeto com a chave "available_times" contendo o array de horários
echo json_encode(['available_times' => $availableTimesOutput]);
?>