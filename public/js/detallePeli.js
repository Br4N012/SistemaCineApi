let selectedFunctionId = null;
let selectedSeats = [];
let availableSeatsMap = new Map();
let selectedPaymentMethod = null;
let currentRoomName = '';
let currentShowtimeText = '';

const showtimeSelectionDiv = document.getElementById('showtime-selection');
const seatMapContainer = document.getElementById('seat-map-container');
const seatMapDiv = document.getElementById('seat-map');
const selectedSeatsListDiv = document.getElementById('selected-seats-list');
const totalPriceSpan = document.getElementById('total-price');
const paymentButtons = document.querySelectorAll('.payment-btn');
const buyTicketsBtn = document.getElementById('buy-tickets-btn');
const bookingFlowContainer = document.getElementById('booking-flow');
const receiptContainer = document.getElementById('receipt-container');

const backButtonBooking = document.getElementById('back-button-booking');
const backButtonReceipt = document.getElementById('back-button-receipt');

// Referencias a los elementos del modal de alerta
const customAlertModal = document.getElementById('custom-alert-modal');
const modalTitle = document.getElementById('modal-title');
const modalMessage = document.getElementById('modal-message');
const modalCloseBtn = document.getElementById('modal-close-btn');

// Referencias a los elementos del modal de confirmación
const customConfirmModal = document.getElementById('custom-confirm-modal');
const confirmModalTitle = document.getElementById('confirm-modal-title');
const confirmModalMessage = document.getElementById('confirm-modal-message');
const confirmCancelBtn = document.getElementById('confirm-cancel-btn');
const confirmOkBtn = document.getElementById('confirm-ok-btn');

// Función para mostrar el modal de alerta personalizado
function showAlert(title, message) {
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    customAlertModal.classList.add('show');
}

// Función para cerrar el modal de alerta
function closeAlert() {
    customAlertModal.classList.remove('show');
}

// Función para mostrar el modal de confirmación personalizado
function showConfirm(title, message) {
    return new Promise((resolve) => {
        confirmModalTitle.textContent = title;
        confirmModalMessage.textContent = message;
        customConfirmModal.classList.add('show');

        const handleConfirm = () => {
            customConfirmModal.classList.remove('show');
            confirmOkBtn.removeEventListener('click', handleConfirm);
            confirmCancelBtn.removeEventListener('click', handleCancel);
            customConfirmModal.removeEventListener('click', handleClickOutside);
            resolve(true);
        };

        const handleCancel = () => {
            customConfirmModal.classList.remove('show');
            confirmOkBtn.removeEventListener('click', handleConfirm);
            confirmCancelBtn.removeEventListener('click', handleCancel);
            customConfirmModal.removeEventListener('click', handleClickOutside);
            resolve(false);
        };

        const handleClickOutside = (event) => {
            if (event.target === customConfirmModal) {
                handleCancel();
            }
        };

        confirmOkBtn.addEventListener('click', handleConfirm);
        confirmCancelBtn.addEventListener('click', handleCancel);
        customConfirmModal.addEventListener('click', handleClickOutside);
    });
}

// Event Listener para el botón de cerrar del modal de alerta
modalCloseBtn.addEventListener('click', closeAlert);

// Opcional: Cerrar el modal de alerta haciendo clic fuera del contenido
customAlertModal.addEventListener('click', (event) => {
    if (event.target === customAlertModal) {
        closeAlert();
    }
});

function generateRandomQrCodeUrl() {
    const randomString = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    return `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(randomString)}`;
}

function updateBackButtonsVisibility() {
    if (bookingFlowContainer.style.display !== 'none') {
        backButtonBooking.style.display = 'block';
        backButtonReceipt.style.display = 'none';
    } else if (receiptContainer.style.display !== 'none') {
        backButtonBooking.style.display = 'none';
        backButtonReceipt.style.display = 'block';
    } else {
        backButtonBooking.style.display = 'block';
        backButtonReceipt.style.display = 'none';
    }
}

