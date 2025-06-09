<?php
session_start();

if (!isset($_SESSION['cod_cliente'])) {
    header("Location: ../index.php");
    exit();
}

include '../connect.php';

$cod_cliente = $_SESSION['cod_cliente'];
$agendamentos = [];

$sql = "SELECT * FROM (
            (SELECT *, 1 AS sort_group FROM agendamento WHERE COD_CLIENTE = ? AND DATA_AGENDAMENTO >= CURDATE())
            UNION ALL
            (SELECT *, 2 AS sort_group FROM agendamento WHERE COD_CLIENTE = ? AND DATA_AGENDAMENTO < CURDATE())
        ) AS combined_appointments
        ORDER BY
            sort_group ASC,
            CASE WHEN sort_group = 1 THEN DATA_AGENDAMENTO END ASC,
            CASE WHEN sort_group = 1 THEN HORA_AGENDAMENTO END ASC,
            CASE WHEN sort_group = 2 THEN DATA_AGENDAMENTO END DESC,
            CASE WHEN sort_group = 2 THEN HORA_AGENDAMENTO END DESC";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ii", $cod_cliente, $cod_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $agendamentos[] = $row;
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salão Rose - Meus Agendamentos</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main class="main-content">
    <div class="container wrapper-custom">
        <header class="page-header">
            <div class="header-left">
                <a href="servicos.php" class="header-link">
                    <ion-icon name="add-outline"></ion-icon> Novo Agendamento
                </a>
            </div>
            <div class="header-right">
                <a href="logout.php" class="header-link">
                    <ion-icon name="log-out-outline"></ion-icon> Sair
                </a>
            </div>
        </header>
        
        <section class="booking-section">
            <h2>Meus Agendamentos</h2>
            <p class="subtitle">Aqui está o seu histórico de agendamentos.</p>

            <div class="appointments-list-v2">
                <?php if (empty($agendamentos)): ?>
                    <div class="no-appointments-message">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        <p>Você ainda não possui agendamentos.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($agendamentos as $agendamento): ?>
                        <?php
                            $servicos_array = json_decode($agendamento['SERVICOS_SELECIONADOS'], true);
                            $nomes_servicos = [];
                            if (is_array($servicos_array)) {
                                foreach ($servicos_array as $servico) {
                                    $nomes_servicos[] = $servico['nome'];
                                }
                            }
                            $descricao_servicos = implode(', ', $nomes_servicos);
                        ?>
                        <div class="appointment-card-v2">
                            <div class="card-details-v2">
                                <span class="service-name"><?php echo htmlspecialchars($descricao_servicos); ?></span>
                                <span class="service-date"><?php echo date("d/m/Y", strtotime($agendamento['DATA_AGENDAMENTO'])); ?> às <?php echo date("H:i", strtotime($agendamento['HORA_AGENDAMENTO'])); ?></span>
                            </div>
                            <div class="card-pricing">
                                <span class="price">R$ <?php echo number_format($agendamento['VALOR_TOTAL'], 2, ',', '.'); ?></span>
                                <span class="payment-method"><?php echo htmlspecialchars($agendamento['MODO_DE_PAGAMENTO']); ?></span>
                            </div>
                            <div class="card-status">
                                <span class="status-badge"><?php echo htmlspecialchars($agendamento['STATUS']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>