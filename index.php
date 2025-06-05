<?php
// Simulación de datos de películas (40 películas, 10 por categoría) con imágenes locales
$movies = [
    // Animación
    ["title" => "Spider-Man: Into the Spider-Verse", "category" => "Animación", "poster" => "imagenes/imagen1.jpg"],
    ["title" => "Toy Story 4", "category" => "Animación", "poster" => "imagenes/imagen2.jpg"],
    ["title" => "Coco", "category" => "Animación", "poster" => "imagenes/imagen3.jpg"],
    ["title" => "Inside Out 2", "category" => "Animación", "poster" => "imagenes/imagen4.jpg"],
    ["title" => "Frozen II", "category" => "Animación", "poster" => "imagenes/imagen5.webp"],
    ["title" => "The Incredibles 2", "category" => "Animación", "poster" => "imagenes/imagen6.jpg"],
    ["title" => "Moana", "category" => "Animación", "poster" => "imagenes/imagen7.webp"],
    ["title" => "Zootopia", "category" => "Animación", "poster" => "imagenes/imagen8.webp"],
    // Ciencia Ficción
    ["title" => "Blade Runner 2049", "category" => "Ciencia Ficción", "poster" => "imagenes/imagen9.jpg"],
    ["title" => "Interstellar", "category" => "Ciencia Ficción", "poster" => "imagenes/imagen10.jpg"],
    ["title" => "The Matrix", "category" => "Ciencia Ficción", "poster" => "imagenes/imagen11.jpg"],
    ["title" => "Dune", "category" => "Ciencia Ficción", "poster" => "imagenes/imagen12.jpg"],
    ["title" => "Star Wars: The Force Awakens", "category" => "Ciencia Ficción", "poster" => "imagenes/imagen13.jpg"],
    ["title" => "Arrival", "category" => "Ciencia Ficción", "poster" => "imagenes/imagen14.jpg"],
    ["title" => "Ex Machina", "category" => "Ciencia Ficción", "poster" => "imagenes/imagen15.jpg"],
    ["title" => "Gattaca", "category" => "Ciencia Ficción", "poster" => "imagenes/imagen16.jpg"],
    // Acción
    ["title" => "Mad Max: Fury Road", "category" => "Acción", "poster" => "imagenes/imagen17.jpg"],
    ["title" => "John Wick", "category" => "Acción", "poster" => "imagenes/imagen18.jpg"],
    ["title" => "Die Hard", "category" => "Acción", "poster" => "imagenes/imagen19.webp"],
    ["title" => "The Dark Knight", "category" => "Acción", "poster" => "imagenes/imagen20.jpg"],
    ["title" => "Mission: Impossible - Fallout", "category" => "Acción", "poster" => "imagenes/imagen21.jpg"],
    ["title" => "Gladiator", "category" => "Acción", "poster" => "imagenes/imagen22.jpg"],
    ["title" => "Lethal Weapon", "category" => "Acción", "poster" => "imagenes/imagen23.jpg"],
    ["title" => "Taken", "category" => "Acción", "poster" => "imagenes/imagen24.jpg"],
];

// Películas destacadas para el banner (una por género)
$featured_movies = [
    ["title" => "Spider-Man: Into the Spider-Verse", "category" => "Animación", "poster" => "imagenes/imagen1.jpg"],
    ["title" => "Interstellar", "category" => "Ciencia Ficción", "poster" => "imagenes/imagen10.jpg"],
    ["title" => "Mad Max: Fury Road", "category" => "Acción", "poster" => "imagenes/imagen17.jpg"]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineFuturo - Películas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Orbitron para estilo futurista) -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CineFuturo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="backend/login.php">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="backend/register.php">Registrarse</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sección de búsqueda -->
    <div class="container">
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Busca tu película favorita...">
        </div>
    </div>

    <!-- Banner animado -->
    <div class="container">
        <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php for ($i = 0; $i < count($featured_movies); $i++): ?>
                    <button type="button" data-bs-target="#featuredCarousel" data-bs-slide-to="<?php echo $i; ?>" <?php echo $i === 0 ? 'class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo $i + 1; ?>"></button>
                <?php endfor; ?>
            </div>
            <div class="carousel-inner">
                <?php foreach ($featured_movies as $index => $movie): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="banner-content">
                            <img src="<?php echo $movie['poster']; ?>" alt="<?php echo $movie['title']; ?>" onerror="this.src='this.src=images/posters/placeholder.jpg'">
                            <div class="banner-text">
                                <h2><?php echo $movie['title']; ?></h2>
                                <p><?php echo $movie['category']; ?> - Más Vista</p>
                                <button class="favorite-btn" onclick="addToFavorites('<?php echo $movie['title']; ?>')">
                                    <i class="fas fa-heart"></i> Favorito
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </div>

    <!-- Sección de películas -->
    <div class="container">
        <?php
        // Organizar películas por categoría
        $categories = ["Animación", "Ciencia Ficción", "Acción"];
        foreach ($categories as $category) {
            echo "<h2 class='category-title'>$category</h2>";
            echo "<div class='row'>";
            foreach ($movies as $movie) {
                if ($movie['category'] === $category) {
                    echo "
                    <div class='col-md-3 mb-4'>
                        <div class='movie-card'>
                            <img src='{$movie['poster']}' alt='{$movie['title']}' onerror='this.src=\"images/posters/placeholder.jpg\"'>
                            <div class='p-3'>
                                <h5>{$movie['title']}</h5>
                                <p>{$movie['category']}</p>
                                <button class='favorite-btn' onclick='addToFavorites(\"{$movie['title']}\")'>
                                    <i class='fas fa-heart'></i> Favorito
                                </button>
                            </div>
                        </div>
                    </div>";
                }
            }
            echo "</div>";
        }
        ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2025 CineFuturo. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap JS y script personalizado -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="index.js"></script>
</body>
</html>