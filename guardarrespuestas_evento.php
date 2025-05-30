<?php
/*******************************************************
 *  guardarrespuestas_evento.php
 *  Guarda las respuestas del formulario “Encuesta
 *  de Satisfacción de un evento” en la tabla `respostas`
 *******************************************************/
date_default_timezone_set('Europe/Madrid');     // tu zona horaria
try {
    $pdo = new PDO('mysql:host=localhost;dbname=web;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// En producción, aquí debes obtener el usuario conectado (ejemplo: desde sesión)
$usuario_id = 1;  

$fecha = date('Y-m-d H:i:s');

/*------------------------------------------------------
|  Mapa texto-de-pregunta  ->  _POST[name]
|  La clave es el texto exacto que debe coincidir con
|  la columna `texto` en tu tabla `preguntas`.
|  El valor es el "name" del input en el formulario.
------------------------------------------------------*/
$map = [
  '¿Cómo calificarías tu experiencia general?' => 'experiencia_general',
  '¿Qué fue lo que más te gustó durante el evento?' => 'aspecto_favorito',
  '¿La explicación por parte de los profesionales estuvo bien desarrollada?' => 'apoyo',
  '¿El servicio del catering durante la celebración/exposición fue la mejor?' => 'servicio_catering',
  '¿Recomendarías este evento a otros?' => 'servicio_adecuado',
  '¿Crees quela dinámica del evento estuvo de acorde a la atención al público?' => 'dinamica_evento',
  '¿Te gusto el evento?' => 'gusto_evento',
  '¿cual fue la escultura que más te gusto?' => 'escultura_favorita',
  '¿Qué tal fue tu relación con los otros participantes?' => 'relacion_participantes',
  'Comentarios adicionales:' => 'comentarios'
];

/*------------------------------------------------------
|  Preparar la consulta para insertar respuestas
------------------------------------------------------*/
$insert = $pdo->prepare(
  "INSERT INTO respostas (usuario_id, pregunta_id, texto_resposta, data)
   VALUES (:usuario_id, :pregunta_id, :texto_resposta, :data)"
);

/*------------------------------------------------------
|  Procesar cada pregunta y su respuesta enviada
------------------------------------------------------*/
foreach ($map as $textoPregunta => $campoPost) {

  // 1. Buscar el id de la pregunta en la tabla preguntas
  $pregunta = $pdo->prepare("SELECT id FROM preguntas WHERE texto = :texto LIMIT 1");
  $pregunta->execute([':texto' => $textoPregunta]);
  $row = $pregunta->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
      // Si no existe la pregunta, insertarla con enquisa_id=0 y tipo='abierta'
      $pdo->prepare("INSERT INTO preguntas (enquisa_id, texto, tipo) VALUES (0, :texto, 'abierta')")
          ->execute([':texto' => $textoPregunta]);
      $pregunta_id = $pdo->lastInsertId();
  } else {
      $pregunta_id = $row['id'];
  }

  // 2. Obtener la respuesta enviada (o null si no existe)
  $respuesta = $_POST[$campoPost] ?? null;

  // 3. Si está vacía o null, no insertar nada
  if ($respuesta === null || trim($respuesta) === '') {
      continue;
  }

  // 4. Insertar la respuesta en la tabla respostas
  $insert->execute([
      ':usuario_id'     => $usuario_id,
      ':pregunta_id'    => $pregunta_id,
      ':texto_resposta' => $respuesta,
      ':data'           => $fecha
  ]);
}

/*------------------------------------------------------
|  Mensaje de confirmación al usuario
------------------------------------------------------*/
echo "<h2 style='font-family:Arial;text-align:center;margin-top:40px'>
        ✅ ¡Gracias por tu tiempo!<br>Tu encuesta ha sido registrada.
      </h2>";
?>
