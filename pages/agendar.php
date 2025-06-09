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
        <header class="page-header">
            <div class="header-left">
                <a href="servicos.php" class="header-link">
                    <ion-icon name="arrow-back-outline"></ion-icon> Voltar
                </a>
            </div>
            <div class="header-right"></div>
        </header>
        
        <section class="booking-section">
            <h2>Agende seu Horário</h2>
            <p class="subtitle">Escolha a data e hora para seu atendimento.</p>
            
            <form id="bookingForm" action="resumo_pagamento.php" method="POST" class="booking-form">
                <input type="hidden" name="selectedServices" id="selectedServicesHidden">
                <input type="hidden" name="selected_time" id="selectedTimeInput"> 

                <div class="input-group-agendamento">
                    <h3><ion-icon name="calendar-outline"></ion-icon> Selecione a Data</h3>
                    <input type="text" id="booking_date" name="booking_date" class="form-control" placeholder="Clique para escolher a data" required>
                </div>

                <div class="time-slot-selection">
                    <h3><ion-icon name="time-outline"></ion-icon> Selecione o Horário</h3>
                    <div class="time-slots-grid"></div>
                </div>
                
                <button type="submit" class="continue-button">Confirmar Agendamento</button>
            </form>
        </section>
    </div>
</main>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script> 
<script src="../js/script.js"></script>

</body>
</html>