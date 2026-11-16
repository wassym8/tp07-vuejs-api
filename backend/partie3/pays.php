<?php
require __DIR__ . '/../config.php';

$pdo = getPDO();
$liste = $pdo->query('SELECT id_pays, nom FROM pays ORDER BY nom')->fetchAll();
reponse($liste);
