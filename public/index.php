<?php
// filepath: public/index.php
require_once __DIR__ . '/../controllers/EncuestaController.php';

$controller = new EncuestaController();
$controller->index();
