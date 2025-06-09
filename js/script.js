document.addEventListener('DOMContentLoaded', () => {
    // --- Lógica para alternar formulários de Login/Cadastro (para index.php) ---
    const wrapper = document.querySelector('.wrapper');
    const signUpBtnLink = document.querySelector('.signUpBtn-link');
    const signInBtnLink = document.querySelector('.signInBtn-link'); 

    if (wrapper && signUpBtnLink && signInBtnLink) {
        signUpBtnLink.addEventListener('click', (e) => {
            e.preventDefault(); 
            wrapper.classList.add('active');
        });
        signInBtnLink.addEventListener('click', (e) => {
            e.preventDefault(); 
            wrapper.classList.remove('active');
        });
    }

    // --- Lógica para Agendamento (para agendar.php) ---
    const bookingDateInput = document.getElementById('booking_date');
    const timeSlotsGrid = document.querySelector('.time-slots-grid');
    const selectedTimeInput = document.getElementById('selectedTimeInput'); 
    const bookingForm = document.getElementById('bookingForm');
    
    // ==========================================================
    // ===== TRECHO NOVO PARA CORRIGIR A PASSAGEM DE DADOS =====
    // ==========================================================
    // Esta função pega os dados de serviços que foram enviados pela página anterior
    // e os armazena em um campo escondido do formulário de agendamento.
    function transferServicesData() {
        // Tenta pegar os dados de serviços do 'localStorage' (uma "memória" do navegador)
        const servicesData = localStorage.getItem('selectedServices');
        const hiddenInput = document.getElementById('selectedServicesHidden');

        if (servicesData && hiddenInput) {
            // Se encontrou dados, coloca no input escondido
            hiddenInput.value = servicesData;
        }
    }
    // ==========================================================


    if (bookingDateInput) { 
        // Chama a nova função assim que a página de agendamento carregar
        transferServicesData();

        function loadAvailableTimes(dateStr) {
            if (!timeSlotsGrid) return;

            timeSlotsGrid.innerHTML = '<div class="loading-message" style="text-align: center; color: #857074; width: 100%;">Carregando horários...</div>';
            fetch(`get_available_times.php?date=${dateStr}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    timeSlotsGrid.innerHTML = ''; 

                    if (data.error) {
                        timeSlotsGrid.innerHTML = `<div class="error-message" style="text-align: center; color: #bb335c; width:100%;">${data.error}</div>`;
                        return;
                    }
                    if (!data.available_times || data.available_times.length === 0) {
                        timeSlotsGrid.innerHTML = '<div class="no-times-message" style="text-align: center; color: #857074; width:100%;">Nenhum horário disponível para esta data.</div>';
                        if (selectedTimeInput) selectedTimeInput.value = ''; 
                        return;
                    }

                    data.available_times.forEach(slot => { 
                        const button = document.createElement('button');
                        button.type = 'button'; 
                        button.className = 'time-slot-button';
                        button.dataset.time = slot.time; 
                        button.textContent = slot.time;

                        if (!slot.available) {
                            button.classList.add('disabled');
                            button.disabled = true;
                        }

                        button.addEventListener('click', () => {
                            if (!button.disabled) { 
                                const allTimeSlotButtons = timeSlotsGrid.querySelectorAll('.time-slot-button');
                                allTimeSlotButtons.forEach(btn => btn.classList.remove('selected'));
                                button.classList.add('selected');
                                if (selectedTimeInput) {
                                    selectedTimeInput.value = button.dataset.time;
                                }
                            }
                        });
                        timeSlotsGrid.appendChild(button);
                    });
                })
                .catch(error => {
                    console.error('Agendamento: Erro ao buscar horários:', error);
                    if (timeSlotsGrid) { 
                        timeSlotsGrid.innerHTML = '<div class="error-message" style="text-align: center; color: #bb335c; width:100%;">Não foi possível carregar os horários. Tente novamente.</div>';
                    }
                    if (selectedTimeInput) selectedTimeInput.value = ''; 
                });
        }

        flatpickr(bookingDateInput, {
            dateFormat: "Y-m-d", 
            altInput: true,      
            altFormat: "d/m/Y",  
            minDate: "today",
            locale: "pt", 
            disable: [
                function(date) {
                    return (date.getDay() === 0);
                }
            ],
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    loadAvailableTimes(dateStr);
                } else {
                    if (timeSlotsGrid) {
                        timeSlotsGrid.innerHTML = '<div style="text-align: center; color: #857074; width:100%;">Selecione uma data para ver os horários.</div>';
                    }
                    if (selectedTimeInput) {
                        selectedTimeInput.value = ''; 
                    }
                }
            }
        });
        
        if (bookingForm) {
            bookingForm.addEventListener('submit', (event) => {
                if (!selectedTimeInput.value) {
                    event.preventDefault(); 
                    alert('Por favor, selecione um horário para continuar.');
                }
            });
        }
        
        const initialDate = bookingDateInput.value;
        if (initialDate) {
            loadAvailableTimes(initialDate);
        } else if (timeSlotsGrid) {
            timeSlotsGrid.innerHTML = '<div style="text-align: center; color: #857074; width:100%;">Selecione uma data para ver os horários.</div>';
        }
    }

    // --- Lógica para Seleção de Serviços (para servicos.php) ---
    const servicesForm = document.getElementById('servicesForm');
    const selectedServicesInput = document.getElementById('selectedServices'); 

    if (servicesForm && selectedServicesInput) { 
        const serviceCards = servicesForm.querySelectorAll('.service-card'); 

        function updateSelectedServicesInput() {
            const selectedServices = [];
            const checkedCheckboxes = servicesForm.querySelectorAll('input[type="checkbox"][name="servicos[]"]:checked');
            
            checkedCheckboxes.forEach(checkbox => {
                const card = checkbox.closest('.service-card');
                selectedServices.push({
                    id: card.dataset.serviceId,
                    nome: checkbox.value,
                    valor: parseFloat(card.dataset.price)
                });
            });
            
            const servicesJson = JSON.stringify(selectedServices);
            selectedServicesInput.value = servicesJson;
            
            // ==========================================================
            // ===== TRECHO NOVO PARA CORRIGIR A PASSAGEM DE DADOS =====
            // ==========================================================
            // Salva os dados dos serviços na "memória" do navegador (localStorage)
            localStorage.setItem('selectedServices', servicesJson);
            // ==========================================================
        }

        serviceCards.forEach(card => {
            const selectButton = card.querySelector('.select-button');
            const hiddenCheckbox = card.querySelector('input[type="checkbox"][name="servicos[]"]');

            if (selectButton && hiddenCheckbox) {
                if (hiddenCheckbox.checked) {
                    card.classList.add('selected');
                    selectButton.textContent = 'Selecionado';
                    selectButton.classList.add('selected');
                }

                selectButton.addEventListener('click', () => {
                    hiddenCheckbox.checked = !hiddenCheckbox.checked;
                    card.classList.toggle('selected');
                    
                    if (hiddenCheckbox.checked) {
                        selectButton.textContent = 'Selecionado';
                        selectButton.classList.add('selected');
                    } else {
                        selectButton.textContent = 'Selecionar';
                        selectButton.classList.remove('selected');
                    }
                    updateSelectedServicesInput(); 
                });
            }
        });
        
        updateSelectedServicesInput(); 

        servicesForm.addEventListener('submit', (event) => {
            const checkedCheckboxes = servicesForm.querySelectorAll('input[type="checkbox"][name="servicos[]"]:checked');
            if (checkedCheckboxes.length === 0) {
                alert('Por favor, selecione pelo menos um serviço para continuar.');
                event.preventDefault();
            }
        });
    }
});