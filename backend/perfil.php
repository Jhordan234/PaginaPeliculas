<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$nombre = $_SESSION['nombre'];

// Obtener foto de perfil
$stmt = $conn->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();
$foto_perfil = $usuario['foto_perfil'] ? "../{$usuario['foto_perfil']}" : '../bd_imagenes/usuarios/default_profile.jpg';

// Subir foto de perfil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['foto_perfil'])) {
    $target_dir = "D:/programas/Xampp/htdocs/PaginaPelumes/bd_imagenes/usuarios/";
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0755, true)) {
            $error = "Error: No se pudo crear la carpeta $target_dir.";
        }
    }
    if (is_dir($target_dir) && !is_writable($target_dir)) {
        $error = "Error: La carpeta $target_dir no tiene permisos de escritura.";
    }
    if (!isset($error)) {
        $imageFileType = strtolower(pathinfo($_FILES["foto_perfil"]["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . "usuario_$usuario_id.$imageFileType";
        $allowed_types = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'tiff'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
                $relative_path = "bd_imagenes/usuarios/usuario_$usuario_id.$imageFileType";
                $stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
                $stmt->execute([$relative_path, $usuario_id]);
                $foto_perfil = "../$relative_path";
            } else {
                $error = "Error al subir la foto: No se pudo mover el archivo a $target_file.";
            }
        } else {
            $error = "Formato de imagen no permitido: $imageFileType.";
        }
    }
}

// Buscador y resultados
$search_query = $_POST['search_query'] ?? '';
$search_results = [];
$recomendaciones = [];
$recomendaciones_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($search_query)) {
    // Buscar película
    $stmt = $conn->prepare("SELECT id, titulo, poster, genero FROM peliculas WHERE titulo LIKE ?");
    $stmt->execute(["%$search_query%"]);
    $search_results = $stmt->fetchAll();
    if ($search_results) {
        // Obtener recomendaciones basadas en el género de la primera película encontrada
        $genero = $search_results[0]['genero'];
        $stmt = $conn->prepare("SELECT id, titulo, poster FROM peliculas WHERE genero = ? AND id NOT IN (SELECT id FROM peliculas WHERE titulo LIKE ?) ORDER BY RAND() LIMIT 5");
        $stmt->execute([$genero, "%$search_query%"]);
        $recomendaciones = $stmt->fetchAll();
        $recomendaciones_message = "Recomendaciones basadas en el género: $genero";
    } else {
        $recomendaciones_message = "No se encontró la película en la base de datos.";
    }
}

// Manejar agregar favoritos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pelicula_id'])) {
    $pelicula_id = $_POST['pelicula_id'];
    try {
        $stmt = $conn->prepare("INSERT INTO favoritos (usuario_id, pelicula_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE pelicula_id = pelicula_id");
        $stmt->execute([$usuario_id, $pelicula_id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit();
}

// Obtener favoritas (máximo 5 para vista previa)
$stmt = $conn->prepare("SELECT p.id, p.titulo, p.poster FROM favoritos f JOIN peliculas p ON f.pelicula_id = p.id WHERE f.usuario_id = ? LIMIT 5");
$stmt->execute([$usuario_id]);
$favoritas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="device-width, initial-scale=1.0">
    <title>CineFuturo - Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./perfil.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">CineFuturo</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="profile-container">
            <h2>Bienvenido, <?php echo htmlspecialchars($nombre); ?>!</h2>
            <div class="profile-header">
                <div class="profile-pic">
                    <img src="<?php echo $foto_perfil; ?>" alt="Foto de perfil" onerror="this.src='../bd_imagenes/usuarios/default_profile.jpg'">
                </div>
                <div class="profile-buttons">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="foto_perfil" accept="image/*" id="foto_perfil" class="d-none">
                        <label for="foto_perfil" class="btn favorite-btn">Cambiar Foto</label>
                    </form>
                    <a href="../favoritos.php" class="btn favorite-btn">Ver Favoritos</a>
                </div>
            </div>

            <div class="search-bar mt-4">
                <form method="POST" id="searchForm">
                    <div class="input-group">
                        <input type="text" name="search_query" id="searchInput" placeholder="Buscar película..." value="<?php echo htmlspecialchars($search_query); ?>" class="form-control">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>

            <?php if ($search_results): ?>
                <h3 class="category-title">Resultados de la búsqueda</h3>
                <div class="row">
                    <?php foreach ($search_results as $movie): ?>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="movie-card">
                                <img src="../<?php echo $movie['poster']; ?>" alt="<?php echo $movie['titulo']; ?>" class="movie-poster" onerror="this.src='../bd_imagenes/placeholder.jpg'">
                                <div class="card-body">
                                    <h5 class="movie-title"><?php echo htmlspecialchars($movie['titulo']); ?></h5>
                                    <button class="btn favorite-btn" onclick="addToFavorites(<?php echo $movie['id']; ?>, '<?php echo addslashes($movie['titulo']); ?>')">Agregar a Favoritos</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($recomendaciones_message): ?>
                <div class="alert alert-info"><?php echo $recomendaciones_message; ?></div>
            <?php endif; ?>
            <?php if ($recomendaciones): ?>
                <h3 class="category-title">Recomendaciones</h3>
                <div class="row">
                    <?php foreach ($recomendaciones as $movie): ?>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="movie-card">
                                <img src="../<?php echo $movie['poster']; ?>" alt="<?php echo $movie['titulo']; ?>" class="movie-poster" onerror="this.src='../bd_imagenes/placeholder.jpg'">
                                <div class="card-body">
                                    <h5 class="movie-title"><?php echo htmlspecialchars($movie['titulo']); ?></h5>
                                    <button class="btn favorite-btn" onclick="addToFavorites(<?php echo $movie['id']; ?>, '<?php echo addslashes($movie['titulo']); ?>')">Agregar a Favoritos</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h3 class="category-title">Tus Favoritas</h3>
            <div class="row">
                <?php foreach ($favoritas as $movie): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="movie-card">
                            <img src="../<?php echo $movie['poster']; ?>" alt="<?php echo $movie['titulo']; ?>" class="movie-poster" onerror="this.src='../bd_imagenes/placeholder.jpg'">
                            <div class="card-body">
                                <h5 class="movie-title"><?php echo htmlspecialchars($movie['titulo']); ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2025 CineFuturo. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./perfil.js"></script>
</body>
</html>