<?php
/* ──────────────────────── ❶ CONFIGURACIÓN ───────────────────────── */
$DB_HOST = 'localhost';    
$DB_NAME = 'web';     
$DB_USER = 'root';   
$DB_PASS = '';   

/* ──────────────────────── CONEXIÓN PDO ─────────────────────────── */
<?php
// Conectar con PDO
try {
    $pdo = new PDO("mysql:host=localhost;dbname=web;charset=utf8mb4", 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "✅ Conexión exitosa.";
} catch (PDOException $e) {
    echo "❌ Conexión fallida: " . $e->getMessage();
}

// Obtener datos
$stmt = $pdo->query("SELECT * FROM encuesta_curso ORDER BY fecha_envio DESC");
$respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respuestas del Curso</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f9f9f9 }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left }
        th { background: #eee }
    </style>
</head>
<body>
    <h1>📋 Respuestas Recibidas</h1>
    <?php if ($respuestas): ?>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Curso</th>
                    <th>Primera vez</th>
                    <th>Contenido</th>
                    <th>Interés</th>
                    <th>Recomendación</th>
                    <th>Material útil</th>
                    <th>Cumplió</th>
                    <th>Interacción</th>
                    <th>Comentarios</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($respuestas as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['fecha_envio']) ?></td>
                    <td><?= htmlspecialchars($r['curso']) ?></td>
                    <td><?= htmlspecialchars($r['primera_vez']) ?></td>
                    <td><?= $r['contenido'] ?></td>
                    <td><?= htmlspecialchars($r['interes_formacion']) ?></td>
                    <td><?= htmlspecialchars($r['recomendacion']) ?></td>
                    <td><?= htmlspecialchars($r['material_util']) ?></td>
                    <td><?= $r['cumplio_expectativas'] ?></td>
                    <td><?= $r['interaccion_profesor'] ?></td>
                    <td><?= nl2br(htmlspecialchars($r['comentarios'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay respuestas aún.</p>
    <?php endif; ?>
</body>
</html>

