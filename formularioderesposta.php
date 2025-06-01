<?php
// --- Configuración da base de datos ---
$DB_HOST = 'localhost';
$DB_NAME = 'web';  
$DB_USER = 'root';
$DB_PASS = '';

// --- Conexión coa base de datos ---
try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die('Erro de conexión coa base de datos: ' . $e->getMessage());
}

// --- Xestión de sesión e token único por usuario ---
session_start();
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(16));
}
$token = $_SESSION['token'];

// --- Obter a enquisa activa con preguntas e opcións ---
$sql = "
SELECT e.id AS enquisa_id,
       e.titulo AS enquisa_titulo,
       p.id AS pregunta_id,
       p.texto AS pregunta_texto,
       p.tipo AS pregunta_tipo,
       GROUP_CONCAT(o.id, ':', o.texto ORDER BY o.id) AS opcion_raw
FROM enquisa e
JOIN pregunta p ON p.enquisa_id = e.id
LEFT JOIN opcion o ON o.pregunta_id = p.id
WHERE e.activa = 1
GROUP BY p.id
ORDER BY p.id
";
$stmt = $pdo->query($sql);
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$datos) {
    die('Non hai ningunha enquisa activa neste momento.');
}

// --- Transformar resultados en estrutura organizada ---
$enquisaTitulo = $datos[0]['enquisa_titulo'];
$enquisaId     = $datos[0]['enquisa_id'];
$preguntas     = [];

foreach ($datos as $fila) {
    $preguntas[$fila['pregunta_id']] = [
        'texto'    => $fila['pregunta_texto'],
        'tipo'     => $fila['pregunta_tipo'],
        'opcion'   => []
    ];
    if ($fila['opcion_raw']) {
        foreach (explode(',', $fila['opcion_raw']) as $op) {
            [$oid, $otext] = explode(':', $op, 2);
            $preguntas[$fila['pregunta_id']]['opcion'][$oid] = $otext;
        }
    }
}

// --- Procesar envío do formulario ---
$exito   = false;
$erros   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        foreach ($preguntas as $pid => $pregunta) {
            $campo = 'preg_' . $pid;

            if ($pregunta['tipo'] === 'checkbox') {
                $valores = $_POST[$campo] ?? [];
                if (!is_array($valores)) $valores = [];

                if (empty($valores)) {
                    $erros[] = "A pregunta '{$pregunta['texto']}' é obrigatoria.";
                } else {
                    foreach ($valores as $val) {
                        gardarResposta($pdo, $enquisaId, $pid, $val, $token);
                    }
                }
            } else {
                $valor = trim($_POST[$campo] ?? '');
                if ($valor === '') {
                    $erros[] = "A pregunta '{$pregunta['texto']}' é obrigatoria.";
                } else {
                    gardarResposta($pdo, $enquisaId, $pid, $valor, $token);
                }
            }
        }

        if (empty($erros)) {
            $pdo->commit();
            $exito = true;
        } else {
            $pdo->rollBack();
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        $erros[] = "Produciuse un erro ao gardar as respostas: " . $e->getMessage();
    }
}

// --- Función para gardar unha resposta ---
function gardarResposta(PDO $pdo, int $enquisaId, int $preguntaId, string $valor, string $token): void {
    $sql = "INSERT INTO respostas (enquisa_id, pregunta_id, valor, identificador)
            VALUES (:enq, :preg, :val, :tok)";
    $pdo->prepare($sql)->execute([
        ':enq'  => $enquisaId,
        ':preg' => $preguntaId,
        ':val'  => $valor,
        ':tok'  => $token
    ]);
}
?>
<!DOCTYPE html>
<html lang="gl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($enquisaTitulo) ?></title>
    <style>
        body {
            font-family: sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }
        .formulario {
            background: white;
            max-width: 700px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .pregunta {
            margin-bottom: 20px;
        }
        .pregunta h3 {
            margin-bottom: 10px;
            font-size: 1.1em;
            color: #34495e;
        }
        .opcion label {
            display: block;
            margin-bottom: 8px;
        }
        textarea, input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: vertical;
        }
        .boton {
            display: block;
            width: 100%;
            background: #3498db;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            margin-top: 20px;
            cursor: pointer;
        }
        .boton:hover {
            background: #2980b9;
        }
        .alerta {
            background: #ffdddd;
            padding: 15px;
            border-left: 6px solid #f44336;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .exito {
            background: #ddffdd;
            border-left: 6px solid #4CAF50;
        }
    </style>
</head>
<body>
<div class="formulario">
    <?php if ($exito): ?>
        <div class="alerta exito">
            ✅ Grazas! As túas respostas foron gardadas correctamente.
        </div>
    <?php else: ?>
        <?php if ($erros): ?>
            <div class="alerta">
                <?php foreach ($erros as $err): ?>
                    <p>⚠️ <?php echo htmlspecialchars($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h1><?php echo htmlspecialchars($enquisaTitulo) ?></h1>
        <form method="post">
            <?php foreach ($preguntas as $pid => $pregunta): ?>
                <div class="pregunta">
                    <h3><?php echo htmlspecialchars($pregunta['texto']) ?></h3>
                    <div class="opcion">
                        <?php if ($pregunta['tipo'] === 'texto'): ?>
                            <textarea name="preg_<?php echo $pid ?>" rows="4" required></textarea>

                        <?php elseif ($pregunta['tipo'] === 'radio'): ?>
                            <?php foreach ($pregunta['opcion'] as $oid => $texto): ?>
                                <label>
                                    <input type="radio" name="preg_<?php echo $pid ?>" value="<?php echo htmlspecialchars($texto) ?>" required>
                                    <?php echo htmlspecialchars($texto) ?>
                                </label>
                            <?php endforeach; ?>

                        <?php elseif ($pregunta['tipo'] === 'checkbox'): ?>
                            <?php foreach ($pregunta['opcion'] as $oid => $texto): ?>
                                <label>
                                    <input type="checkbox" name="preg_<?php echo $pid ?>[]" value="<?php echo htmlspecialchars($texto) ?>">
                                    <?php echo htmlspecialchars($texto) ?>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="boton">Enviar respostas</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
