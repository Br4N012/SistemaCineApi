<?php include("src/config/database.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cine Nizami</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <h1>CINEAN</h1>
    </header>

    <section class="destacadas">
        <div class="fondo" id="fondo"></div>
        <div class="pelicula-info" id="pelicula-info">
            <h2 id="titulo-destacada"></h2>
            <p id="sinopsis-destacada"></p>
            <button id="comprar-boletos" class="btn-comprar" style="display: none;">Comprar Boletos</button>
        </div>
        <div class="peliculas-destacadas">
            <?php
                $query_junio = "SELECT id, titulo, poster, sinopsis FROM peliculas WHERE mes_estreno = 'junio' LIMIT 5";
                $result_junio = $conn->query($query_junio);
                if ($result_junio->num_rows > 0) {
                    while ($row_junio = $result_junio->fetch_assoc()) {
                        echo '<div class="card" data-id="' . $row_junio["id"] . '" data-img="images/' . $row_junio["poster"] . '" data-titulo="' . htmlspecialchars($row_junio["titulo"]) . '" data-sinopsis="' . htmlspecialchars($row_junio["sinopsis"]) . '">
                                <img src="images/' . $row_junio["poster"] . '" alt="' . $row_junio["titulo"] . '">
                              </div>';
                    }
                } else {
                    echo '<p>No hay películas destacadas para junio.</p>';
                }
            ?>
        </div>
    </section>

    <section class="coming-soon">
        <h2>Próximamente</h2>

        <nav class="main-nav">
            <button class="nav-item" data-month="julio" data-default-active="true">Julio</button>
            <button class="nav-item" data-month="agosto">Agosto</button>
            <button class="nav-item" data-month="septiembre">Septiembre</button>
        </nav>
        
        <div class="grid" id="peliculas-grid">
            <?php

            ?>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fondo = document.getElementById('fondo');
            const peliculaInfo = document.getElementById('pelicula-info');
            const tituloDestacada = document.getElementById('titulo-destacada');
            const sinopsisDestacada = document.getElementById('sinopsis-destacada');
            const comprarBoletosBtn = document.getElementById('comprar-boletos');
            const cards = document.querySelectorAll('.card');
            const navItems = document.querySelectorAll('.nav-item');
            const peliculasGrid = document.getElementById('peliculas-grid');

            let currentCardIndex = 0;
            let intervalId;

            function updateFeaturedMovie(index) {
                const card = cards[index];
                if (card) {
                    fondo.style.backgroundImage = `url(${card.dataset.img})`;
                    tituloDestacada.textContent = card.dataset.titulo;
                    sinopsisDestacada.textContent = card.dataset.sinopsis;
                    comprarBoletosBtn.style.display = 'block';
                    comprarBoletosBtn.onclick = () => {
                        window.location.href = `detalle_pelicula.php?id=${card.dataset.id}`;
                    };
                    cards.forEach(c => c.classList.remove('active-card'));
                    card.classList.add('active-card');
                }
            }

            function startAutoSlide() {
                clearInterval(intervalId); 
                intervalId = setInterval(() => {
                    currentCardIndex = (currentCardIndex + 1) % cards.length;
                    updateFeaturedMovie(currentCardIndex);
                }, 5000); 
            }

            if (cards.length > 0) {
                updateFeaturedMovie(0);
                startAutoSlide();
            }

            cards.forEach((card, index) => {
                card.addEventListener('click', () => { 
                    currentCardIndex = index;
                    updateFeaturedMovie(currentCardIndex);
                    startAutoSlide(); 
                });
            });

            navItems.forEach(item => {
                item.addEventListener('click', (event) => {
                    event.preventDefault();
                    navItems.forEach(nav => nav.classList.remove('active'));
                    item.classList.add('active');

                    const selectedMonth = item.dataset.month;
                    fetch(`get_movies_by_month.php?month=${selectedMonth}`)
                        .then(response => response.text())
                        .then(data => {
                            peliculasGrid.innerHTML = data;
                        })
                        .catch(error => console.error('Error al cargar películas:', error));
                });
            });

            const urlParams = new URLSearchParams(window.location.search);
            const initialMonth = urlParams.get('mes');
            if (initialMonth) {
                const initialNavItem = document.querySelector(`.nav-item[data-month="${initialMonth}"]`);
                if (initialNavItem) {
                    initialNavItem.click();
                }
            } else {
                const defaultNavItem = document.querySelector('.nav-item[data-default-active="true"]');
                if (defaultNavItem) {
                    defaultNavItem.click();
                }
            }
        });
    </script>
</body>
</html>