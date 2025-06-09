<?php
session_start();

if (!isset($_SESSION['cod_cliente'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salão Rose - Agendamento Confirmado</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main class="main-content">
    <div class="container wrapper-custom">
        <section class="confirmation-section">
            <ion-icon name="checkmark-circle-outline" class="icon-success"></ion-icon>
            <h2>Agendamento Confirmado!</h2>
            <p class="subtitle">Seu horário foi reservado com sucesso. Obrigada pela preferência!</p>

            <div class="confirmation-buttons">
                <a href="meus_agendamentos.php" class="btn">Ver Meus Agendamentos</a>
                <a href="servicos.php" class="btn btn-secondary">Fazer Novo Agendamento</a>
            </div>
        </section>
    </div>
</main>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>