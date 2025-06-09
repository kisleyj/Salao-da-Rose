<?php
include '../connect.php'; 

header('Content-Type: application/json'); 

$selectedDate = $_GET['date'] ?? ''; 

if (empty($selectedDate) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $selectedDate)) {
    echo json_encode(['error' => 'Data não fornecida ou em formato inválido. Use YYYY-MM-DD.']);
    exit();
}

$dbDate = $selectedDate; 

$occupiedTimes = [];

if (!$conn || $conn->connect_error) {
    echo json_encode(['error' => 'Erro de conexão com o banco de dados. Verifique connect.php.']);
    exit();
}

$sql = "SELECT TIME_FORMAT(HORA_AGENDAMENTO, '%H:%i') as hora FROM agendamento WHERE DATA_AGENDAMENTO = ? AND (STATUS = 'confirmado' OR STATUS = 'pendente')";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
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
$conn->close(); 

$availableTimesOutput = []; 

$start_morning = strtotime('07:30');
$end_morning = strtotime('11:00');
for ($i = $start_morning; $i <= $end_morning; $i = $i + (30 * 60)) { 
    $timeSlot = date('H:i', $i);
    $availableTimesOutput[] = [
        'time' => $timeSlot,
        'available' => !in_array($timeSlot, $occupiedTimes) 
    ];
}

$start_afternoon = strtotime('13:00');
$end_afternoon = strtotime('17:30');
for ($i = $start_afternoon; $i <= $end_afternoon; $i = $i + (30 * 60)) { 
    $timeSlot = date('H:i', $i);
    $availableTimesOutput[] = [
        'time' => $timeSlot,
        'available' => !in_array($timeSlot, $occupiedTimes)
    ];
}

echo json_encode(['available_times' => $availableTimesOutput]);
?>