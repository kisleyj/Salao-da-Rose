<?php
// pages/consultar_agendamentos.php
include("../connect.php");

if (!isset($_GET['data'])) {
    echo "<p>Data não fornecida.</p>";
    exit();
}

$dataSelecionada = $_GET['data']; 
$diaSemana = date('w', strtotime($dataSelecionada));

/* ---------- 1. Monta a grade de horários (AGORA COM INTERVALO DE 30 MINUTOS) ---------- */
$horarios = [];
$inicioManha = strtotime('08:00:00');
$fimManha = strtotime('11:00:00'); // Último horário para iniciar agendamento da manhã
$inicioTarde = strtotime('13:00:00');
$fimTarde = strtotime('17:30:00'); // Último horário para iniciar agendamento da tarde

// Ajuste para Segunda-feira (13:00 às 18:00)
if ($diaSemana == 1) { 
    for ($i = $inicioTarde; $i <= $fimTarde; $i += (30 * 60)) { // Incrementa de 30 em 30 minutos
        $horarios[] = date('H:i:s', $i);
    }
} 
// Ajuste para Terça a Sábado (8:00-11:00 e 13:00-18:00)
elseif ($diaSemana >= 2 && $diaSemana <= 6) { 
    for ($i = $inicioManha; $i <= $fimManha; $i += (30 * 60)) {
        $horarios[] = date('H:i:s', $i);
    }
    for ($i = $inicioTarde; $i <= $fimTarde; $i += (30 * 60)) {
        $horarios[] = date('H:i:s', $i);
    }
}
// Se for Domingo (0), não adiciona horários, e o script já sai antes.

/* ---------- 2. Busca agendamentos confirmados (mantido igual) ---------- */
$sql = "
    SELECT  A.COD_AGENDAMENTO,
            TIME(A.DATA_HORA)             AS HORA,
            C.NOME                        AS CLIENTE,
            S.NOME                        AS SERVICO,
            A.VALOR_TOTAL,
            A.MODO_DE_PAGAMENTO           AS FORMA_PAGAMENTO
    FROM    Agendamento A
    JOIN    Cliente     C ON C.COD_CLIENTE = A.COD_CLIENTE
    JOIN    Servico     S ON S.COD_SERVICO = A.COD_SERVICO
    WHERE   DATE(A.DATA_HORA) = ?
    ORDER BY HORA
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dataSelecionada);
$stmt->execute();
$result = $stmt->get_result();

/* ---------- 3. Indexa por horário para facilitar a exibição ---------- */
$agendamentos = [];
while ($row = $result->fetch_assoc()) {
    $agendamentos[$row['HORA']] = $row;
}

/* ---------- 4. Exibe a agenda do dia (mantido igual) ---------- */
echo "<h2>Agendamentos – " . date("d/m/Y", strtotime($dataSelecionada)) . "</h2>";

echo "<ul style='list-style:none;padding-left:0'>";
foreach ($horarios as $hora) {
    echo "<li style='margin-bottom:8px'>";
    echo "<strong>" . substr($hora, 0, 5) . "</strong>: "; // Exibe HH:MM

    if (isset($agendamentos[$hora])) {
        $a = $agendamentos[$hora];
        echo htmlspecialchars($a['CLIENTE']) . " – "
           . htmlspecialchars($a['SERVICO']) . " – "
           . "R$ " . number_format($a['VALOR_TOTAL'], 2, ',', '.') . " – "
           . htmlspecialchars($a['FORMA_PAGAMENTO']);
    } else {
        echo "<em>Horário livre</em>";
    }
    echo "</li>";
}
echo "</ul>";

$stmt->close();
$conn->close();
?>