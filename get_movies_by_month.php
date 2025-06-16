<?php
include("src/config/database.php"); 

if (isset($_GET['month'])) {
    $selectedMonth = $_GET['month'];

    $selectedMonth = $conn->real_escape_string($selectedMonth);

    $query = "SELECT titulo, poster, genero, clasificacion, duracion FROM peliculas WHERE mes_estreno = '$selectedMonth'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="pelicula">
                    <img src="images/' . htmlspecialchars($row["poster"]) . '" alt="' . htmlspecialchars($row["titulo"]) . '">
                    <h3>' . htmlspecialchars($row["titulo"]) . '</h3>
                    <p>' . htmlspecialchars($row["genero"]) . ' | ' . htmlspecialchars($row["clasificacion"]) . ' | ' . htmlspecialchars($row["duracion"]) . ' min</p>
                  </div>';
        }
    } else {
        echo '<p>No hay películas para ' . htmlspecialchars($selectedMonth) . '.</p>';
    }
} else {
    echo '<p>Mes no especificado.</p>';
}

$conn->close();
?>