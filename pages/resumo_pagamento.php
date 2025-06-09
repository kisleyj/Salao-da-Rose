<?php
session_start();

if (!isset($_SESSION['nome'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST" || empty($_POST['selectedServices']) || empty($_POST['booking_date']) || empty($_POST['selected_time'])) {
    header("Location: agendar.php?erro=Faltam dados");
    exit();
}

$servicos_json = $_POST['selectedServices'];
$servicos_selecionados = json_decode($servicos_json, true);
$data_agendamento = $_POST['booking_date'];
$hora_agendamento = $_POST['selected_time'];

$valor_total = 0;
$nomes_servicos = [];
if (is_array($servicos_selecionados)) {
    foreach ($servicos_selecionados as $servico) {
        $valor_total += $servico['valor'];
        $nomes_servicos[] = $servico['nome'];
    }
}
$descricao_servicos = implode(', ', $nomes_servicos);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salão Rose - Forma de Pagamento</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main class="main-content">
    <div class="container wrapper-custom">
        <header class="page-header">
            <div class="header-left">
                <a href="agendar.php" class="header-link">
                    <ion-icon name="arrow-back-outline"></ion-icon> Voltar
                </a>
            </div>
            <div class="header-right"></div>
        </header>
        
        <section class="booking-section">
            <h2>Forma de Pagamento</h2>
            <p class="subtitle">Como você gostaria de pagar?</p>

            <form action="confirmar_agendamento.php" method="POST" class="booking-form">
                <input type="hidden" name="servicos_json" value="<?php echo htmlspecialchars($servicos_json, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="data_agendamento" value="<?php echo $data_agendamento; ?>">
                <input type="hidden" name="hora_agendamento" value="<?php echo $hora_agendamento; ?>">
                <input type="hidden" name="valor_total" value="<?php echo $valor_total; ?>">

                <div class="summary-card">
                    <div class="service-name"><?php echo htmlspecialchars($descricao_servicos); ?> - R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></div>
                    <div class="service-time"><?php echo date("d/m/Y", strtotime($data_agendamento)); ?> às <?php echo $hora_agendamento; ?></div>
                </div>

                <div class="payment-options">
                    <h4 class="payment-title">Escolha a forma de pagamento:</h4>
                    
                    <input type="radio" name="modo_de_pagamento" value="Cartão" id="cartao" required>
                    <label for="cartao">
                        <ion-icon name="card-outline" class="payment-icon"></ion-icon>
                        <span class="payment-text">Cartão</span>
                    </label>
                    
                    <input type="radio" name="modo_de_pagamento" value="Pix" id="pix">
                    <label for="pix">
                        <ion-icon name="phone-portrait-outline" class="payment-icon"></ion-icon>
                        <span class="payment-text">Pix</span>
                    </label>
                    
                    <input type="radio" name="modo_de_pagamento" value="Dinheiro" id="dinheiro">
                    <label for="dinheiro">
                         <ion-icon name="cash-outline" class="payment-icon"></ion-icon>
                        <span class="payment-text">Dinheiro</span>
                    </label>
                </div>
                
                <button type="submit" class="continue-button">Continuar</button>
            </form>
        </section>
    </div>
</main>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>