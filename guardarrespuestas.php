<?php
/* ──────────────────────── ❶ CONFIGURACIÓN ───────────────────────── */
$DB_HOST = 'localhost';    
$DB_NAME = 'web';     
$DB_USER = 'root';   
$DB_PASS = 'contraseña';   

/* ──────────────────────── CONEXIÓN PDO ─────────────────────────── */
try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER, $DB_PASS,
        [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
    );
} catch (PDOException $e) {
    die('❌ Conexión fallida: ' . $e->getMessage());
}

/* ──────────────────── ❷ PROCESAR ENVÍO DEL FORM ────────────────── */
$ok      = false;         // para mostrar mensaje de éxito
$errores = [];            // para guardar errores de validación

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1) Recoger campos (operador null-coalescing para evitar «undefined index»)
    $datos = [
        'curso'                 => $_POST['curso']                ?? '',
        'primera_vez'           => $_POST['primera_vez']          ?? '',
        'contenido'             => $_POST['contenido']            ?? '',
        'interes_formacion'     => $_POST['interes_formacion']    ?? '',
        'recomendacion'         => $_POST['recomendacion']        ?? '',
        'material_util'         => $_POST['material_util']        ?? '',
        'cumplio_expectativas'  => $_POST['cumplio_expectativas'] ?? '',
        'interaccion_profesor'  => $_POST['interaccion_profesor'] ?? '',
        'comentarios'           => trim($_POST['comentarios'] ?? '')
    ];

    // 2) Validación sencilla
    foreach ($datos as $campo => $valor) {
        if ($campo !== 'comentarios' && ($valor === '' || str_starts_with($valor, 'Selecciona'))) {
            $errores[] = "El campo <b>$campo</b> es obligatorio.";
        }
    }

    // 3) Si todo OK, insertar
    if (!$errores) {
        $sql = "INSERT INTO encuesta_curso
                (fecha_envio, curso, primera_vez, contenido, interes_formacion,
                 recomendacion, material_util, cumplio_expectativas,
                 interaccion_profesor, comentarios)
                VALUES (NOW(), :curso, :primera_vez, :contenido, :interes_formacion,
                        :recomendacion, :material_util, :cumplio, :interaccion, :comentarios)";
        try {
            $pdo->prepare($sql)->execute([
                ':curso'             => $datos['curso'],
                ':primera_vez'       => $datos['primera_vez'],
                ':contenido'         => $datos['contenido'],
                ':interes_formacion' => $datos['interes_formacion'],
                ':recomendacion'     => $datos['recomendacion'],
                ':material_util'     => $datos['material_util'],
                ':cumplio'           => $datos['cumplio_expectativas'],
                ':interaccion'       => $datos['interaccion_profesor'],
                ':comentarios'       => $datos['comentarios']
            ]);
            $ok = true;
        } catch (PDOException $e) {
            $errores[] = 'Error al guardar: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Valoración del Curso</title>

    <!-- ======= Estilos originales ======= -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:#eef2f5;color:#333;padding:60px 20px}
        .container{max-width:700px;margin:auto;background:#fff;padding:40px;border-radius:16px;
                   box-shadow:0 8px 24px rgb(0 0 0 / .1)}
        h1{font-size:2rem;text-align:center;margin-bottom:10px;color:#2c3e50}
        .description{text-align:center;font-size:1rem;margin-bottom:30px;color:#7f8c8d}
        .form-group{margin-bottom:20px}
        label{display:block;margin-bottom:8px;font-weight:600}
        input[type=text],select,textarea{width:100%;padding:12px;border:1px solid #ccc;border-radius:8px}
        .options{display:flex;gap:20px;margin-top:10px}
        .options label{font-weight:normal}
        .btn-submit{display:inline-block;width:100%;background:#3498db;color:#fff;font-weight:600;
                    padding:12px;border:none;border-radius:8px;font-size:16px;cursor:pointer;
                    transition:background-color .3s;margin-top:20px}
        .btn-submit:hover{background:#2980b9}
        @media(max-width:600px){.container{padding:20px}}
        .alert{padding:15px;border-radius:8px;margin-bottom:20px}
        .alert-success{background:#e7f9ed;color:#27ae60}
        .alert-error{background:#fdecea;color:#c0392b}
    </style>
</head>
<body>
<div class="container">
    <h1>Encuesta de Valoración del Curso</h1>

    <?php if ($ok): ?>
        <div class="alert alert-success">✅ ¡Gracias por completar la encuesta!</div>
    <?php elseif ($errores): ?>
        <div class="alert alert-error">
            <b>Se encontraron errores:</b><br>
            <?php foreach ($errores as $e) echo "• $e<br>"; ?>
        </div>
    <?php endif; ?>

    <?php if (!$ok): ?> <!-- Solo mostramos el formulario si no se envió correctamente -->
    <p class="description">Tu opinión nos ayuda a mejorar la calidad de nuestros cursos. ¡Gracias por participar!</p>

    <form method="post" action="">
        <!-- ========== FORMULARIO tal como lo diseñaste ========== -->
        <div class="form-group">
            <label for="curso">¿Qué curso estás evaluando?</label>
            <select id="curso" name="curso" required>
                <option disabled selected>Selecciona un curso</option>
                <option value="web">Desarrollo Web</option>
                <option value="ux">Diseño UX/UI</option>
                <option value="ml">Aprendizaje Automático</option>
                <option value="marketing">Marketing Digital</option>
            </select>
        </div>

        <div class="form-group">
            <label>¿Fue la primera vez en este tipo de formación?</label>
            <div class="options">
                <label><input type="radio" name="primera_vez" value="sí" required> Sí</label>
                <label><input type="radio" name="primera_vez" value="no"> No</label>
                <label><input type="radio" name="primera_vez" value="non sei"> Non sei</label>
            </div>
        </div>

        <div class="form-group">
            <label>¿Cómo calificarías el contenido del curso?</label>
            <select name="contenido" required>
                <option disabled selected>Selecciona una opción</option>
                <option value="1">1</option><option value="2">2</option>
                <option value="3">3</option><option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>

        <div class="form-group">
            <label>¿Te interesaría hacer otra formación relacionada?</label>
            <div class="options">
                <label><input type="radio" name="interes_formacion" value="sí" required> Sí</label>
                <label><input type="radio" name="interes_formacion" value="no"> No</label>
                <label><input type="radio" name="interes_formacion" value="non sei"> Non sei</label>
            </div>
        </div>

        <div class="form-group">
            <label>¿Recomendarías este curso a otra persona?</label>
            <div class="options">
                <label><input type="radio" name="recomendacion" value="sí" required> Sí</label>
                <label><input type="radio" name="recomendacion" value="no"> No</label>
                <label><input type="radio" name="recomendacion" value="non sei"> Non sei</label>
            </div>
        </div>

        <div class="form-group">
            <label>¿El material entregado fue útil?</label>
            <div class="options">
                <label><input type="radio" name="material_util" value="sí" required> Sí</label>
                <label><input type="radio" name="material_util" value="no"> No</label>
                <label><input type="radio" name="material_util" value="non sei"> Non sei</label>
            </div>
        </div>

        <div class="form-group">
            <label>¿El curso cumplió tus expectativas?</label>
            <select name="cumplio_expectativas" required>
                <option disabled selected>Selecciona una opción</option>
                <option value="1">1</option><option value="2">2</option>
                <option value="3">3</option><option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>

        <div class="form-group">
            <label>¿La interacción con el profesor fue positiva?</label>
            <select name="interaccion_profesor" required>
                <option disabled selected>Selecciona una opción</option>
                <option value="1">1</option><option value="2">2</option>
                <option value="3">3</option><option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>

        <div class="form-group">
            <label for="comentarios">Comentarios adicionales:</label>
            <textarea name="comentarios" id="comentarios" placeholder="Escriba aquí su opinión..."></textarea>
        </div>

        <button type="submit" class="btn-submit">Enviar Encuesta</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