async function loadShowtimes() {
    try {
        const response = await fetch(`/api/funciones/pelicula/${PELICULA_ID}`);
        const funciones = await response.json();

        if (funciones.length === 0) {
            showtimeSelectionDiv.innerHTML = '<p>No hay funciones disponibles para esta película.</p>';
            return;
        }

        showtimeSelectionDiv.innerHTML = '';
        funciones.forEach(func => {
            const btn = document.createElement('button');
            btn.className = 'showtime-btn';
            const date = new Date(func.horario);
            const formattedDate = date.toLocaleDateString('es-ES', { weekday: 'short', month: 'short', day: 'numeric' });
            const formattedTime = date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: false });
            btn.textContent = `${formattedDate} ${formattedTime} - Sala ${func.sala_nombre}`;
            btn.dataset.functionId = func.id;
            btn.dataset.salaId = func.id_sala;
            btn.dataset.salaName = func.sala_nombre;
            btn.dataset.horario = func.horario;
            btn.addEventListener('click', () => selectShowtime(btn, func));
            showtimeSelectionDiv.appendChild(btn);
        });
    } catch (error) {
        console.error('Error al cargar funciones:', error);
        showtimeSelectionDiv.innerHTML = '<p>Error al cargar funciones.</p>';
    }
}

async function selectShowtime(button, func) {
    document.querySelectorAll('.showtime-btn').forEach(btn => btn.classList.remove('selected'));
    button.classList.add('selected');

    selectedFunctionId = func.id;
    currentRoomName = func.sala_nombre;
    const date = new Date(func.horario);
    const formattedDate = date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
    const formattedTime = date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: false });
    currentShowtimeText = `${formattedDate} ${formattedTime}`;

    selectedSeats = [];
    updateSummary();
    await loadSeats(selectedFunctionId);
    seatMapContainer.style.display = 'flex';
    checkEnableBuyButton();
}

async function loadSeats(functionId) {
    try {
const response = await fetch(`/api/asientos/funcion/${functionId}`);
        const asientos = await response.json();

        availableSeatsMap.clear();
        seatMapDiv.innerHTML = '';

        let seatCounter = 1;
        for (let r = 0; r < SEAT_ROWS; r++) {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'seat-row';
            const rowLetter = String.fromCharCode(65 + r);

            const seatsInThisRow = (r === SEAT_ROWS - 1) ? SEATS_PER_ROW_LAST : SEATS_PER_ROW;

            for (let c = 0; c < seatsInThisRow; c++) {
                const seatNumber = rowLetter + (c + 1);
                const seatData = asientos.find(a => a.numero_asiento === seatNumber);

                if (seatData) {
                    availableSeatsMap.set(seatData.id, { numero: seatData.numero_asiento, disponible: seatData.disponible == 1 });

                    const seat = document.createElement('div');
                    seat.className = 'seat';
                    seat.textContent = (c + 1);
                    seat.dataset.seatNumber = seatData.numero_asiento;
                    seat.dataset.seatId = seatData.id;

                    if (seatData.disponible == 0) {
                        seat.classList.add('unavailable');
                        seat.title = 'No disponible';
                    } else {
                        seat.addEventListener('click', () => toggleSeatSelection(seat, seatData.id, seatData.numero_asiento));
                    }
                    rowDiv.appendChild(seat);
                }
            }
            seatMapDiv.appendChild(rowDiv);
        }
    } catch (error) {
        console.error('Error al cargar asientos:', error);
        seatMapDiv.innerHTML = '<p>Error al cargar asientos.</p>';
    }
}

function toggleSeatSelection(seatElement, seatId, seatNumber) {
    const isAvailable = availableSeatsMap.get(seatId)?.disponible;

    if (!isAvailable) return;

    const index = selectedSeats.findIndex(s => s.id === seatId);

    if (index > -1) {
        selectedSeats.splice(index, 1);
        seatElement.classList.remove('selected');
        seatElement.classList.remove('unavailable');
        seatElement.style.backgroundColor = '#5cb85c';
        seatElement.style.borderColor = '#4cae4c';
    } else {
        selectedSeats.push({ id: seatId, numero: seatNumber });
        seatElement.classList.add('selected');
        seatElement.style.backgroundColor = '#007bff';
        seatElement.style.borderColor = '#0056b3';
    }
    updateSummary();
    checkEnableBuyButton();
}

