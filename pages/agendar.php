<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salão Rose - Agendar</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

<main class="main-content">
    <div class="container wrapper-custom">
        <nav class="inner-top-link-nav">
            <a href="servicos.php" class="back-to-login-link">Voltar</a>
        </nav>
        
        <section class="booking-section">
            <h2>Agende seu Horário</h2>
            <p class="subtitle">Escolha a data e hora para seu atendimento.</p>

            <?php
            // Captura os serviços selecionados (JSON string) da página anterior (servicos.php)
            $servicos_selecionados_json = ""; // Inicializa a variável
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectedServices'])) {
                // selectedServices é a string JSON enviada pelo formulário de servicos.php
                // Apenas repassamos, a decodificação pode ser feita em process_agendamento.php
                $servicos_selecionados_json = htmlspecialchars($_POST['selectedServices'], ENT_QUOTES, 'UTF-8');
            }
            ?>
            
            <form id="bookingForm" action="process_agendamento.php" method="POST" class="booking-form">
                <input type="hidden" name="servicos_selecionados_json" value="<?php echo $servicos_selecionados_json; ?>">
                
                <input type="hidden" name="selected_time" id="selectedTimeInput"> 

                <div class="input-group-agendamento">
                    <label for="booking_date">Data:</label>
                    <input type="text" id="booking_date" name="booking_date" class="form-control" placeholder="Selecione a Data" required>
                </div>

                <div class="time-slot-selection">
                    <h3><ion-icon name="time-outline"></ion-icon> Selecione o Horário</h3>
                    <div class="time-slots-grid">
                        </div>
                </div>
                
                <button type="submit" class="continue-button">Confirmar Agendamento</button>
            </form>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script> <script src="../js/script.js"></script>

</body>
</html>