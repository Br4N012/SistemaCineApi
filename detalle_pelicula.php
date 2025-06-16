<?php
include("src/config/database.php"); 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: funciones/index.php"); 
    exit;
}

$pelicula_id = $_GET['id'];

$stmt = $conn->prepare("SELECT titulo, poster FROM peliculas WHERE id = ?");
$stmt->bind_param("i", $pelicula_id);
$stmt->execute();
$pelicula_result = $stmt->get_result();
$pelicula = $pelicula_result->fetch_assoc();
$stmt->close();

if (!$pelicula) {
    header("Location: funciones/index.php"); 
    exit;
}

$salas_query = "SELECT id, nombre FROM salas";
$salas_result = $conn->query($salas_query);
$salas = [];
while ($row = $salas_result->fetch_assoc()) {
    $salas[$row['id']] = $row['nombre'];
}

$seat_rows = 7;
$seats_per_row = 10; 
$seats_per_row_last = 8; 
$seat_price = 85.00; 

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Boletos para <?php echo htmlspecialchars($pelicula['titulo']); ?></title>
    <link rel="stylesheet" href="public/css/style.css"> 
    <link rel="stylesheet" href="public/css/peliculas.css">
    
</head>
<body>
    <button class="back-button for-booking" id="back-button-booking" onclick="window.location.href='index.php'">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M10.828 12l4.95-4.95a1 1 0 00-1.414-1.414L8.707 11.293a1 1 0 000 1.414l5.657 5.657a1 1 0 001.414-1.414L10.828 12z"/>
        </svg>
    </button>

    <button class="back-button for-receipt" id="back-button-receipt" onclick="window.location.href='index.php'">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M10.828 12l4.95-4.95a1 1 0 00-1.414-1.414L8.707 11.293a1 1 0 000 1.414l5.657 5.657a1 1 0 001.414-1.414L10.828 12z"/>
        </svg>
    </button>

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

    <div id="custom-alert-modal" class="modal-overlay">
        <div class="modal-content">
            <h3 id="modal-title"></h3>
            <p id="modal-message"></p>
            <button id="modal-close-btn" class="modal-button">Aceptar</button>
        </div>
    </div>

    <div id="custom-confirm-modal" class="modal-overlay">
        <div class="modal-content">
            <h3 id="confirm-modal-title"></h3>
            <p id="confirm-modal-message"></p>
            <div class="modal-buttons">
                <button id="confirm-cancel-btn" class="modal-button cancel-button">Cancelar</button>
                <button id="confirm-ok-btn" class="modal-button">Aceptar</button>
            </div>
        </div>
    </div>

    <script>
        const PELICULA_ID = <?php echo $pelicula_id; ?>;
        const SEAT_PRICE = <?php echo $seat_price; ?>;
        const SEAT_ROWS = <?php echo $seat_rows; ?>;
        const SEATS_PER_ROW = <?php echo $seats_per_row; ?>;
        const SEATS_PER_ROW_LAST = <?php echo $seats_per_row_last; ?>;
        const MOVIE_TITLE = '<?php echo htmlspecialchars($pelicula['titulo']); ?>'; 
    </script>
    <script src="public/js/detallePeli.js"></script>
</body>
</html>