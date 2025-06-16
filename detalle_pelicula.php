<?php
// detalle_pelicula.php
include("src/config/database.php"); // Adjust path if necessary based on your actual structure

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect or show an error if no valid movie ID is provided
    header("Location: funciones/index.php"); // Redirect to home
    exit;
}

$pelicula_id = $_GET['id'];

// Fetch movie details
$stmt = $conn->prepare("SELECT titulo, poster FROM peliculas WHERE id = ?");
$stmt->bind_param("i", $pelicula_id);
$stmt->execute();
$pelicula_result = $stmt->get_result();
$pelicula = $pelicula_result->fetch_assoc();
$stmt->close();

if (!$pelicula) {
    header("Location: funciones/index.php"); // Movie not found
    exit;
}

// Fetch available rooms
$salas_query = "SELECT id, nombre FROM salas";
$salas_result = $conn->query($salas_query);
$salas = [];
while ($row = $salas_result->fetch_assoc()) {
    $salas[$row['id']] = $row['nombre'];
}

// Define fixed seating layout for simplicity (you could fetch this from DB if more complex)
// For now, let's assume all salas have this fixed layout for display purposes.
// In a real system, sala capacity and layout would be more dynamic.
$seat_rows = 7;
$seats_per_row = 10; // For rows 1-6
$seats_per_row_last = 8; // For row 7 (example)
$seat_price = 85.00; // Example price per seat in MXN

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Boletos para <?php echo htmlspecialchars($pelicula['titulo']); ?></title>
    <link rel="stylesheet" href="style.css"> <style>
        /* Specific styles for detalle_pelicula.php */
        body {
            background-color: #1a1a1a !important; /* Darker background for booking page */
            color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .booking-container {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
            gap: 2rem;
            padding: 3rem;
            max-width: 1200px;
            margin: 2rem auto;
            background-color: #2a2a2a;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            flex-grow: 1; /* Allow it to grow and fill available space */
        }

        .movie-info-panel {
            flex: 1;
            min-width: 280px; /* Minimum width for the info panel */
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .movie-info-panel img {
            width: 100%;
            max-width: 250px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            margin-bottom: 1rem;
        }

        .movie-info-panel h2 {
            font-size: 2.2rem;
            color: #e50914; /* Red accent for title */
            margin-bottom: 0.5rem;
        }

        .movie-info-panel p {
            font-size: 1.1rem;
            color: #ccc;
            line-height: 1.6;
        }

        .booking-details-panel {
            flex: 2; /* Takes more space */
            min-width: 450px; /* Minimum width for booking panel */
            background-color: #333;
            padding: 2rem;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .booking-details-panel h3 {
            font-size: 1.8rem;
            color: #f0f0f0;
            margin-bottom: 1rem;
            border-bottom: 1px solid #444;
            padding-bottom: 0.5rem;
        }

        .section-title {
            font-size: 1.4rem;
            color: #f0f0f0;
            margin-bottom: 1rem;
            margin-top: 1.5rem;
        }

        .showtime-selection, .payment-options {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
        }

        .showtime-btn, .payment-btn {
            background-color: #555;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, border 0.3s ease;
            white-space: nowrap; /* Prevent button text from wrapping */
        }

        .showtime-btn:hover, .payment-btn:hover {
            background-color: #777;
        }

        .showtime-btn.selected, .payment-btn.selected {
            background-color: #e50914; /* Active state for showtime/payment */
            border: 2px solid #ff0a16;
        }

        .seat-map-container {
            background-color: #444;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: none; /* Hidden by default until function is selected */
            flex-direction: column;
            align-items: center;
        }

        .screen-indicator {
            width: 80%;
            height: 15px;
            background-color: #e50914;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            text-align: center;
            line-height: 15px;
            font-size: 0.8rem;
            color: white;
        }

        .seat-row {
            display: flex;
            justify-content: center; /* Center seats in the row */
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .seat {
            width: 30px;
            height: 30px;
            background-color: #5cb85c; /* Available (Green) */
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: white;
            font-weight: bold;
            transition: background-color 0.2s ease, transform 0.2s ease;
            border: 1px solid #4cae4c;
        }

        .seat.unavailable {
            background-color: #d9534f; /* Unavailable (Red) */
            cursor: not-allowed;
            border: 1px solid #c9302c;
        }

        .seat.selected {
            background-color: #007bff; /* Selected (Blue) */
            border: 1px solid #0056b3;
            transform: scale(1.1);
        }

        /* Responsive adjustments for seat size on smaller screens */
        @media (max-width: 768px) {
            .seat {
                width: 25px;
                height: 25px;
                font-size: 0.7rem;
            }
            .seat-row {
                gap: 0.3rem;
            }
        }


        .summary-panel {
            background-color: #3d3d3d;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .summary-panel h4 {
            font-size: 1.3rem;
            color: #f0f0f0;
            margin-bottom: 0.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            font-size: 1rem;
            color: #bbb;
        }

        .summary-total {
            font-size: 1.5rem;
            font-weight: bold;
            color: #e50914;
            border-top: 1px solid #555;
            padding-top: 0.8rem;
            margin-top: 0.5rem;
            display: flex;
            justify-content: space-between;
        }

        .final-purchase-btn {
            background-color: #e50914;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.3rem;
            cursor: pointer;
            margin-top: 2rem;
            transition: background-color 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .final-purchase-btn:hover {
            background-color: #ff0a16;
        }

        /* --- Receipt Page Styles (for the PDF-like view) --- */
        .receipt-container {
            background-color: #fff;
            color: #333;
            padding: 2rem;
            margin: 2rem auto;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            font-family: Arial, sans-serif;
            display: none; /* Hidden by default */
        }
        .receipt-container h2 {
            text-align: center;
            color: #e50914;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        .receipt-details p {
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        .receipt-details strong {
            color: #555;
        }
        .receipt-summary {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px dashed #ccc;
            font-size: 1.1rem;
        }
        .receipt-summary div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .receipt-qr-code {
            text-align: center;
            margin-top: 2rem;
        }
        .receipt-qr-code img {
            width: 150px;
            height: 150px;
            border: 1px solid #eee;
            padding: 5px;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #777;
        }
        .print-pdf-btn {
            background-color: #007bff;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            margin-top: 1.5rem;
            display: block;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }
        .print-pdf-btn:hover {
            background-color: #0056b3;
        }

        /* Hide elements for PDF printing */
        @media print {
            body > *:not(.receipt-container) {
                display: none !important;
            }
            .receipt-container {
                display: block !important;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }
            .print-pdf-btn {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="booking-container" id="booking-flow">
        <div class="movie-info-panel">
            <img src="images/<?php echo htmlspecialchars($pelicula['poster']); ?>" alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>">
            <h2><?php echo htmlspecialchars($pelicula['titulo']); ?></h2>
            <p>Selecciona una función y tus asientos para continuar con la compra.</p>
        </div>

        <div class="booking-details-panel">
            <h3>Seleccionar Función</h3>
            <div class="showtime-selection" id="showtime-selection">
                <p>Cargando funciones...</p>
            </div>

            <div class="seat-map-container" id="seat-map-container">
                <div class="screen-indicator">PANTALLA</div>
                <div id="seat-map">
                    </div>
            </div>

            <div class="summary-panel">
                <h4>Resumen de la Compra</h4>
                <div id="selected-seats-list">
                    <div class="summary-item"><span>Asientos Seleccionados:</span> <span>Ninguno</span></div>
                </div>
                <div class="summary-total">
                    <span>Total a Pagar:</span> <span id="total-price">$0.00</span>
                </div>
            </div>

            <div class="payment-options">
                <div class="section-title">Método de Pago:</div>
                <button class="payment-btn" data-method="efectivo">Efectivo</button>
                <button class="payment-btn" data-method="tarjeta">Tarjeta</button>
            </div>

            <button class="final-purchase-btn" id="buy-tickets-btn" disabled>Comprar Boletos</button>
        </div>
    </div>

    <div class="receipt-container" id="receipt-container">
        <h2>¡Compra Exitosa!</h2>
        <div class="receipt-details">
            <p><strong>Película:</strong> <span id="receipt-movie-title"></span></p>
            <p><strong>Función:</strong> <span id="receipt-showtime"></span></p>
            <p><strong>Sala:</strong> <span id="receipt-room-name"></span></p>
            <p><strong>Asientos:</strong> <span id="receipt-seats"></span></p>
            <p><strong>Método de Pago:</strong> <span id="receipt-payment-method"></span></p>
            <p><strong>Fecha de Compra:</strong> <span id="receipt-purchase-date"></span></p>
        </div>
        <div class="receipt-summary">
            <div><span>Total Pagado:</span> <span id="receipt-total-price"></span></div>
        </div>
        <div class="receipt-qr-code">
            <img id="receipt-qr" src="" alt="QR Code">
            <p>Escanea para más detalles</p>
        </div>
        <div class="receipt-footer">
            <p>Gracias por tu compra en Cine Nizami.</p>
            <p>¡Disfruta tu película!</p>
        </div>
        <button class="print-pdf-btn" onclick="window.print()">Guardar como PDF</button>
    </div>


    <script>
        // Global variables for booking
        const PELICULA_ID = <?php echo $pelicula_id; ?>;
        const SEAT_PRICE = <?php echo $seat_price; ?>;
        const SEAT_ROWS = <?php echo $seat_rows; ?>;
        const SEATS_PER_ROW = <?php echo $seats_per_row; ?>; // General
        const SEATS_PER_ROW_LAST = <?php echo $seats_per_row_last; ?>; // Last row might be different

        let selectedFunctionId = null;
        let selectedSeats = []; // Stores objects: {numero: 'A1', id: asiento_id, is_selected: true}
        let availableSeatsMap = new Map(); // Maps asiento_id to {numero, disponible}
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

        // Function to generate a random QR code URL (for demonstration)
        function generateRandomQrCodeUrl() {
            const randomString = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            return `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(randomString)}`;
        }


        // 1. Load Showtimes
        async function loadShowtimes() {
            try {
                // Adjust path for get_funciones.php
                const response = await fetch(`src/get_funciones.php?pelicula_id=${PELICULA_ID}`);
                const funciones = await response.json();

                if (funciones.length === 0) {
                    showtimeSelectionDiv.innerHTML = '<p>No hay funciones disponibles para esta película.</p>';
                    return;
                }

                showtimeSelectionDiv.innerHTML = ''; // Clear "Cargando funciones..."
                funciones.forEach(func => {
                    const btn = document.createElement('button');
                    btn.className = 'showtime-btn';
                    // Format date and time
                    const date = new Date(func.horario);
                    const formattedDate = date.toLocaleDateString('es-ES', { weekday: 'short', month: 'short', day: 'numeric' });
                    const formattedTime = date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: false });
                    btn.textContent = `${formattedDate} ${formattedTime} - Sala ${func.sala_nombre}`;
                    btn.dataset.functionId = func.id;
                    btn.dataset.salaId = func.id_sala;
                    btn.dataset.salaName = func.sala_nombre;
                    btn.dataset.horario = func.horario; // Store full datetime
                    btn.addEventListener('click', () => selectShowtime(btn, func));
                    showtimeSelectionDiv.appendChild(btn);
                });
            } catch (error) {
                console.error('Error al cargar funciones:', error);
                showtimeSelectionDiv.innerHTML = '<p>Error al cargar funciones.</p>';
            }
        }

        // 2. Select Showtime
        async function selectShowtime(button, func) {
            document.querySelectorAll('.showtime-btn').forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');

            selectedFunctionId = func.id;
            currentRoomName = func.sala_nombre;
            const date = new Date(func.horario);
            const formattedDate = date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
            const formattedTime = date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: false });
            currentShowtimeText = `${formattedDate} ${formattedTime}`;


            selectedSeats = []; // Reset selected seats when new function is chosen
            updateSummary();
            await loadSeats(selectedFunctionId);
            seatMapContainer.style.display = 'flex'; // Show seat map
            checkEnableBuyButton();
        }

        // 3. Load Seats for Selected Function
        async function loadSeats(functionId) {
            try {
                // Adjust path for get_asientos.php
                const response = await fetch(`src/get_asientos.php?function_id=${functionId}`);
                const asientos = await response.json();

                availableSeatsMap.clear();
                seatMapDiv.innerHTML = ''; // Clear existing seats

                let seatCounter = 1;
                for (let r = 0; r < SEAT_ROWS; r++) {
                    const rowDiv = document.createElement('div');
                    rowDiv.className = 'seat-row';
                    const rowLetter = String.fromCharCode(65 + r); // A, B, C...

                    const seatsInThisRow = (r === SEAT_ROWS - 1) ? SEATS_PER_ROW_LAST : SEATS_PER_ROW;

                    for (let c = 0; c < seatsInThisRow; c++) {
                        const seatNumber = rowLetter + (c + 1);
                        const seatData = asientos.find(a => a.numero_asiento === seatNumber);

                        if (seatData) {
                             availableSeatsMap.set(seatData.id, { numero: seatData.numero_asiento, disponible: seatData.disponible == 1 });

                            const seat = document.createElement('div');
                            seat.className = 'seat';
                            seat.textContent = (c + 1); // Display only number for simplicity in small seat box
                            seat.dataset.seatNumber = seatData.numero_asiento;
                            seat.dataset.seatId = seatData.id;

                            if (seatData.disponible == 0) { // Check if 'disponible' is 0 (false)
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


        // 4. Toggle Seat Selection
        function toggleSeatSelection(seatElement, seatId, seatNumber) {
            const isAvailable = availableSeatsMap.get(seatId)?.disponible;

            if (!isAvailable) return; // Cannot select unavailable seats

            const index = selectedSeats.findIndex(s => s.id === seatId);

            if (index > -1) {
                // Deselect
                selectedSeats.splice(index, 1);
                seatElement.classList.remove('selected');
                seatElement.classList.remove('unavailable'); // Ensure it reverts to available color
                seatElement.style.backgroundColor = '#5cb85c'; // Explicitly set to green
                seatElement.style.borderColor = '#4cae4c';
            } else {
                // Select
                selectedSeats.push({ id: seatId, numero: seatNumber });
                seatElement.classList.add('selected');
                seatElement.style.backgroundColor = '#007bff'; // Explicitly set to blue
                seatElement.style.borderColor = '#0056b3';
            }
            updateSummary();
            checkEnableBuyButton();
        }

        // 5. Update Summary Panel
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

        // 6. Select Payment Method
        paymentButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                paymentButtons.forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                selectedPaymentMethod = btn.dataset.method;
                checkEnableBuyButton();
            });
        });

        // 7. Enable/Disable Buy Button
        function checkEnableBuyButton() {
            if (selectedFunctionId && selectedSeats.length > 0 && selectedPaymentMethod) {
                buyTicketsBtn.disabled = false;
            } else {
                buyTicketsBtn.disabled = true;
            }
        }

        // 8. Handle Purchase
        buyTicketsBtn.addEventListener('click', async () => {
            if (!selectedFunctionId || selectedSeats.length === 0 || !selectedPaymentMethod) {
                alert('Por favor, selecciona una función, al menos un asiento y un método de pago.');
                return;
            }

            // Confirm with user
            if (!confirm(`Confirmar compra de ${selectedSeats.length} boleto(s) por $${(selectedSeats.length * SEAT_PRICE).toFixed(2)}?`)) {
                return;
            }

            try {
                const response = await fetch('src/process_purchase.php', { // Adjust path
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        function_id: selectedFunctionId,
                        seat_ids: selectedSeats.map(s => s.id),
                        total_price: selectedSeats.length * SEAT_PRICE,
                        payment_method: selectedPaymentMethod,
                        movie_title: '<?php echo htmlspecialchars($pelicula['titulo']); ?>',
                        showtime: currentShowtimeText,
                        room_name: currentRoomName
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Update UI: Mark bought seats as unavailable
                    selectedSeats.forEach(seat => {
                        const seatElement = document.querySelector(`.seat[data-seat-id="${seat.id}"]`);
                        if (seatElement) {
                            seatElement.classList.remove('selected');
                            seatElement.classList.add('unavailable');
                            seatElement.style.backgroundColor = '#d9534f'; // Ensure red
                            seatElement.style.borderColor = '#c9302c';
                            seatElement.removeEventListener('click', toggleSeatSelection); // Prevent re-selection
                        }
                    });
                    // Update internal map
                    selectedSeats.forEach(seat => {
                         const currentData = availableSeatsMap.get(seat.id);
                         if (currentData) {
                             currentData.disponible = false;
                             availableSeatsMap.set(seat.id, currentData);
                         }
                    });

                    // Show Receipt
                    bookingFlowContainer.style.display = 'none';
                    receiptContainer.style.display = 'block';

                    document.getElementById('receipt-movie-title').textContent = result.receipt.movie_title;
                    document.getElementById('receipt-showtime').textContent = result.receipt.showtime;
                    document.getElementById('receipt-room-name').textContent = result.receipt.room_name;
                    document.getElementById('receipt-seats').textContent = result.receipt.seats_numbers;
                    document.getElementById('receipt-payment-method').textContent = result.receipt.payment_method;
                    document.getElementById('receipt-purchase-date').textContent = result.receipt.purchase_date;
                    document.getElementById('receipt-total-price').textContent = `$${parseFloat(result.receipt.total_price).toFixed(2)}`;
                    document.getElementById('receipt-qr').src = generateRandomQrCodeUrl();


                    alert('¡Compra realizada con éxito!');
                    // Reset selection for new purchase
                    selectedSeats = [];
                    selectedFunctionId = null;
                    selectedPaymentMethod = null;
                    updateSummary();
                    checkEnableBuyButton();
                    document.querySelectorAll('.showtime-btn').forEach(btn => btn.classList.remove('selected'));
                    paymentButtons.forEach(btn => btn.classList.remove('selected'));

                } else {
                    alert('Error al procesar la compra: ' + result.message);
                }
            } catch (error) {
                console.error('Error en la solicitud de compra:', error);
                alert('Ocurrió un error inesperado al comprar los boletos.');
            }
        });

        // Initial load
        loadShowtimes();
    </script>
</body>
</html>