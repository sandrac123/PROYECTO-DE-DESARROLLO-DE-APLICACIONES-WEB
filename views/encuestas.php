<?php
// filepath: views/encuestas.php
?>
<!DOCTYPE html>
<html>

<head>
    <title>Listado de Encuestas</title>
</head>

<body>
    <h1>Encuestas</h1>
    <ul>
        <?php foreach ($encuestas as $encuesta): ?>
            <li><?php echo htmlspecialchars($encuesta['nombre']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>