function updateSummary() {
    selectedSeatsListDiv.innerHTML = '';
    if (selectedSeats.length === 0) {
        selectedSeatsListDiv.innerHTML = '<div class="summary-item"><span>Asientos Seleccionados:</span> <span>Ninguno</span></div>';
    } else {
        const seatsNumbers = selectedSeats.map(s => s.numero).join(', ');
        selectedSeatsListDiv.innerHTML = `<div class="summary-item"><span>Asientos Seleccionados:</span> <span>${seatsNumbers}</span></div>`;
    }
    const total = selectedSeats.length * SEAT_PRICE;
    totalPriceSpan.textContent = `$${total.toFixed(2)}`;
}

paymentButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        paymentButtons.forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedPaymentMethod = btn.dataset.method;
        checkEnableBuyButton();
    });
});

function checkEnableBuyButton() {
    if (selectedFunctionId && selectedSeats.length > 0 && selectedPaymentMethod) {
        buyTicketsBtn.disabled = false;
    } else {
        buyTicketsBtn.disabled = true;
    }
}

buyTicketsBtn.addEventListener('click', async () => {
    if (!selectedFunctionId || selectedSeats.length === 0 || !selectedPaymentMethod) {
        showAlert('Error de Selección', 'Por favor, selecciona una función, al menos un asiento y un método de pago.');
        return;
    }

    const confirmed = await showConfirm(
        'Confirmar Compra',
        `¿Confirmar compra de ${selectedSeats.length} boleto(s) por $${(selectedSeats.length * SEAT_PRICE).toFixed(2)}?`
    );

    if (!confirmed) {
        return;
    }

    try {
       const response = await fetch('/api/compra/comprar', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        function_id: selectedFunctionId,
        seat_ids: selectedSeats.map(s => s.id),
        total_price: selectedSeats.length * SEAT_PRICE,
        payment_method: selectedPaymentMethod
    })
});

        const result = await response.json();

        if (result.success) {
            selectedSeats.forEach(seat => {
                const seatElement = document.querySelector(`.seat[data-seat-id="${seat.id}"]`);
                if (seatElement) {
                    seatElement.classList.remove('selected');
                    seatElement.classList.add('unavailable');
                    seatElement.style.backgroundColor = '#d9534f';
                    seatElement.style.borderColor = '#c9302c';
                    seatElement.removeEventListener('click', toggleSeatSelection);
                }
            });
            selectedSeats.forEach(seat => {
                const currentData = availableSeatsMap.get(seat.id);
                if (currentData) {
                    currentData.disponible = false;
                    availableSeatsMap.set(seat.id, currentData);
                }
            });

            bookingFlowContainer.style.display = 'none';
            receiptContainer.style.display = 'block';

            document.getElementById('receipt-movie-title').textContent = MOVIE_TITLE;
document.getElementById('receipt-showtime').textContent = currentShowtimeText;
document.getElementById('receipt-room-name').textContent = currentRoomName;
document.getElementById('receipt-seats').textContent = selectedSeats.map(s => s.numero).join(', ');
document.getElementById('receipt-payment-method').textContent = selectedPaymentMethod;
document.getElementById('receipt-purchase-date').textContent = new Date().toLocaleString();
document.getElementById('receipt-total-price').textContent = `$${(selectedSeats.length * SEAT_PRICE).toFixed(2)}`;
document.getElementById('receipt-qr').src = generateRandomQrCodeUrl();

            showAlert('¡Compra Exitosa!', '¡Tu compra ha sido procesada correctamente! Disfruta tu película.');

            selectedSeats = [];
            selectedFunctionId = null;
            selectedPaymentMethod = null;
            updateSummary();
            checkEnableBuyButton();
            document.querySelectorAll('.showtime-btn').forEach(btn => btn.classList.remove('selected'));
            paymentButtons.forEach(btn => btn.classList.remove('selected'));

        } else {
            showAlert('Error al Procesar Compra', 'Error al procesar la compra: ' + result.message);
        }
    } catch (error) {
        console.error('Error en la solicitud de compra:', error);
        showAlert('Error Inesperado', 'Ocurrió un error inesperado al comprar los boletos.');
    } finally {
        updateBackButtonsVisibility();
    }
});

loadShowtimes();
updateBackButtonsVisibility();