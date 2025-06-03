<?php
// filepath: controllers/EncuestaController.php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Encuesta.php';

class EncuestaController
{
    public function index()
    {
        $database = new Database();
        $db = $database->getConnection();
        $encuesta = new Encuesta($db);
        $result = $encuesta->obtenerTodas();
        $encuestas = $result->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../views/encuestas.php';
    }
}
