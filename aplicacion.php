<?php
// Configuración de la conexión usando las variables de entorno de Docker
$host = 'db'; // Nombre del servicio en docker-compose
$db   = $_ENV['MYSQL_DATABASE'] ?? 'biblioteca_db';
$user = $_ENV['MYSQL_USER'] ?? 'user_biblioteca';
$pass = $_ENV['MYSQL_PASSWORD'] ?? 'user_password_seguro';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo "Fallo de conexión a la Base de Datos: " . $e->getMessage();
    exit;
}

// --- LÓGICA DE PROCESAMIENTO CRUD ---

// 1. CREAR (Create)
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $stmt = $pdo->prepare('INSERT INTO resenas (titulo_libro, autor, calificacion, comentario) VALUES (?, ?, ?, ?)');
    $stmt->execute([$_POST['titulo_libro'], $_POST['autor'], $_POST['calificacion'], $_POST['comentario']]);
    header("Location: aplicacion.php");
    exit;
}

// 2. ACTUALIZAR (Update)
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $stmt = $pdo->prepare('UPDATE resenas SET titulo_libro = ?, autor = ?, calificacion = ?, comentario = ? WHERE id = ?');
    $stmt->execute([$_POST['titulo_libro'], $_POST['autor'], $_POST['calificacion'], $_POST['comentario'], $_POST['id']]);
    header("Location: aplicacion.php");
    exit;
}

// 3. BORRAR (Delete)
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM resenas WHERE id = ?');
    $stmt->execute([$_GET['delete']]);
    header("Location: aplicacion.php");
    exit;
}

// 4. LEER TODOS LOS REGISTROS (Read)
$stmt = $pdo->query('SELECT * FROM resenas ORDER BY fecha_creacion DESC');
$resenas = $stmt->fetchAll();

// Buscar registro individual si se va a editar
$resena_a_editar = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM resenas WHERE id = ?');
    $stmt->execute([$_GET['edit']]);
    $resena_a_editar = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca de Reseñas de Libros</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f4f9; }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007BFF; color: white; }
        .form-box { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 500px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; color: white; }
        .btn-submit { background-color: #28a745; }
        .btn-edit { background-color: #ffc107; color: black; }
        .btn-delete { background-color: #dc3545; }
        .btn-cancel { background-color: #6c757d; }
    </style>
</head>
<body>

    <h1>📚 Menú Principal: Biblioteca de Reseñas</h1>
    <hr>

    <div class="form-box">
        <?php if ($resena_a_editar): ?>
            <h2>Modificar Reseña </h2>
            <form action="aplicacion.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?php echo $resena_a_editar['id']; ?>">
                
                <div class="form-group">
                    <label>Título del Libro:</label>
                    <input type="text" name="titulo_libro" value="<?php echo htmlspecialchars($resena_a_editar['titulo_libro']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Autor:</label>
                    <input type="text" name="autor" value="<?php echo htmlspecialchars($resena_a_editar['autor']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Calificación (1 a 5):</label>
                    <select name="calificacion">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $resena_a_editar['calificacion'] == $i ? 'selected' : ''; ?>><?php echo $i; ?> ⭐</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Comentario / Reseña:</label>
                    <textarea name="comentario" rows="4" required><?php echo htmlspecialchars($resena_a_editar['comentario']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-submit">Guardar Cambios</button>
                <a href="aplicacion.php" class="btn btn-cancel">Cancelar</a>
            </form>
        <?php else: ?>
            <h2>Añadir Nueva Reseña </h2>
            <form action="aplicacion.php" method="POST">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label>Título del Libro:</label>
                    <input type="text" name="titulo_libro" placeholder="Ej: Don Quijote" required>
                </div>
                <div class="form-group">
                    <label>Autor:</label>
                    <input type="text" name="autor" placeholder="Ej: Miguel de Cervantes" required>
                </div>
                <div class="form-group">
                    <label>Calificación (1 a 5):</label>
                    <select name="calificacion">
                        <option value="5">5 ⭐⭐⭐⭐⭐</option>
                        <option value="4">4 ⭐⭐⭐⭐</option>
                        <option value="3">3 ⭐⭐⭐</option>
                        <option value="2">2 ⭐⭐</option>
                        <option value="1">1 ⭐</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Comentario / Reseña:</label>
                    <textarea name="comentario" rows="4" placeholder="Escribe aquí qué te pareció el libro..." required></textarea>
                </div>
                <button type="submit" class="btn btn-submit">Publicar Reseña</button>
            </form>
        <?php endif; ?>
    </div>

    <h2>Reseñas Registradas </h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Libro</th>
                <th>Autor</th>
                <th>Calificación</th>
                <th>Comentario</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($resenas) == 0): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No hay reseñas aún. ¡Sé el primero en agregar una!</td>
                </tr>
            <?php else: ?>
                <?php foreach ($resenas as $r): ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($r['titulo_libro']); ?></strong></td>
                        <td><?php echo htmlspecialchars($r['autor']); ?></td>
                        <td><?php echo str_repeat('⭐', $r['calificacion']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($r['comentario'])); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($r['fecha_creacion'])); ?></td>
                        <td>
                            <a href="aplicacion.php?edit=<?php echo $r['id']; ?>" class="btn btn-edit">Editar </a>
                            <a href="aplicacion.php?delete=<?php echo $r['id']; ?>" onclick="return confirm('¿Seguro que deseas borrar esta reseña?')" class="btn btn-delete">Borrar </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
