<?php
session_start();

// Conexión á base de datos
$pdo = new PDO("mysql:host=localhost;dbname=enquisa_db;charset=utf8", "user", "pass");

// Verifica ID de enquisa
$enquisa_id = $_GET['id'] ?? null;
if (!$enquisa_id) die("❌ Falta o ID da enquisa");

// Comproba se está finalizada ou se é admin
$stmt = $pdo->prepare("SELECT nome, finalizada FROM enquisa WHERE id = ?");
$stmt->execute([$enquisa_id]);
$enquisa = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$enquisa) die("❌ Enquisa non atopada");

$is_admin = $_SESSION['is_admin'] ?? false;
if (!$enquisa['finalizada'] && !$is_admin) {
    die("⛔ Resultados só dispoñibles para administrador ou se a enquisa está finalizada.");
}

// Obter preguntas
$stmt = $pdo->prepare("SELECT id, texto, tipo FROM pregunta WHERE enquisa_id = ? ORDER BY id");
$stmt->execute([$enquisa_id]);
$preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Para os gráficos
$data_js = [];

foreach ($preguntas as &$p) {
    if ($p['tipo'] === 'texto') {
        $stmt = $pdo->prepare("SELECT valor FROM respostas WHERE pregunta_id = ? ORDER BY id DESC LIMIT 20");
        $stmt->execute([$p['id']]);
        $p['respostas'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
        $stmt = $pdo->prepare("SELECT valor, COUNT(*) as total FROM respostas WHERE pregunta_id = ? GROUP BY valor");
        $stmt->execute([$p['id']]);
        $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        $total = array_sum($rows);
        $p['resumo'] = $rows;
        $p['total'] = $total;
        $data_js[] = [
            'id' => $p['id'],
            'labels' => array_keys($rows),
            'values' => array_values($rows)
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="gl">
<head>
    <meta charset="UTF-8">
    <title>Resultados - <?php echo htmlspecialchars($enquisa['nome']) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f9fc;
            margin: 0;
            padding: 40px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: auto;
        }
        .card {
            background: #fff;
            margin-bottom: 30px;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        h2 {
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .comment {
            background: #f1f1f1;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-style: italic;
        }
        .chart {
            max-width: 100%;
            height: 320px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>📊 Resultados da enquisa: <?php echo htmlspecialchars($enquisa['nome']); ?></h1>

    <?php foreach ($preguntas as $p): ?>
        <div class="card">
            <h2><?php echo htmlspecialchars($p['texto']); ?></h2>

            <?php if ($p['tipo'] === 'texto'): ?>
                <?php if (empty($p['respostas'])): ?>
                    <p>Sen respostas.</p>
                <?php else: ?>
                    <?php foreach ($p['respostas'] as $r): ?>
                        <div class="comment">📝 <?php echo htmlspecialchars($r); ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

            <?php else: ?>
                <canvas id="chart_<?php echo $p['id']; ?>" class="chart"></canvas>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
<?php foreach ($data_js as $q): ?>
    new Chart(document.getElementById('chart_<?php echo $q['id']; ?>'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($q['labels']); ?>,
            datasets: [{
                label: 'Número de respostas',
                data: <?php echo json_encode($q['values']); ?>,
                backgroundColor: '#3498db'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const total = <?php echo array_sum($q['values']); ?>;
                            const count = ctx.raw;
                            const pct = (count / total * 100).toFixed(1);
                            return `${count} respostas (${pct}%)`;
                        }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
<?php endforeach; ?>
</script>
</body>
</html>
