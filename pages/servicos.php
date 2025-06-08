<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salão Rose - Serviços</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<main class="main-content">
    <div class="container wrapper-custom">
        <nav class="inner-top-link-nav">
            <a href="../index.php" class="back-to-login-link">Voltar</a>
        </nav>
        
        <section class="services-selection-section">
            <h2>Escolha seus Serviços</h2>
            <p class="subtitle">Escolha o serviço perfeito para você</p>
            
            <form id="servicesForm" action="agendar.php" method="POST" class="services-form">
                <input type="hidden" name="selectedServices" id="selectedServices">

                <div class="service-card" data-service-id="corte-feminino" data-price="80">
                    <div class="service-icon">
                        <ion-icon name="cut"></ion-icon>
                    </div>
                    <div class="service-details">
                        <h3>Corte</h3> <p>Valor: R$ 80</p>
                    </div>
                    <button type="button" class="select-button" data-service-value="Corte Feminino">Selecionar</button>
                    <input type="checkbox" id="corte-feminino-checkbox" name="servicos[]" value="Corte Feminino" style="display: none;">
                </div>

                <div class="service-card" data-service-id="coloracao" data-price="120">
                    <div class="service-icon">
                        <ion-icon name="color-palette"></ion-icon>
                    </div>
                    <div class="service-details">
                        <h3>Coloração</h3>
                        <p>Valor: R$ 120</p>
                    </div>
                    <button type="button" class="select-button" data-service-value="Coloracao">Selecionar</button>
                    <input type="checkbox" id="coloracao-checkbox" name="servicos[]" value="Coloracao" style="display: none;">
                </div>

                <div class="service-card" data-service-id="progressiva" data-price="200">
                    <div class="service-icon">
                        <ion-icon name="sparkles"></ion-icon>
                    </div>
                    <div class="service-details">
                        <h3>Progressiva</h3>
                        <p>Valor: R$ 200</p>
                    </div>
                    <button type="button" class="select-button" data-service-value="Progressiva">Selecionar</button>
                    <input type="checkbox" id="progressiva-checkbox" name="servicos[]" value="Progressiva" style="display: none;">
                </div>

                <button type="submit" class="continue-button">Continuar</button>
            </form>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="../js/script.js"></script>

</body>
</html>