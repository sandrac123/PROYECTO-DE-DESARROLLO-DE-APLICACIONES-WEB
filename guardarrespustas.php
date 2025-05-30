<?php
$conexion = new mysqli("localhost", "root", "", "web");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

date_default_timezone_set('Europe/Madrid'); // Ajusta según tu zona horaria
$usuario_id = 1; // Aquí deberías obtenerlo dinámicamente (por sesión, por ejemplo)
$fecha = date("Y-m-d H:i:s");

// Recoge los datos del formulario
$frecuenciaCompras = $_POST['frecuencia_compras'];
$lugarCompra = $_POST['lugar_compra'];
$recomienda = $_POST['recomienda'];
$calificacionAtencion = $_POST['calificacion_atencion'];
$metodoPago = $_POST['metodo_pago'];
$factoresDecision = $_POST['factores_decision'];
$razonConsumo = $_POST['razon_consumo'];
$presentacionProducto = $_POST['presentacion_producto'];
$relacionConOtros = $_POST['relacion_con_otros'];
$comentarioAdicional = $_POST['comentario_adicional'];

// Puedes definir manualmente los IDs de preguntas si sabes cuáles son en la BD.
// Aquí se insertan como texto abierto (preguntas tipo 'abierta')
$preguntas = [
    "¿Con qué frecuencia realizas tus compras?" => $frecuenciaCompras,
    "¿Dónde compra la mayoría de los productos que utiliza?" => $lugarCompra,
    "¿Le recomendaría nuestro producto/servicios a otros clientes?" => $recomienda,
    "¿Cómo calificarías la atención recibida?" => $calificacionAtencion,
    "¿Qué métodos de pago prefiere?" => $metodoPago,
    "¿Qué factores influyen en sus decisiones de compra?" => $factoresDecision,
    "¿Sabías qué productos o servicios estás consumiendo y por qué los eliges?" => $razonConsumo,
    "¿Cómo calificarías la presentación del producto o servicio?" => $presentacionProducto,
    "¿Cómo fue tu relación con otros consumidores o usuarios?" => $relacionConOtros,
    "¿Tienes algún comentario adicional?" => $comentarioAdicional,
];

// Buscar los IDs de las preguntas en la base de datos
foreach ($preguntas as $texto_pregunta => $respuesta) {
    $stmt = $conexion->prepare("SELECT id FROM preguntas WHERE texto = ?");
    $stmt->bind_param("s", $texto_pregunta);
    $stmt->execute();
    $stmt->bind_result($pregunta_id);
    if ($stmt->fetch()) {
        $stmt->close();

        // Insertar la respuesta
        $insert = $conexion->prepare("INSERT INTO respostas (usuario_id, pregunta_id, texto_resposta, data) VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiss", $usuario_id, $pregunta_id, $respuesta, $fecha);
        $insert->execute();
        $insert->close();
    } else {
        $stmt->close();
    }
}

$conexion->close();

echo "Gracias por completar la encuesta.";
?>
