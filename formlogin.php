<?php
session_start();

// Inicializar preguntas si no existen
if (!isset($_SESSION['preguntas'])) {
    $_SESSION['preguntas'] = [
        [
            'pregunta' => '¿Qué metas fueron las más claras?',
            'opciones' => ['Meta 1', 'Meta 2', 'Meta 3', 'Meta 4'],
            'correcta' => 0,
            'respuestas' => [0, 0, 0, 0]
        ],
        [
            'pregunta' => '¿Cuál es la mejor opción para tu carrera dentro del sector?',
            'opciones' => ['Opción A', 'Opción B', 'Opción C', 'Opción D'],
            'correcta' => 1,
            'respuestas' => [0, 0, 0, 0]
        ]
    ];
}

$mensaje = '';
$mostrar_resultados = false;

// Añadir opción a una pregunta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pregunta_id'], $_POST['texto'])) {
    $id = intval($_POST['pregunta_id']);
    $texto = trim($_POST['texto']);
    if ($texto !== '' && isset($_SESSION['preguntas'][$id])) {
        $_SESSION['preguntas'][$id]['opciones'][] = $texto;
        $_SESSION['preguntas'][$id]['respuestas'][] = 0;
        $mensaje = 'Opción añadida correctamente.';
    }
}

// Responder pregunta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['responder'], $_POST['pregunta_responder'], $_POST['opcion'])) {
    $pid = intval($_POST['pregunta_responder']);
    $oid = intval($_POST['opcion']);
    if (isset($_SESSION['preguntas'][$pid]['respuestas'][$oid])) {
        $_SESSION['preguntas'][$pid]['respuestas'][$oid]++;
        $mostrar_resultados = $pid;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Encuesta interactiva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f2f6fc;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
        }

        .form-container {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #4f5bd5;
        }

        .btn-submit {
            width: 100%;
            background-color: #4f5bd5;
            color: white;
            border: none;
            padding: 12px;
            font-weight: bold;
            border-radius: 8px;
        }

        .btn-submit:hover {
            background-color: #3949ab;
        }

        .resultados {
            background: #e0f7fa;
            border: 2px solid #b2dfdb;
            border-radius: 14px;
            padding: 22px;
            margin-top: 32px;
            color: #5e548e;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Engadir Opción á Pregunta</h2>
        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="pregunta_id" class="form-label">Pregunta</label>
                <select class="form-select" id="pregunta_id" name="pregunta_id" required>
                    <option value="" disabled selected>Selecciona unha pregunta</option>
                    <?php foreach ($_SESSION['preguntas'] as $idx => $p): ?>
                        <option value="<?php echo $idx; ?>"><?php echo htmlspecialchars($p['pregunta']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="texto" class="form-label">Texto da opción</label>
                <input type="text" class="form-control" id="texto" name="texto" placeholder="Nova opción" required />
            </div>
            <button type="submit" class="btn btn-submit">💾 Engadir Opción</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Responder unha Pregunta</h2>
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="pregunta_responder" class="form-label">Pregunta</label>
                <select class="form-select" id="pregunta_responder" name="pregunta_responder" required onchange="mostrarOpcions()">
                    <option value="" disabled selected>Selecciona unha pregunta</option>
                    <?php foreach ($_SESSION['preguntas'] as $idx => $p): ?>
                        <option value="<?php echo $idx; ?>"><?php echo htmlspecialchars($p['pregunta']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3" id="opciones-container"></div>
            <input type="hidden" name="responder" value="1" />
            <button type="submit" class="btn btn-submit">Responder</button>
        </form>
    </div>

    <?php if ($mostrar_resultados !== false): ?>
        <div class="form-container resultados">
            <h2>Resultados</h2>
            <p><strong>Pregunta:</strong> <?php echo htmlspecialchars($_SESSION['preguntas'][$mostrar_resultados]['pregunta']); ?></p>
            <ul>
                <?php foreach ($_SESSION['preguntas'][$mostrar_resultados]['opciones'] as $i => $op): ?>
                    <li>
                        <?php echo htmlspecialchars($op); ?>
                        <?php if ($i == $_SESSION['preguntas'][$mostrar_resultados]['correcta']): ?>
                            <span style="color:#4f5bd5;">(Correcta)</span>
                        <?php endif; ?>
                        - <strong><?php echo $_SESSION['preguntas'][$mostrar_resultados]['respuestas'][$i]; ?></strong> respostas
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <script>
        // Mostrar dinámicamente as opcións segundo a pregunta seleccionada
        const preguntas = <?php echo json_encode($_SESSION['preguntas']); ?>;

        function mostrarOpcions() {
            const select = document.getElementById('pregunta_responder');
            const idx = select.value;
            const opcionesDiv = document.getElementById('opciones-container');
            opcionesDiv.innerHTML = '';
            if (preguntas[idx]) {
                preguntas[idx].opciones.forEach((op, i) => {
                    opcionesDiv.innerHTML += `
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="opcion" id="opcion${i}" value="${i}" required>
                            <label class="form-check-label" for="opcion${i}">
                                ${op}
                            </label>
                        </div>
                    `;
                });
            }
        }
    </script>
</body>

</html>