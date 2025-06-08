<?php
// Inicia a sessão apenas se ainda não tiver sido iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Opcional: Pega o nome do usuário (caso queira usar no menu)
$usuario = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Visitante';
?>

<style>
    .header {
        background-color: #bb335c;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #fff;
        font-family: 'Poppins', sans-serif;
    }

    .header h1 {
        font-size: 22px;
        margin: 0;
    }

    .nav-links {
        display: flex;
        gap: 20px;
    }

    .nav-links a {
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }

    .nav-links a:hover {
        color: #ffe6f0;
    }

    .user-name {
        font-size: 14px;
        color: #ffeef2;
        margin-right: 15px;
    }

    .header-right {
        display: flex;
        align-items: center;
    }
</style>

<div class="header">
    <h1>Salão Rose</h1>
    <div class="header-right">
        <span class="user-name">Olá, <?php echo htmlspecialchars($usuario); ?></span>
        <div class="nav-links">
            <a href="../homepage.php">Home</a>
            <a href="../pages/agendar.php">Agendar</a>
            <a href="../pages/meus_agendamentos.php">Meus Agendamentos</a>
            <a href="../logout.php">Sair</a>
        </div>
    </div>
</div>