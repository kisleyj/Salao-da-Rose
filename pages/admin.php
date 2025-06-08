<?php
// pages/admin.php

session_start();

if (!isset($_SESSION['admin_logado'])) { 
    header('Location: ../index.php'); 
    exit(); 
}

$adminUsuario = isset($_SESSION['admin_usuario']) ? htmlspecialchars($_SESSION['admin_usuario']) : 'Administrador';

// Inclui a conexão com o banco de dados
include("../connect.php"); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Salão Rose</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link rel="stylesheet" href="http://localhost/SalaoRose/css/admin.css"> 
    
</head>
<body>
    <h1>Bem-vinda, <?php echo $adminUsuario; ?>!</h1>

    <div class="admin-controls">
        <button id="showCalendarBtn" class="control-button active">Ver Calendário</button>
        <button id="showClientsBtn" class="control-button">Ver Clientes</button>
    </div>

    <div class="admin-content">
        <div id="calendar-section" class="admin-container visible">
            <div id='calendar'></div>
            <div class="agenda-do_dia" id="agendaDia">
                <h2>Agendamentos do Dia</h2>
                <p>Selecione um dia no calendário para ver os agendamentos.</p>
            </div>
        </div>

        <div id="clients-section" class="admin-container clientes-lista-container hidden">
            <h2>Lista de Clientes</h2>
            <table class="clientes-tabela">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query para buscar todos os clientes
                    // CUIDADO AQUI: O nome da tabela é 'Cliente' (com C maiúsculo) no seu DDL
                    $sqlClientes = "SELECT NOME, CPF, TELEFONE FROM Cliente ORDER BY NOME ASC";
                    $resultClientes = $conn->query($sqlClientes);

                    // CORREÇÃO DO WARNING: Verifica se a consulta foi bem-sucedida
                    if ($resultClientes === false) {
                        echo "<tr><td colspan='3'>Erro ao carregar clientes: " . $conn->error . "</td></tr>";
                    } elseif ($resultClientes->num_rows > 0) {
                        while($cliente = $resultClientes->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($cliente['NOME']) . "</td>";
                            echo "<td>" . htmlspecialchars($cliente['CPF']) . "</td>";
                            echo "<td>" . htmlspecialchars($cliente['TELEFONE']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Nenhum cliente cadastrado ainda.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div> <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js'></script> 

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Lógica do Calendário
            const calendarEl = document.getElementById('calendar');
            const agendaDia = document.getElementById('agendaDia');
            const calendarSection = document.getElementById('calendar-section');
            const clientsSection = document.getElementById('clients-section');
            const showCalendarBtn = document.getElementById('showCalendarBtn');
            const showClientsBtn = document.getElementById('showClientsBtn');

            if (typeof FullCalendar === 'undefined') {
                console.error('ERRO CRÍTICO: FullCalendar não está definido. Verifique os links da CDN.');
                return; 
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth', 
                locale: 'pt-br',             
                events: 'get_events.php', 
                
                dateClick: function(info){
                    const dataSelecionada = info.dateStr;
                    fetch(`consultar_agendamentos.php?data=${dataSelecionada}`)
                    .then(response => response.text())
                    .then(html => {
                        agendaDia.innerHTML = html;
                        calendar.updateSize(); 
                    })
                    .catch(error => {
                        console.error('Erro ao buscar agendamentos:', error);
                        agendaDia.innerHTML = '<p>Erro ao buscar agendamentos.</p>';
                        calendar.updateSize(); 
                    });
                }
            });
            calendar.render(); 

            // Lógica para alternar entre Calendário e Clientes
            showCalendarBtn.addEventListener('click', () => {
                calendarSection.classList.add('visible');
                calendarSection.classList.remove('hidden');
                clientsSection.classList.add('hidden');
                clientsSection.classList.remove('visible');
                showCalendarBtn.classList.add('active');
                showClientsBtn.classList.remove('active');
                calendar.updateSize(); // Força o calendário a se redimensionar ao ser exibido
            });

            showClientsBtn.addEventListener('click', () => {
                clientsSection.classList.add('visible');
                clientsSection.classList.remove('hidden');
                calendarSection.classList.add('hidden');
                calendarSection.classList.remove('visible');
                showClientsBtn.classList.add('active');
                showCalendarBtn.classList.remove('active');
            });
        });
    </script>
</body>
</html>