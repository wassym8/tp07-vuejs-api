<?php
require __DIR__ . '/config.php';

$idPays = filter_input(INPUT_GET, 'id_pays', FILTER_VALIDATE_INT);
if ($idPays === false || $idPays === null) {
    reponse(['success' => false, 'message' => 'id_pays invalide.'], 400);
}

$pdo  = getPDO();
$stmt = $pdo->prepare('SELECT id_ville, nom FROM ville WHERE id_pays = :id ORDER BY nom');
$stmt->execute([':id' => $idPays]);
reponse($stmt->fetchAll());
