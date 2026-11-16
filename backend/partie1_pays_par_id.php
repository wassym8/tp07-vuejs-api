<?php
require __DIR__ . '/config.php';

// Validation côté serveur (on ne fait jamais confiance au frontend)
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    reponse(['success' => false, 'message' => "L'identifiant doit être un nombre entier."], 400);
}

$pdo  = getPDO();
$stmt = $pdo->prepare('SELECT nom FROM pays WHERE id_pays = :id');
$stmt->execute([':id' => $id]);
$pays = $stmt->fetch();

if ($pays) {
    reponse(['success' => true, 'nom' => $pays['nom']]);
}
reponse(['success' => false, 'message' => "Pays non trouvé."], 404);
