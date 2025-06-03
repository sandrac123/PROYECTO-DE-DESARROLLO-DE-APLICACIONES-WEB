<?php
$encuesta_creada = false;
$titulo = $descripcion = $estado = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = htmlspecialchars($_POST["titulo"]);
    $descripcion = htmlspecialchars($_POST["descripcion"]);
    $estado = htmlspecialchars($_POST["estado"]);
    $encuesta_creada = true;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crear Encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 0;
            background: linear-gradient(135deg, #fceabb 0%, #e0c3fc 100%);
        }

        .form-container {
            background-color: #fdf6fd;
            max-width: 700px;
            margin: 0 auto;
            padding: 35px 40px;
            border-radius: 22px;
            box-shadow: 0 8px 24px rgba(180, 180, 255, 0.12);
            border: 2px solid #b2dfdb;
        }

        h1 {
            font-size: 2.5rem;
            text-align: center;
            color: #a084ca;
            margin-bottom: 10px;
        }

        .form-text {
            text-align: center;
            color: #7b8fa1;
            font-size: 1.1rem;
            margin-bottom: 30px;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        label {
            font-weight: bold;
            color: #6d6875;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 2px solid #e0c3fc;
            background-color: #f7f6fa;
            color: #5e548e;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #b2dfdb;
            box-shadow: 0 0 6px #b2dfdb77;
        }

        .btn-encuesta {
            background-color: #b2dfdb;
            border: none;
            padding: 12px 20px;
            color: #5e548e;
            font-weight: bold;
            border-radius: 12px;
            transition: background-color 0.3s ease, color 0.3s;
            width: 100%;
            font-size: 1rem;
        }

        .btn-encuesta:hover {
            background-color: #a084ca;
            color: #fff;
        }

        .encuesta-creada {
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
        <h1>🎉 Creación de la Encuesta</h1>
        <p class="form-text">Complete la información para crear una nueva encuesta</p>

        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="titulo">Título de la encuesta</label>
                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ej. Encuesta de eventos"
                    required value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>" />
            </div>

            <div class="mb-3">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                    placeholder="Información sobre la encuesta" required><?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="estado">Estado de la encuesta</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="" disabled <?php if (!isset($_POST['estado'])) echo 'selected'; ?>>Seleccione el estado
                    </option>
                    <option value="activo" <?php if (isset($_POST['estado']) && $_POST['estado'] == 'activo') echo 'selected'; ?>>Activa
                    </option>
                    <option value="inactivo" <?php if (isset($_POST['estado']) && $_POST['estado'] == 'inactivo') echo 'selected'; ?>>Inactiva
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-encuesta">🚀 Crear Encuesta</button>
        </form>

        <?php if ($encuesta_creada): ?>
            <div class="encuesta-creada mt-4">
                <h3>✅ Encuesta creada:</h3>
                <p><strong>Título:</strong> <?php echo $titulo; ?></p>
                <p><strong>Descripción:</strong> <?php echo $descripcion; ?></p>
                <p><strong>Estado:</strong> <?php echo ucfirst($estado); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
