<?php
// Configuration commune : connexion PDO + helpers JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

function getPDO(): PDO {
    try {
        return new PDO(
            'mysql:host=localhost;dbname=tp07;charset=utf8mb4',
            'root',   
            '',       
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    } catch (PDOException $e) {
        reponse(['success' => false, 'message' => 'Erreur de connexion à la base de données.'], 500);
    }
}

function reponse(array $data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
